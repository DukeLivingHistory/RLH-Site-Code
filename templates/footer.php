<footer class="footer">
  <figure class="footer-logo">
    <a href="<?= site_url(); ?>">
      <?php $altLogo = get_field( 'primary_brand_logo_alt', 'options' ); ?>
      <img src="<?= $altLogo['url']; ?>" alt="<?= $altLogo['alt']; ?>"/>
    </a>
  </figure>
  <nav class="footer-nav">
    <?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => '', 'menu_class' => 'menu menu--footer menu--primary' ] ); ?>
    <?php wp_nav_menu( [ 'theme_location' => 'utility', 'container' => '', 'menu_class' => 'menu menu--footer' ] ); ?>
  </nav>
  <div class="footer-info">
    <?= str_replace( '{{year}}', Date('Y'), get_field( 'address', 'options' ) ); ?>
  </div>
</footer>
<?php if( get_field( 'fb_client_id', 'options' ) ){ ?>
<script>
  window.FB_APP_ID = '<?= get_field( 'fb_client_id', 'options' ); ?>';
  window.MAPS_APP_ID = '<?= get_field( 'maps_client_id', 'options' ); ?>';
  window.COUNT = <?= get_option( 'posts_per_page' ); ?>
</script>
<?php } ?>
<?php wp_footer(); ?>
