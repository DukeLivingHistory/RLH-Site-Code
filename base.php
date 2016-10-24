<?php get_template_part( 'templates/head' ); ?>
  <body <?= body_attr(); ?>>
  <a href="#main" class="sr-only">Skip to main content</a>
  <div class="body-wrap">
    <?= icon_defs(); ?>
    <?php get_template_part( 'templates/header' ); ?>
    <main class="main" id="main" tabindex="-1">
    <?php include template_path(); ?>
    </main>
    <?php get_template_part( 'templates/footer' ); ?>
  </div>
  </body>
</html>
