<?php

include_once( get_template_directory().'/api/Route.php' );
$routes = glob( get_template_directory().'/api/routes/*/*.php' );
foreach( $routes as $route ){
  require_once( $route );
}
