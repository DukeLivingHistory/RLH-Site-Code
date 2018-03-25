<?php
/*
 * This file allows us to get parts from our /app/ directory with the correct URL path.
 */

function get_app() {
  $dir = get_stylesheet_directory_uri();
  ?>
  <div class="app-wrapper"></div>
  <script src="<?= $dir; ?>/assets/vendor/js/ableplayer.min.js"></script>
  <script src="<?= $dir; ?>/assets/vendor/js/able-timestamps.min.js"></script>
  <script src="<?= $dir; ?>/assets/vendor/js/able-search.min.js"></script>
  <?php
}
