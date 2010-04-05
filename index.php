<?php /* Entry point for Project C */
//phpinfo();
require_once('config.php');

// See robap-php-router for routing information
// See smarty for templating information

include_once('PageError.php'); 
include_once('php-router.php');

// TODO Use factory/singleton for Router and Dispatcher?
// Find the Route for your url
$router = new Router();
require_once('routes.php');
$url            = urldecode($_SERVER['REQUEST_URI']);
$found_route    = $router->findRoute($url);

$dispatcher = new Dispatcher();
$dispatcher->setSuffix('Controller');
$dispatcher->setClassPath($_SERVER['DOCUMENT_ROOT'] . '/src/controllers/');

// Dispatch the Route
if( FALSE === $found_route ) {
  PageError::show('404', $url, 'route not found');
} else {
  // Note: you would likely use some other http status codes depending
  // on the specific Exception thrown.
  try {
    $dispatcher->dispatch( $found_route );
  } catch ( badClassNameException $e ) {
    PageError::show('404', $url, $e);
  } catch ( classFileNotFoundException $e ) {
    PageError::show('404', $url, $e);
  } catch ( classNameNotFoundException $e ) {
    PageError::show('404', $url, $e);
  } catch ( classMethodNotFoundException $e ) {
    PageError::show('404', $url, $e);
  } catch ( classNotSpecifiedException $e ) {
    PageError::show('404', $url, $e);
  } catch ( methodNotSpecifiedException $e ) {
    PageError::show('404', $url, $e);
  }
}
?>
