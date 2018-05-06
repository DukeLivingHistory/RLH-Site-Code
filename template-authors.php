<?php
/* Template Name: Authors */
?>

  <header class="contentHeader contentHeader--archive">
    <h2>Authors</h2>
  </header>
<?php

$q = new WP_User_Query([
  'has_published_posts' => ['post', 'interactive'],
  'number' => 9999,
  'fields' => 'ids'
]);
$users = $q->get_results();
foreach($users as $author):
  ?>
  <div class="author">
    <a href="<?= get_author_posts_url($author); ?>" class="author-link">
    <?php if($avatar = get_avatar($author)): ?>
    <div class="author-thumbnail">
      <?= $avatar; ?>
    </div>
    <?php endif; ?>
    <div class="author-bio">
      <strong><?= get_author_name($author); ?> posts</strong>
      <p><?= get_the_author_meta('user_description', $author); ?></p>
    </div>
    </a>
  </div>
<?php
endforeach;
