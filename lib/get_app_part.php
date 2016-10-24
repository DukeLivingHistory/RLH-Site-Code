<?php

/*
 * This file allows us to get parts from our /app/ directory with the correct URL path.
 */

function get_app_part( $path ){
  $app = file_get_contents( get_template_directory().$path );
  $app = str_replace( '/app/', get_template_directory_uri().'/app/app/', $app );
  echo $app;
}
