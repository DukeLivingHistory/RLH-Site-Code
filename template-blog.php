<?php
/* Template Name: Blog */
?>
<?php
while( have_posts() ){
  the_post();
?>
  <article>
    <header class="contentHeader contentHeader--archive">
      <h2><?php the_title(); ?></h2>
    </header>
    <aside class="researchMenu">
      <button class="researchMenu-toggle">Expand Menu <?= icon( 'down', 'link' ); ?></button>
      <?php wp_nav_menu( [ 'theme_location' => 'blog', 'container' => '', 'menu_class' => 'menu menu--research' ] ); ?>
    </aside>
    <section class="genericContent genericContent--research">
      <?php the_content(); ?>
    </section>
  </article>
<?php
}
