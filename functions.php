<?php

$includes = [
  'api/api.php',
  'models/models.php',
  'models/site.php',
  'lib/acf.php',
  'lib/admin_css.php',
  'lib/alert_unavailable_timestamp.php',
  'lib/assets.php',
  'lib/body_attr.php',
  'lib/connections.php',
  'lib/get_app_part.php',
  'lib/get_og.php',
  'lib/get_supp_cont_fields.php',
  'lib/hack-supp-cont-save.php',
  'lib/icon.php',
  'lib/images.php',
  'lib/manage_raw_supp.php',
  'lib/manage_raw_transcript.php',
  'lib/photo_credits.php',
  'lib/repeater-search.php',
  'lib/rich-text-format.php',
  'lib/sanitize_timestamp.php',
  'lib/save_txt_from_vtt.php',
  'lib/setup.php',
  'lib/site_options.php',
  'lib/supp_vtt.php',
  'lib/sync_supp.php',
  'lib/wrapper.php'
];

foreach( $includes as $include ){
  if ( !$filepath = locate_template($include) ) {
    trigger_error( sprintf(__('Error locating %s for inclusion' ), $include ), E_USER_ERROR);
  }
  require_once $filepath;
}
unset( $include, $filepath );

// allow svg
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

// prevent smart quote issue
remove_filter('the_title', 'wptexturize');

function my_acf_init() {
	acf_update_setting('google_api_key', get_field('maps_client_id', 'options'));
}
add_action('acf/init', 'my_acf_init');

function add_interactives_to_author_archive($query) {
  if(
    is_author() &&
    $query->is_main_query()
  ) {
    $query->set('post_type', ['post', 'interactive']);
  }
}
add_action('pre_get_posts','add_interactives_to_author_archive');
