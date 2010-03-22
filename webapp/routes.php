<?php
/* Routes for the app */

  $home_route = new Route('/');
  $home_route->setMapClass('viscous');
  $home_route->setMapMethod('home');
  $router->addRoute('home', $home_route );

  $settings_route = new Route('/settings');
  $settings_route->setMapClass('viscous');
  $settings_route->setMapMethod('settings');
  $router->addRoute('settings', $settings_route );

  $explore_route = new Route('/explore/:method');
  $explore_route->setMapClass('explore');
  $explore_route->addDynamicElement(':method', ':method');
  $router->addRoute('explore', $explore_route );

  $share_route = new Route('/share/:method');
  $share_route->setMapClass('share');
  $share_route->addDynamicElement(':method', ':method');
  $router->addRoute('share', $share_route );

  $about_route = new Route('/about/:method');
  $about_route->setMapClass('about');
  $about_route->addDynamicElement(':method', ':method');
  $router->addRoute('about', $about_route );

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
