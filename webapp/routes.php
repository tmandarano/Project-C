<?php
/* Routes for the app */

  function staticRoute($router, $route, $class, $method) {
    $r = new Route($route);
    $r->setMapClass($class);
    $r->setMapMethod($method);
    $router->addRoute($method, $r);
  }

  function dynMethodRoute($router, $class) {
    $r = new Route('/'.$class.'/:method');
    $r->setMapClass($class);
    $r->addDynamicElement(':method', ':method');
    $router->addRoute($class, $r);
  }

  staticRoute($router, '/', 'viscous', 'home');
  staticRoute($router, '/signup', 'viscous', 'signup');
  staticRoute($router, '/settings', 'viscous', 'settings');

  $users_profile_route = new Route('/users/profile/:number');
  $users_profile_route->setMapClass('users');
  $users_profile_route->setMapMethod('profile');
  $users_profile_route->addDynamicElement(':number', '^\d+$');
  $router->addRoute('users_profile', $users_profile_route);

  staticRoute($router, '/about/contact', 'viscous', 'about_contact');
  staticRoute($router, '/about/faq', 'viscous', 'about_faq');

  staticRoute($router, '/explore/map', 'viscous', 'explore_map');
  staticRoute($router, '/explore/photos', 'viscous', 'explore_photos');
  staticRoute($router, '/explore/people', 'viscous', 'explore_people');

  staticRoute($router, '/share', 'viscous', 'share_index');
  staticRoute($router, '/share/upload', 'viscous', 'share_upload');
  staticRoute($router, '/share/mobile', 'viscous', 'share_mobile');
  staticRoute($router, '/share/webcam', 'viscous', 'share_webcam');

  $photos_view_route = new Route('/photos/view/:number');
  $photos_view_route->setMapClass('viscous');
  $photos_view_route->setMapMethod('photos_view');
  $photos_view_route->addDynamicElement(':number', '^\d+$');
  $router->addRoute('photos_view', $photos_view_route);

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

  dynMethodRoute($router, 'users');

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
