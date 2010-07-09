<?php
require_once('lib/smarty/libs/Smarty.class.php');

/* Template extends Smarty to get the Smarty templating with special 
 * settings. */
class Template extends Smarty {
    function __construct() {
        $this->Smarty();
        $this->template_dir = 'src/views/';
        $this->compile_dir = 'special/smarty_pants/compiled';
        $this->config_dir = 'special/smarty_pants/config';
        $this->cache_dir = 'special/smarty_pants/cache';
        $this->plugins_dir[] = 'special/smarty_pants/plugins';
    }

    function fetch($template) {
        $this->assign('content', $template);
        return parent::fetch('base.tpl');
    }

    function untemplated_fetch($template) {
        return parent::fetch($template);
    }
}
?>
