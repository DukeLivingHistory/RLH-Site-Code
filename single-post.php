<?php
global $post;
require_once(get_template_directory() . '/api/routes/search/search-helpers.php');
while( have_posts() ){
  the_post();
?>
  <article>
    <header class="contentHeader contentHeader--archive">
      <h2><?php the_title(); ?></h2>
    </header>
    <aside class="researchMenu">
      <button class="researchMenu-toggle">
        Expand Menu <?= icon( 'down', 'link' ); ?>
      </button>
      <?php if(!get_field('hide_sidebar')): ?>
        <form class="researchMenu-search" method="get" action="<?php bloginfo('url'); ?>/">
          <input name="s" type="text" placeholder="Search blog">
          <input name="type" value="blog" type="hidden">
          <button type="submit"><?= icon( 'search' ); ?></button>
        </form>
        <?php dynamic_sidebar('posts'); ?>
      <?php endif; ?>
    </aside>
    <section class="blog-content">
      <h3 class="blog-article-head">
        <?php $title = highlight_term( $post->post_title, $_GET['search'] ); ?>
        <?= apply_filters('the_title', $title); ?>
      </h3>
      <div class="blog-meta">Posted <?php the_date(); ?> by <?php the_author(); ?></div>
      <?php $content = highlight_term( $post->post_content, $_GET['search'] ); ?>
      <?= apply_filters('the_content', $content); ?>
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
    <?php if($author = get_post_field('post_author')): ?>
      <div class="author">
        <a href="<?= get_author_posts_url($author); ?>" class="author-link">
        <?php if($avatar = get_avatar($author)): ?>
        <div class="author-thumbnail">
          <?= $avatar; ?>
        </div>
        <?php endif; ?>
        <div class="author-bio">
          <strong><?= get_author_name($author); ?></strong>
          <p><?= get_the_author_meta('user_description', $author); ?></p>
        </div>
        </a>
      </div>
    <?php endif; ?>
    </section>
  </article>
<?php
}
