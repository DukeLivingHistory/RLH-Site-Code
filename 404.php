<?php
// WP doesn't support an achive page for taxonomies, so we catch it here. '/collections'
// is a reserved key word and should NOT be used as a slug.
$request = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : false;
$is_collection_archive = $request === '/collections' ||  $request === '/collections/';
if( $is_collection_archive ){
  get_app();
} else { ?>
  <div class="notfound">
    <header class="contentHeader contentHeader--archive">
      <h2>404: Page Not Found</h2>
    </header>
    <form method="get" action="<?php bloginfo('url'); ?>/">
      <input name="s" type="text" placeholder="Search">
      <?= icon( 'search' ); ?>
    </form>
  </div>
<?php }
