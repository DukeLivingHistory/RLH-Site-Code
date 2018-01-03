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
<?php if( get_field( 'fb_client_id', 'options' ) ): ?>
<script>
  window.FB_APP_ID    = '<?= get_field( 'fb_client_id', 'options' ); ?>';
</script>
<?php endif; ?>
<?php if( get_field( 'maps_client_id', 'options' ) ): ?>
<script>
  window.MAPS_APP_ID  = '<?= get_field( 'maps_client_id', 'options' ); ?>';
</script>
<?php endif; ?>
<script>
  window.COUNT        =  <?= get_option( 'posts_per_page' ); ?>;
  window.INSTRUCTIONS = '<?= str_replace("\n", "", get_field( 'interview_instructions', 'options' )); ?>';
  window.HEADINGOPTS  = {
    <?php
      $h_color   = get_field('heading_color',   'options');
      $h_width   = get_field('heading_width',   'options');
      $h_height  = get_field('heading_height',  'options');
      $h_display = get_field('heading_display', 'options');
    ?>
    COLOR:    <?= $h_color   ? "'$h_color'"   : 'false'; ?>,
    WIDTH:    <?= $h_width   ? "'$h_width'"   : 'false'; ?>,
    HEIGHT:   <?= $h_height  ? "'$h_height'"  : 'false'; ?>,
    DISPLAY:  <?= $h_display ? "'$h_display'" : 'false'; ?>
  };
  window.CHAPTEROPTS  = {
    <?php
      $c_color   = get_field('chapter_color',   'options');
      $c_width   = get_field('chapter_width',   'options');
      $c_height  = get_field('chapter_height',  'options');
      $c_display = get_field('chapter_display', 'options');
    ?>
    COLOR:    <?= $c_color   ? "'$c_color'"   : 'false'; ?>,
    WIDTH:    <?= $c_width   ? "'$c_width'"   : 'false'; ?>,
    HEIGHT:   <?= $c_height  ? "'$c_height'"  : 'false'; ?>,
    DISPLAY:  <?= $c_display ? "'$c_display'" : 'false'; ?>
  };
  window.SEARCHOPTS  = {
    <?php
      $s_color   = get_field('search_color',   'options');
      $s_width   = get_field('search_width',   'options');
      $s_height  = get_field('search_height',  'options');
      $s_display = get_field('search_display', 'options');
    ?>
    COLOR:    <?= $s_color   ? "'$s_color'"   : 'false'; ?>,
    WIDTH:    <?= $s_width   ? "'$s_width'"   : 'false'; ?>,
    HEIGHT:   <?= $s_height  ? "'$s_height'"  : 'false'; ?>,
    DISPLAY:  <?= $s_display ? "'$s_display'" : 'false'; ?>
  };
  window.AUDIOOPTS  = {
    <?php
      $a_color   = get_field('audio_color',   'options');
      $a_width   = get_field('audio_width',   'options');
      $a_height  = get_field('audio_height',  'options');
      $a_display = get_field('audio_display', 'options');
    ?>
    COLOR:    <?= $a_color   ? "'$a_color'"   : 'false'; ?>,
    WIDTH:    <?= $a_width   ? "'$a_width'"   : 'false'; ?>,
    HEIGHT:   <?= $a_height  ? "'$a_height'"  : 'false'; ?>,
    DISPLAY:  <?= $a_display ? "'$a_display'" : 'false'; ?>
  };
  window.SUPP_CONT_OPTS  = {
    <?php
      $sc_color   = get_field('supp_cont_color',   'options');
      $sc_width   = get_field('supp_cont_width',   'options');
      $sc_height  = get_field('supp_cont_height',  'options');
      $sc_display = get_field('supp_cont_display', 'options');
    ?>
    COLOR:    <?= $sc_color   ? "'$sc_color'"   : 'false'; ?>,
    WIDTH:    <?= $sc_width   ? "'$sc_width'"   : 'false'; ?>,
    HEIGHT:   <?= $sc_height  ? "'$sc_height'"  : 'false'; ?>,
    DISPLAY:  <?= $sc_display ? "'$sc_display'" : 'false'; ?>
  };
</script>
<?php } ?>
<?php if($s_highlight = get_field('search_highlight_color', 'options')){ ?>
  <style>
  .transcript-highlight {
    background: <?= $s_highlight; ?>;
  }
  </style>
<?php } ?>
<?php if($highlight = get_field('highlight_color', 'options')){ ?>
  <style>
  .transcript ::selection {
    background: <?= $highlight; ?>
  }
  </style>
<?php } ?>
<?php wp_footer(); ?>
