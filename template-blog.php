<?php
/* Template Name: Blog */
the_post();
$posts = new WP_Query([
  'post_type' => 'post'
]);

?>
<article>
  <header class="contentHeader contentHeader--archive">
    <h2><?php the_title(); ?></h2>
  </header>
  <aside class="researchMenu">
    <button class="researchMenu-toggle">Expand Menu <?= icon( 'down', 'link' ); ?></button>
    <?php wp_nav_menu([
      'theme_location' => 'blog',
      'container' => '',
      'menu_class' => 'menu menu--research'
    ]); ?>
  </aside>
  <section class="genericContent genericContent--research">
    <?php while($posts->have_posts()): $posts->the_post(); ?>
      <article class="blog-single">
        <h3 class="blog-article-head"><?php the_title(); ?></h3>
        <div class="blog-meta">Posted <?php the_date(); ?> by <?php the_author(); ?></div>
        <div class="blog-excerpt"><?php the_excerpt(); ?><a href="<?php the_permalink(); ?>">Read More</a></div>
      </article>
    <?php endwhile; ?>
  </section>
</article>
