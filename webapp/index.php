<?php
    // See robap-php-router for information

    // Set timezone so apache doesn't complain about relying on server time.
    date_default_timezone_set('America/Los_Angeles');

    //...Stuff before routing occurs

    //Set the include path so that the Routing system files can be included.
    set_include_path(get_include_path() .
      PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/lib/robap-php-router/'.
      PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/lib/smarty/libs/'
    );

    //Include a PageError class which can be used later. You supply this class.
    include('PageError.php'); 
    include('php-router.php');

    //Create a new instance of Router (you'd likely use a factory or container to
    // manage the instance)
    $router = new Router;

    //Create a new instance of Dispatcher (again, you would probably utilize a
    // factory or container)
    $dispatcher = new Dispatcher;
    $dispatcher->setSuffix('Controller');
    $dispatcher->setClassPath($_SERVER['DOCUMENT_ROOT'] . '/src/controllers/');

    require_once('routes.php');

    //Find the Route for your url
    $url            = urldecode($_SERVER['REQUEST_URI']);
    $found_route    = $router->findRoute($url);

    //Dispatch the Route
    if( FALSE === $found_route )
    {
      PageError::show('404', $url, 'route not found');
    }
    else
    {
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
