<?php
class ShareController extends AppController {
  var $name = 'Share';
  var $uses = 'Photo';

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'mobile', 'webcam');
  }

  function index() {}

  function upload() {
    $this->pageTitle = 'Upload | Share ';
    $file = $this->data;
    $photo = $this->data['Photo']['photo'];
    if ($photo) {
      $userId = $this->Auth->user('id');
      $this->ensureUploadDirs();
      $results = $this->saveUploadedFilesForUser($userId, $this->data['Photo']);
      $this->set(compact('results'));
    }
  }

  function mobile() {
    $this->pageTitle = 'Mobile | Share ';
  }

  function webcam() {
    $this->pageTitle = 'Webcam | Share ';
  }

  /* Private */

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
              'location'=>array($formdata['lng'], $formdata['lat']),
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
