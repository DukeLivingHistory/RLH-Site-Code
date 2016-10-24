<?php

$includes = [
  'api/api.php',
  'models/models.php',
  'models/site.php',
  'lib/alert_unavailable_timestamp.php',
  'lib/add_confirm_to_delete.php',
  'lib/admin_css.php',
  'lib/assets.php',
  'lib/body_attr.php',
  'lib/connections.php',
  'lib/fetch_transcript.php',
  'lib/get_app_part.php',
  'lib/get_og.php',
  'lib/get_supp_cont_fields.php',
  'lib/icon.php',
  'lib/images.php',
  'lib/manage_raw_transcript.php',
  'lib/photo_credits.php',
  'lib/sanitize_timestamp.php',
  'lib/save_sliced_transcript.php',
  'lib/save_timestamp.php',
  'lib/save_transcript_from_fields.php',
  'lib/save_txt_from_vtt.php',
  'lib/save_vtt_from_fields.php',
  'lib/site_options.php',
  'lib/sync_supp.php',
  'lib/update_transcript_field.php',
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
