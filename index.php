<?php /* Entry point for Project C */
//phpinfo();
require_once('localhost/config/config.php');

// See robap-php-router for routing information
// See smarty for templating information

include_once('localhost/utils/page_error.php'); 
include_once('libraries/robap-php-router/php-router.php');

// TODO Use factory/singleton for Router and Dispatcher?
// Find the Route for your url
$router = new Router();
require_once('localhost/config/routes.php');
$url            = urldecode($_SERVER['REQUEST_URI']);
$found_route    = $router->findRoute($url);

$dispatcher = new Dispatcher();
$dispatcher->setSuffix('Controller');
$dispatcher->setClassPath($_SERVER['DOCUMENT_ROOT'] . '/localhost/controllers/');

// Dispatch the Route
if( FALSE === $found_route ) {
    PageError::show('404', $url, 'route not found');
} else {
    // Note: you would likely use some other http status codes depending
    // on the specific Exception thrown.
    try {
        $dispatcher->dispatch( $found_route );
    } catch ( badClassNameException $e ) {
        PageError::show('400', $url, $e);
    } catch ( classFileNotFoundException $e ) {
        PageError::show('302', $url, $e);
    } catch ( classNameNotFoundException $e ) {
        PageError::show('302', $url, $e);
    } catch ( classMethodNotFoundException $e ) {
        PageError::show('302', $url, $e);
    } catch ( classNotSpecifiedException $e ) {
        PageError::show('302', $url, $e);
    } catch ( methodNotSpecifiedException $e ) {
        PageError::show('302', $url, $e);
    }
}
?>
