<?php

/* This function lets us provide a timestamp in either HH:MM:SS.mmm or MM:SS.mmm format and get back the time in seconds. */

function sanitize_timestamp( $timestamp ){
  if( !preg_match( '/\d\d:\d\d:\d\d.\d\d\d/', $timestamp ) ){
    $timestamp = '00:'.$timestamp;
  }
  return strtotime( $timestamp ) - strtotime('today');
}
