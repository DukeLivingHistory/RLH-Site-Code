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
      <?php dynamic_sidebar('blog'); ?>
    </aside>
    <section class="blog-content">
      <h3 class="blog-article-head"><?php the_title(); ?></h3>
      <div class="blog-meta">Posted <?php the_date(); ?> by <?php the_author(); ?></div>
      <?php the_content(); ?>
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
    </section>
  </article>
<?php
}
