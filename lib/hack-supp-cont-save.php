<?php

add_action('save_post', function() {
  $supp_rows = $_POST['acf']['sc_row'];
  if($supp_rows) foreach($supp_rows as $i => $supp_row) {
    update_post_meta($_POST['post_ID'], 'sc_row_'.$i.'_timestamp', $supp_row['sc_timestamp']);
  }
});
