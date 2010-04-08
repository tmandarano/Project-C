<?php
require_once('config.php');
require_once('src/utils/restutils.php');
require_once('Smarty.class.php');

/* BaseController extends Smarty to get the Smarty templating */
abstract class BaseController extends Smarty {
  function __construct() {
    $this->Smarty();
    $this->template_dir = 'src/views/';
    $this->compile_dir = 'smarty_pants/compiled';
    $this->config_dir = 'smarty_pants/config';
    $this->cache_dir = 'smarty_pants/cache';
    $this->plugins_dir[] = 'smarty_pants/plugins';
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
