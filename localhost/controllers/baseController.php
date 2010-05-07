<?php
require_once('localhost/config/config.php');
require_once('localhost/utils/restutils.php');
require_once('Smarty.class.php');

/* BaseController extends Smarty to get the Smarty templating */
abstract class BaseController extends Smarty {
  function __construct() {
    $this->Smarty();
    $this->template_dir = 'localhost/views/';
    $this->compile_dir = 'special/smarty_pants/compiled';
    $this->config_dir = 'special/smarty_pants/config';
    $this->cache_dir = 'special/smarty_pants/cache';
    $this->plugins_dir[] = 'special/smarty_pants/plugins';
  }

  function fetch($template) {
    $this->assign('content', $template);
    return parent::fetch('base.tpl');
  }

  function untemplatedFetch($template) {
    return parent::fetch($template);
  }
}
?>
