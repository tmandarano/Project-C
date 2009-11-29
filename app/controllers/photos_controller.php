<?php
define('UPLOAD_DIR', ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.'img'.DS.'db'.DS);

class PhotosController extends AppController {
  var $name = 'Photos';
  var $uses = 'Photo';

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'view');
  }

  /* Linked to by photos/:id */
  function index($id=null) {
    if (!$id) {
      $id = $this->params['id'];
    }
    $photo = $this->Photo->find('first', array('conditions'=>array('Photo.id'=>$id)));
    $this->log($photo);
    $photo = $photo['Photo'];

    /* Send the photo */
    $this->view = 'Media';
    $params = array(
      'id'=>$photo['id'].'.jpg',
      'name'=>$photo['caption'],
      'download'=>false,
      'extension'=>'jpg',
      'path'=>UPLOAD_DIR,
      'mimeType'=>'image/jpeg',
      'cache'=>60*60*24*7
    );
    $this->set($params);
  }

  function view($id=null) {
    if (!$id) {
      if (isset($this->params['id'])) {
        $id = $this->params['id'];
      }
    }
    $photo = $this->Photo->find('first', array('conditions'=>array('Photo.id'=>$id)));
    $this->log($photo);
    $related = $this->Photo->find('all', array('limit'=>10));
    $this->set(compact('id', 'photo', 'related'));
  }

  function json($id=null) {
    if (!$id) {
      $id = $this->params['id'];
    }
    $photo = $this->Photo->find('first', array('conditions'=>array('Photo.id'=>$id)));
    unset($photo['User']['id']);
    unset($photo['User']['password']);
    $this->set(compact('photo'));
    $this->layout = false;
    $this->render('/elements/json');
  }

  function add() {
    $this->pageTitle = 'Photo Upload';
    $file = $this->data;
    $photo = $this->data['Photo']['photo'];
    if ($photo) {
      $userId = $this->Auth->user('id');
      $this->ensureUploadDir();
      $results = $this->saveUploadedFilesForUser($userId, $this->data['Photo']);

      // TODO do some checking that this is really a point.
      $this->set(compact('results'));
    }
  }

  private function mime_ok($permitted, $type) {
    foreach ($permitted as $mime) {
      if ($mime == $type) {
        return true;
      }
    }
    return false;
  }

  private function ensureUploadDir() {
    if (!is_dir(UPLOAD_DIR)) {
      $this->log('Recreating missing upload directory: '.UPLOAD_DIR);
      mkdir(UPLOAD_DIR);
    }
  }

  private function saveUploadedFilesForUser($userId, $formdata) {
    $results = array(
      'ids'=>array(),
      'errors'=>array()
    );
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
              $fileurl = UPLOAD_DIR.$filename;
              if (file_exists($fileurl)) {
                $this->log('File exists that should not: '.$filename);
                $results['errors'][] = 'File collision';
              } else {
                /* Try to save the file permanently */
                if (move_uploaded_file($file['tmp_name'], $fileurl)) {
                  $results['ids'][] = $filename;
                } else {
                  $results['errors'][] = 'Unable to write file';
                  //TODO remove the photo record
                }
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
