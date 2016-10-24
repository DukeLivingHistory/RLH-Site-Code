<?php

include_once( get_template_directory().'/models/ContentNode.php' );
include_once( get_template_directory().'/models/Type.php' );
include_once( get_template_directory().'/models/Taxonomy.php' );

// ====
// register all types
// ====
$types = glob( get_template_directory().'/models/types/*/*.php' );
foreach( $types as $type ) require_once( $type );

// ====
// register all taxonomies
// =====
$taxonomies = glob( get_template_directory().'/models/taxonomies/*/*.php' );
foreach( $taxonomies as $taxonomy ) require_once( $taxonomy );
