<?php
/* Template Name: Blog */
$meta = [
  'relation' => 'OR',
  [
    'key' => 'show_in_blog',
    'value' => 1,
    'compare' => '='
  ],
  [
    'key' => 'show_in_blog',
    'compare' => 'NOT EXISTS'
  ],
];

// get featured content
$curated_items = get_field( 'curated_blog_content', 'options' );
if($curated_items) foreach( $curated_items as $curated_item ){
  $in[] = $curated_item[ 'curated_blog' ];
}

$show = get_field('show_roll_blog', 'option');
$total_results = $show ? 12 : 4;
$posts_per_page = get_option('posts_per_page');
$paged = get_query_var('paged');
$diff = $total_results - $posts_per_page;
$offset = ($paged - 1)  * $posts_per_page + $diff;

if($paged) {
  $query = new WP_Query([
    'post_type' => ['post', 'interactive'],
    'offset' => $offset,
    'meta_query' => $meta,
  ]);
  $found_posts = $query->found_posts;
  if(!$show) $found_posts = $found_posts + $posts_per_page - $offset;
  $total = ceil($found_posts / $posts_per_page);
  ?>
  <header class="contentHeader contentHeader--archive">
    <h2><?= get_queried_object()->name; ?></h2>
  </header>
  <aside class="researchMenu">
    <button class="researchMenu-toggle">Expand Menu <?= icon( 'down', 'link' ); ?></button>
    <form class="researchMenu-search" method="get" action="<?php bloginfo('url'); ?>/">
      <input name="s" type="text" placeholder="Search blog">
      <input name="type" value="blog" type="hidden">
      <button type="submit"><?= icon( 'search' ); ?></button>
    </form>
    <?php dynamic_sidebar('blog'); ?>
  </aside>
  <section class="genericContent genericContent--research">
    <?php while($query->have_posts()): $query->the_post(); ?>
      <article class="blog-single">
        <h3 class="blog-article-head"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="blog-meta">Posted <?php the_date(); ?> by <?php the_author(); ?></div>
        <div class="blog-excerpt"><?php the_excerpt(); ?><a href="<?php the_permalink(); ?>">Read More</a></div>
      </article>
    <?php endwhile; ?>
    <div class="blog-pagination">
      <?= paginate_links([
        'total' => $total,
      ]); ?>
    </div>
  </section>
<?php
} else {
  $fake_query = new WP_Query([
    'post_type' => ['post', 'interactive'],
    'posts_per_page' => $posts_per_page,
    'meta_query' => $meta,
  ]);
  $found_posts = $fake_query->found_posts;
  if(!$show) $found_posts = $found_posts + $posts_per_page - 4;
  $total = ceil($found_posts / $posts_per_page);
  $main_query = new WP_Query([
    'post_type' => ['post', 'interactive'],
    'posts_per_page' => $total_results,
    'post__in' => $in,
    'orderby' => 'post__in',
    'meta_query' => $meta,
  ]);
  $secondary_query = new WP_Query([
    'post_type' => ['post', 'interactive'],
    'posts_per_page' => $total_results - $main_query->found_posts,
    'post__not_in' => $in,
    'meta_query' => $meta,
  ]);
  $posts = array_merge($main_query->posts, $secondary_query->posts);
  $posts = array_slice($posts, 0, $total_results);

  $feat_cont = new ContentNode( $posts[0] );
  $elevated_cont = array_slice($posts, 1, 3);
  $rest = array_slice($posts, 4);
?>
  <article class="homeFeat">
    <header class="post-header">
      <div class="post-type">
        <?= ($t = get_field('blog_title', 'option')) ? $t : __('Blog'); ?>
      </div>
    </header>
    <div class="homeFeat-inner">
      <h2 class="post-title"><?= $feat_cont->title; ?></h2>
      <div class="post-image js-img" data-showcredit data-img="<?= $feat_cont->img; ?>">
        <a href="<?= $feat_cont->link; ?>">
          <?= wp_get_attachment_image( $feat_cont->img, 'feat_home' ); ?>
        </a>
      </div>
      <?php if( $feat_cont->excerpt ){ ?>
        <p class="post-excerpt"><?= $feat_cont->excerpt; ?></p>
      <?php } ?>
      <a class="post-link" href="<?= $feat_cont->link; ?>">
        <?php _e('View Post'); ?>
      </a>
    </div>
    <form class="blog-header-search" method="get" action="<?php bloginfo('url'); ?>/">
      <input name="s" type="text" placeholder="Search blog">
      <input name="type" value="blog" type="hidden">
      <button type="submit"><?= icon( 'search' ); ?></button>
    </form>
    <div class="blog-header-authors">
    <label class="sr-only" for="author-select">Authors</label>
    <?php
    $q = new WP_User_Query([
      'has_published_posts' => ['post', 'interactive'],
      'number' => 9999,
      'fields' => 'ids'
    ]);
    $users = $q->get_results();
    ?>
    <div class="select-wrap">
    <select id="author-select">
      <option value="null">Authors</option>
    <?php
    foreach($users as $author):
    ?>
      <option value="<?= get_author_posts_url($author); ?>">
        <?= get_author_name($author); ?>
      </option>
    <?php endforeach; ?>
    <?php $u_list_page = get_posts([
      'post_type' => 'page',
      'meta_key' => '_wp_page_template',
      'meta_value' => 'template-authors.php'
    ]);
    if(count($u_list_page)): ?>
    <option value="<?= get_permalink($u_list_page[0]); ?>">
      List All Authors
    </option>
  <?php endif;
    ?>
      </select>
    </div>
    </div>
    </section>
  </article>

  <section class="postRoll postRoll--featured">
    <?php foreach($elevated_cont as $post): ?>
      <?php $content = new ContentNode( $post->ID ); $content->html(); ?>
    <?php endforeach; ?>
  </section>

    <?php if( $show ): ?>
    <section class="postRoll postRoll--home postRoll--blog">
        <?php foreach($rest as $post): ?>
          <?php $content = new ContentNode( $post->ID ); $content->html(); ?>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>
    <div class="blog-pagination">
      <?= paginate_links([
        'total' => $total
      ]); ?>
    </div>
    <section class="siteDescription">
      <h2><?= get_field( 'site_description_header', 'options' ); ?></h2>
      <?= get_field( 'site_description', 'options' ); ?>
      <?php $links = get_field( 'site_description_links', 'options' ); ?>
      <?php if( $links ){ ?>
        <ul class="siteDescription-links">
        <?php foreach( $links as $link ){ ?>
          <li class="siteDescription-link">
            <a href="<?= get_the_permalink( $link['home_link'] ); ?>">
              <?= get_the_title( $link['home_link'] ); ?>
              <?= icon( 'right', 'link' ); ?>
            </a>
          </li>
        <?php } ?>
        </ul>
      <?php } ?>
    </section>
    <div class="featured">
      <?php get_template_part( 'templates/buckets' ); ?>
    </div>
<?php
}
