<?php
/* Routes for the app */

  function staticRoute($router, $route, $class, $method, $name) {
    $r = new Route($route);
    $r->setMapClass($class);
    $r->setMapMethod($method);
    $router->addRoute($name, $r);
  }

  function dynMethodRoute($router, $class) {
    $r = new Route('/'.$class.'/:method');
    $r->setMapClass($class);
    $r->addDynamicElement(':method', ':method');
    $router->addRoute($class, $r);
  }

  staticRoute($router, '/', 'viscous', 'home', 'home');

  dynMethodRoute($router, 'users');
  dynMethodRoute($router, 'explore');
  dynMethodRoute($router, 'share');

  staticRoute($router, '/about/contact', 'viscous', 'about_contact', 'about_contact');
  staticRoute($router, '/about/faq', 'viscous', 'about_faq', 'about_faq');

  $photos_recent_route = new Route('/photos/recent/:number');
  $photos_recent_route->setMapClass('photos');
  $photos_recent_route->setMapMethod('recent');
  $photos_recent_route->addDynamicElement(':number', '^\d+$');
  $router->addRoute('photos_recent', $photos_recent_route);

  $photos_show_route = new Route('/photos/show/:photo_id');
  $photos_show_route->setMapClass('photos');
  $photos_show_route->setMapMethod('show');
  $photos_show_route->addDynamicElement(':photo_id', '^\d{5}$');
  $router->addRoute('photos_show', $photos_show_route);

  // Set up a 'catch all' default route and add it to the Router.
  $default_route = new Route('/:class/:method/:id');
  $default_route->addDynamicElement(':class', ':class');
  $default_route->addDynamicElement(':method', ':method');
  $default_route->addDynamicElement(':id', ':id');
  $router->addRoute('default', $default_route);

  $method_only_route = new Route('/:class/:method');
  $method_only_route->addDynamicElement(':class', ':class');
  $method_only_route->addDynamicElement(':method', ':method');
  $router->addRoute('method_only', $method_only_route);
?>
