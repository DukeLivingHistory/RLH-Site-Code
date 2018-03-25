<?php
/* Template Name: Blog */
the_post();
$posts_per_page = get_option('posts_per_page');
$paged = get_query_var('paged');
$posts = new WP_Query([
  'post_type' => 'post',
  'posts_per_page' => 1,
  'paged' => $paged
]);
?>
<header class="contentHeader contentHeader--archive">
  <h2><?php the_title(); ?></h2>
</header>
<aside class="researchMenu">
  <button class="researchMenu-toggle">Expand Menu <?= icon( 'down', 'link' ); ?></button>
  <?php dynamic_sidebar('blog'); ?>
</aside>
<section class="genericContent genericContent--research">
  <?php while($posts->have_posts()): $posts->the_post(); ?>
    <article class="blog-single">
      <h3 class="blog-article-head"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <div class="blog-meta">Posted <?php the_date(); ?> by <?php the_author(); ?></div>
      <div class="blog-excerpt"><?php the_excerpt(); ?><a href="<?php the_permalink(); ?>">Read More</a></div>
      <?php if(get_the_category_list()): ?>
      <div class="blog-category">
        Posted in <?= get_the_category_list(); ?>
      </div>
      <?php endif; ?>
      <?php if(get_the_tag_list()): ?>
      <div class="blog-category">
        Tagged <?= get_the_tag_list(); ?>
      </div>
    <?php endif; ?>
    </article>
  <?php endwhile; ?>
  <div class="blog-pagination">
    <?= paginate_links([
      'total' => $posts->max_num_pages
    ]); ?>
  </div>
</section>
