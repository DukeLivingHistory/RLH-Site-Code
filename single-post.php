<?php
global $post;
require_once(get_template_directory() . '/api/routes/search/search-helpers.php');
while( have_posts() ){
  the_post();
?>
  <article class="blog-singlePost">
    <header class="contentHeader contentHeader--archive">
      <?php $title = highlight_term( $post->post_title, $_GET['search'] ); ?>
      <h2><?= apply_filters('the_title', $title); ?></h2>
      <div class="blog-meta">Posted <?php the_date(); ?></div>
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
    <?php
      $authors = get_field('authors');
      if(have_rows('authors')):
        while(have_rows('authors')):
          the_row();
    ?>
          <div class="author">
            <div class="author-thumbnail">
              <img src="<?= the_sub_field('avatar'); ?>" />
            </div>
            <div class="author-bio">
              <strong><?= the_sub_field('name'); ?></strong>
              <p><?= the_sub_field('bio'); ?></p>
            </div>
          </div>
    <?php
        endwhile;
      endif;
    ?>
    </section>
  </article>
<?php
}
