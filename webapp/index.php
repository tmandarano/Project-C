<?php
    //...Stuff before routing occurs

    //Set the include path so that the Routing system files can be included.
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/lib/robap-php-router/');

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

    //Set up a 'catch all' default route and add it to the Router.
    //You may want to set up an external file, define your routes there, and
    // and include that file in place of this code block.
    $default_route = new Route('/:class/:method/:id');
    $default_route->addDynamicElement(':class', ':class');
    $default_route->addDynamicElement(':method', ':method');
    $default_route->addDynamicElement(':id', ':id');
    $router->addRoute( 'default', $default_route );

    $method_only_route = new Route('/:class/:method');
    $method_only_route->addDynamicElement(':class', ':class');
    $method_only_route->addDynamicElement(':method', ':method');
    $router->addRoute( 'method_only', $method_only_route );
    
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
    	//Note: you would likely use some other http status codes depending
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