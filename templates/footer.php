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
  window.FB_APP_ID    = '<?= get_field( 'fb_client_id', 'options' ); ?>';
  window.MAPS_APP_ID  = '<?= get_field( 'maps_client_id', 'options' ); ?>';
  window.COUNT        =  <?= get_option( 'posts_per_page' ); ?>;
  window.INSTRUCTIONS = '<?= str_replace("\n", "", get_field( 'interview_instructions', 'options' )); ?>';
  window.HEADINGOPTS  = {
    <?php
      $h_color   = get_field('heading_color',   'options');
      $h_width   = get_field('heading_width',   'options');
      $h_display = get_field('heading_display',   'options');
    ?>
    COLOR:    <?= $h_color   ? "'$h_color'"   : 'false'; ?>,
    WIDTH:    <?= $h_width   ? "'$h_width'"   : 'false'; ?>,
    DISPLAY:  <?= $h_display ? "'$h_display'" : 'false'; ?>
  };
  window.CHAPTEROPTS  = {
    <?php
      $c_color   = get_field('chapter_color',   'options');
      $c_width   = get_field('chapter_width',   'options');
      $c_display = get_field('chapter_display',   'options');
    ?>
    COLOR:    <?= $c_color   ? "'$c_color'"   : 'false'; ?>,
    WIDTH:    <?= $c_width   ? "'$c_width'"   : 'false'; ?>,
    DISPLAY:  <?= $c_display ? "'$c_display'" : 'false'; ?>
  };
</script>
<?php } ?>
<?php wp_footer(); ?>
