<?php
class PhotosController extends AppController {
  var $name = 'Photos';
  var $uses = 'Photo';

  /* View */

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'view');
  }

  function index($id=null) { /* Linked to by photos/:id */
    if (!$id) {
      $id = $this->params['id'];
    }
    $photo = $this->Photo->find('first', array('conditions'=>array('Photo.id'=>$id)));
    $photo = $photo['Photo'];
    $upload_dir = DS.'webroot'.DS.'img'.DS.'db'.DS;

    /* Send the photo */
    $this->view = 'Media';
    $params = array(
      'id'=>$photo['id'].'.jpg',
      'name'=>$photo['caption'],
      'download'=>false,
      'extension'=>'jpg',
      'path'=>$upload_dir,
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
    /* TODO find related pics */
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

  private function ensureUploadDir() {
    $upload_dir = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.'img'.DS.'db'.DS;
    if (!is_dir($upload_dir)) {
      log('Recreating missing upload directory: '.$upload_dir);
      mkdir($upload_dir);
    }
  }

  private function saveUploadedFilesForUser($userId, $formdata) {
    $permittedMIMEs = array('image/jpeg');
    $results = array();
    $results['ids'] = array();
    $results['errors'] = array();
    foreach ($formdata as &$file) {
      $mimeOK = false;
      foreach ($permittedMIMEs as $mime) {
        if ($mime == $file['type']) {
          $mimeOK = true;
          break;
        }
      }
      if ($mimeOK) {
        $upload_dir = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.'img'.DS.'db'.DS;
        switch ($file['error']) {
          case 0:
            $photo_attribs = array(
              'user_id'=>$userId,
              'file'=>$file['tmp_name'],
              'caption'=>$formdata['caption'],
              //'location'=>'PointFromText("'.$formdata['location'].'")',
              'datetime'=>date('Y-m-d H:i:s', time()),
            );
            if ($this->Photo->save($photo_attribs)) {
              $filename = $this->Photo->id.'.jpg';
              $fileurl = $upload_dir.$filename;
              if (file_exists($fileurl)) {
                log('Error: file exists that should not: '.$filename);
                $results['errors'][] = 'Error! File collision';
                break;
              } else {
                /* Try to save the file permanently */
                if (move_uploaded_file($file['tmp_name'], $fileurl)) {
                  $results['ids'][] = $filename;
                } else {
                  $results['errors'][] = 'Error uploading';
                  //TODO remove the photo record
                }
              }
            }
            break;
          case 3: $results['errors'][] = 'Error uploading'; break;
          case 4: $results['errors'][] = 'No file selected'; break;
          default: $results['errors'][] = 'Big ERROR!'; break;
        }
      } else {
        $results['errors'][] = 'You may only upload image (jpg) files.';
      }
    }
    return $results;
  }

}
?>
