<?php
define('UPLOAD_DIR', ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.'img'.DS.'db'.DS);
define('O_DIR', UPLOAD_DIR.'o'.DS);
define('S0_DIR', UPLOAD_DIR.'0'.DS);
define('S1_DIR', UPLOAD_DIR.'1'.DS);
define('S2_DIR', UPLOAD_DIR.'2'.DS);
define('S3_DIR', UPLOAD_DIR.'3'.DS);

class PhotosController extends AppController {
  var $name = 'Photos';
  var $uses = array('Photo', 'Comment');

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'view', 'json', 'recent');
  }

  /* Linked to by photos/:id */
  function index($id=null, $size='O') {
    if (!$id) {
      $id = $this->params['id'];
    }
    $dir = O_DIR;
    if (isset($this->params['s'])) {
      $size = $this->params['s'];
      switch ($size) {
        case "O": $dir = O_DIR; break;
        case "0": $dir = S0_DIR; break;
        case "1": $dir = S1_DIR; break;
        case "2": $dir = S2_DIR; break;
        case "3": $dir = S3_DIR; break;
      }
    }

    $photo = $this->getPhoto($id);
    $this->redirect(substr($dir, strlen(ROOT.DS.APP_DIR.DS.WEBROOT_DIR)).DS.$photo['Photo']['id'].'.jpg', 303);
  }

  function view($id=null) {
    if (!$id) {
      if (isset($this->params['id'])) {
        $id = $this->params['id'];
      }
    }
    $photo = $this->getPhoto($id);
    $related = $this->Photo->find('all', array('limit'=>10)); //TODO find real related
    $this->set(compact('id', 'photo', 'related'));
    $this->set('pageClass', 'photos view');
  }

  function add() {
    $this->pageTitle = 'Photo Upload';
    $file = $this->data;
    $photo = $file['Photo']['photo'];
    if ($photo) {
      $userId = $this->Auth->user('id');
      $this->ensureUploadDirs();
      $results = $this->saveUploadedFilesForUser($userId, $file['Photo']);
      $this->set(compact('results'));
    }
    $this->set('pageClass', 'photos add');
  }

  function edit($id) {
    $this->set('pageClass', 'photos edit');
  }

  function comment() {
    $pid = $_REQUEST['photo_id'];
    $comment = $_REQUEST['comment'];
    $this->Comment->save(array(
      'photo_id'=>$pid,
      'datetime'=>date('Y-m-d H:i:s', time()),
      'comment'=>$comment,
      'user_id'=>$this->Auth->user('id')));
    $this->redirect($this->referer());
  }

  function json($ids=null) {
    if (!$ids) { $ids = $this->params['id']; }
    if (!is_array($ids)) { $ids = explode(',', $ids); }
    $photos = array();
    foreach ($ids as $id) {
      $photo = $this->getPhoto($id);
      if ($photo) {
        $photos[] = $photo;
      }
    }
    $this->set(compact('photos'));
    $this->layout = false;
    $this->render('/elements/json');
  }

  function recent($num=10) {
    function getid($p) {return $p['Photo']['id'];};
    $related = array_map("getid", $this->Photo->find('all', array('fields'=>'Photo.id', 'order'=>'Photo.datetime DESC', 'limit'=>$num)));
    $this->json($related);
  }

  private function getPhoto($id) {
    $P_INFO = array('Photo.id','Photo.caption','Photo.datetime','Photo.location',
                    'Photo.lat','Photo.lng','User.name','User.location','User.id');
    $result = $this->Photo->find('first', array('fields'=>$P_INFO, 'conditions'=>array('Photo.id'=>$id)));

    $result['Photo']['location'] = implode(' ', $result['Photo']['location']);
    foreach ($result['Comment'] as &$comment) {
      $row = mysql_query('SELECT users.name FROM users WHERE users.id = '.mysql_real_escape_string($comment['user_id']));
      $comment['User']['name'] = mysql_result($row, 0);
      mysql_free_result($row);
    }
    return $result;
  }

  private function mime_ok($permitted, $type) {
    return in_array($type, $permitted);
  }

  private function ensureUploadDirs() {
    $dirs = array(UPLOAD_DIR, O_DIR, S0_DIR, S1_DIR, S2_DIR, S3_DIR);
    foreach ($dirs as $dir) {
      if (!is_dir($dir)) {
        $this->log('Recreating missing upload directory: '.$dir);
        mkdir($dir);
      }
    }
  }

  private function saveOPhoto($tmppath, $name) {
    $path = O_DIR.$name;
    if (file_exists($path)) {
      $this->log('FILE COLLISION: '.$name);
      return false;
    }
    return move_uploaded_file($tmppath, $path);
  }

  private function bound($img, $maxx, $maxy) {
    $imgx = imagesx($img);
    $imgy = imagesy($img);
    $newx = $imgx;
    $newy = $imgy;
    if ($newx > $maxx) {
      $newx = $maxx;
      $newy = $maxx/$imgx * $imgy;
    }
    if ($newy > $maxy) {
      $newy = $maxy;
      $newx = $maxy/$imgy * $imgx;
    }
    return $this->resize($img, $newx, $newy);
  }

  private function resize($img, $width, $height) {
    $newimg = imagecreatetruecolor($width, $height);
    imagecopyresampled($newimg, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
    return $newimg;
  }

  private function savePhoto($tmppath, $name) {
    if ($this->saveOPhoto($tmppath, $name)) {
      $dirs = array(
        S0_DIR => array(30, 30),
        S1_DIR => array(50, 50),
        S2_DIR => array(150, 150),
        S3_DIR => array(400, 450));
      foreach ($dirs as $dir => $size) {
        if (!imagejpeg($this->bound(imagecreatefromjpeg(O_DIR.$name), $size[0], $size[1]), $dir.$name, 100)) {
          /* if failed, remove all previously saved photos */
          foreach ($dirs as $olddir => $size) {
            if ($olddir != $dir) {
              unlink($olddir.$name);
            } else {
              unlink(O_DIR.$name);
              return false;
            }
          }
        }
      }
      return true;
    }
    return false;
  }

  private function saveUploadedFilesForUser($userId, $formdata) {
    $results = array(
      'ids'=>array(),
      'errors'=>array()
    );
    $this->log($this->Photo->schema());
    $permittedMIMEs = array('image/jpeg');
    foreach ($formdata as $key=>&$file) {
      if (strcmp($key, 'photo') != 0) {continue;}
      if ($this->mime_ok($permittedMIMEs, $file['type'])) {
        switch ($file['error']) {
          case 0:
            $photo_attribs = array(
              'user_id'=>$userId,
              'file'=>$file['tmp_name'],
              'caption'=>$formdata['caption'],
              'lat'=>$formdata['lat'],
              'lng'=>$formdata['lng'],
              'datetime'=>date('Y-m-d H:i:s', time()),
            );
            if ($this->Photo->save($photo_attribs)) {
              $filename = $this->Photo->id.'.jpg';
              if ($this->savePhoto($file['tmp_name'], $filename)) {
                $results['ids'][] = '<a href="/photos/'.rtrim($filename, '.jpg').'"><img src="/photos/'.rtrim($filename, '.jpg').'" /></a>';
              } else {
                $this->Photo->delete($this->Photo->id);
                $results['errors'][] = 'Unable to save photo. It was probably corrupt.';
              }
            }
            break;
          case 3: $results['errors'][] = 'There was an error uploading the file.'; break;
          case 4: $results['errors'][] = 'There was no file selected.'; break;
          default: $results['errors'][] = 'Unknown case on upload.'; break;
        }
      } else {
        $results['errors'][] = 'You may only upload image (jpg) files.';
      }
    }
    return $results;
  }
}
?>
