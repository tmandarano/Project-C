<?php
/* Routes for the app */

  function staticRoute($router, $route, $class, $method) {
    $r = new Route($route);
    $r->setMapClass($class);
    $r->setMapMethod($method);
    $router->addRoute($method, $r);
  }

  function singleArgRoute($router, $route, $arg, $class, $method, $argRegex, $routename) {
    $r = new Route($route.$arg);
    $r->setMapClass($class);
    $r->setMapMethod($method);
    $r->addDynamicElement($arg, $argRegex);
    $router->addRoute($routename, $r);
  }

  function dynMethodRoute($router, $class) {
    $r = new Route('/'.$class.'/:method');
    $r->setMapClass($class);
    $r->addDynamicElement(':method', ':method');
    $router->addRoute($class, $r);
  }

  function dynMethodArgRoute($router, $class, $arg, $argRegex) {
    $r = new Route('/'.$class.'/:method/'.$arg);
    $r->setMapClass($class);
    $r->addDynamicElement(':method', ':method');
    $r->addDynamicElement($arg, $argRegex);
    $router->addRoute($class, $r);
  }

  staticRoute($router, '/', 'viscous', 'home');

  /* users routes */
  staticRoute($router, '/signup', 'viscous', 'signup');
  staticRoute($router, '/settings', 'viscous', 'settings');
  singleArgRoute($router, '/profile/', ':number', 'viscous', 'profile', '^\d+$', 'profile');
  dynMethodRoute($router, 'users');
  dynMethodArgRoute($router, 'users', ':number', '^\d+$');

  /* about routes */
  staticRoute($router, '/about/contact', 'viscous', 'about_contact');
  staticRoute($router, '/about/faq', 'viscous', 'about_faq');

  /* explore routes */
  staticRoute($router, '/explore/map', 'viscous', 'explore_map');
  staticRoute($router, '/explore/photos', 'viscous', 'explore_photos');
  staticRoute($router, '/explore/people', 'viscous', 'explore_people');

  /* share routes */
  staticRoute($router, '/share', 'viscous', 'share_index');
  staticRoute($router, '/share/upload', 'viscous', 'share_upload');
  staticRoute($router, '/share/mobile', 'viscous', 'share_mobile');
  staticRoute($router, '/share/webcam', 'viscous', 'share_webcam');

  /* photos routes */
  singleArgRoute($router, '/photos/view/', ':number', 'viscous', 'photos_view', '^\d+$', 'photos_view');
  singleArgRoute($router, '/photos/recent/', ':number', 'photos', 'recent', '^\d+$', 'photos_recent');
  singleArgRoute($router, '/photos/show/', ':photo_id', 'photos', 'show', '^\d{5}$', 'photos_show');

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
