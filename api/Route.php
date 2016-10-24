<?php

class Route {
  function __construct( $route, $method, $callback ){
    $this->route = $route;
    $this->method = $method;
    $this->callback = $callback;
    add_action( 'rest_api_init', function () {
      register_rest_route( 'v1', $this->route, [
          'methods' => $this->method,
          'callback' => $this->callback
      ] );
    } );
  }
}
