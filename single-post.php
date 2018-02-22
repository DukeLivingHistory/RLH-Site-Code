<?php
while( have_posts() ){
  the_post();
?>
  <article>
    <header class="contentHeader contentHeader--archive">
      <h2><?php the_title(); ?></h2>
    </header>
    <section class="genericContent">
      <?php the_content(); ?>
    </section>
  </article>
<?php
}
