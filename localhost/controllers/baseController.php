<?php
require_once('localhost/config/config.php');
require_once('localhost/utils/restutils.php');
require_once('Smarty.class.php');
require_once('localhost/dao/user_dao.php');

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

    public function checkAuth() {
        if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
            header('WWW-Authenticate: Basic realm="example.com"');
            header('HTTP/1.0 401 Unauthorized');
            die();
        } else {
            $users = UserDAO::getUserByStandardAuth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
            if(is_null($users) || count($users) <= 0) {
                RestUtils::sendResponse(401, 'User is unauthorized', 'text/html');
            }
        }
    }
}
?>
