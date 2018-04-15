<?php

class ContentNode {
    public function __construct($id, $is_taxonomy = false) {
      if ($is_taxonomy) {
          $the_term = get_term($id);
      } else {
          $the_post = get_post($id);
      }

      $this->id = $id;
      $this->date = $is_taxonomy ? $this->get_term_date($the_term) : get_the_date('Ymd', $id);

      $this->excerpt = $is_taxonomy ?
        $this->limit_words(get_field('collection_description', 'collection_'.$id), 80) :
        $this->limit_words($the_post->post_excerpt, 80);

      if(!$this->excerpt || !strlen($this->excerpt)) {
        preg_match_all('/.*\n/', $the_post->post_content, $first_p);
        $this->excerpt = $this->limit_words($first_p[0][0], 80);
      }

      $this->img = $is_taxonomy ?
        get_field($the_term->taxonomy.'_img', $the_term->taxonomy.'_'.$id) :
        get_post_thumbnail_id($id);

      $this->link = $is_taxonomy ? get_term_link($id) : get_permalink($id);
      $this->title = $is_taxonomy ? $the_term->name : $the_post->post_title;
      $this->type = $is_taxonomy ? $the_term->taxonomy : get_post_type($id);

      if($this->type === 'post') $this->type = 'blog';

      $this->img_set = !$this->img ? null : [
        'caption'  => get_post($this->img)->post_excerpt,
        'original' => wp_get_attachment_image_src($this->img, 'full')[0],
        'credit' => [
          'author' => get_post_meta($this->img, 'photographer_name', true),
          'src' => get_post_meta($this->img, 'photographer_url', true)
        ],
        'alt' => get_post_meta($this->img, '_wp_attachment_image_alt', true),
        'sizes' => [
          'xs' => wp_get_attachment_image_src($this->img, 'feat_xs')[0],
          'sm' => wp_get_attachment_image_src($this->img, 'feat_sm')[0],
          'md' => wp_get_attachment_image_src($this->img, 'feat_md')[0],
          'lg' => wp_get_attachment_image_src($this->img, 'feat_lg')[0]
        ]
      ];
    }

    private function get_term_date($term) {
      $date = get_the_date(
        'Ymd',
        get_posts([
        'post_type' => 'any',
        'tax_query' => [
          [
            'taxonomy' => $term->taxonomy,
            'field' => 'name',
            'terms' => $term->name,
            'order' => 'ASC'
          ]
        ],
        'posts_per_page' => 1
      ])
    );
    if (count($date)) {
      return $date[0];
    }
  }

    private function limit_words($string, $count) {
      $string = strip_tags($string);
      $split = explode(' ', $string, $count + 1);
      if (count($split) === $count + 1) {
          array_pop($split);
          $string_limited = implode(' ', $split);
          $string_limited = rtrim($string_limited, '.,?!');
          $string_limited = $string_limited.'&hellip;';
      } else {
          $string_limited = implode(' ', $split);
      }
      return $string_limited;
    }

    public function html($classes = '') {
      the_post($this->id);
      ?>
      <article class="post post--<?= $this->type; ?> <?= $classes; ?>">
        <?php if($this->type === 'blog' || $this->type === 'interactive'){ ?>

        <?php } else { ?>
          <a class="post-hyperlink" href="<?= $this->link; ?>">
        <?php } ?>
          <header class="post-header">
            <div class="post-type"><?= icon($this->type, 'type'); ?><?= ucfirst($this->type); ?></div>
            <?php if ($this->type === 'collection') {
              ?>
              <dl class="post-meta">
                <dt class="sr-only">Number of interviews:</dt>
                <dd>
                  <?= icon('interview', 'type'); ?>
                  <?= $this->interview_count; ?>
                </dd>
                <dt class="sr-only">Number of timelines:</dt>
                <dd>
                  <?= icon('timeline', 'type'); ?>
                  <?= $this->timeline_count; ?>
                </dd>
              </dl>
            <?php
          } ?>
          </header>
          <?php if ($this->type === 'collection') {
              ?>
            <h2 class="post-title"><?= $this->title; ?></h2>
          <?php
          } ?>
          <div class="post-image js-img" data-img="<?= $this->img; ?>">
            <span href="<?= $this->link; ?>">
              <?= wp_get_attachment_image($this->img, 'feat_md'); ?>
            </span>
          </div>
          <?php if ($this->type !== 'collection') {
              ?>
            <h2 class="post-title"><?= $this->title; ?></h2>
              <?php if($this->type === 'blog' || $this->type === 'interactive'){ ?>
              <div class="blog-meta">
                Posted <strong><?php the_date(); ?></strong> by
                <a href="<?= get_author_posts_url( get_the_author_meta('ID') ); ?>">
                  <?php the_author(); ?>
                </a>
              </div>
            <?php } ?>
          <?php
          } ?>
          <?php if ($this->excerpt) {
              ?>
            <div class="post-excerpt"><?= $this->excerpt; ?></div>
          <?php
          } ?>

            <?= $this->type !== 'blog' && $this->type !== 'interactive' ?
              '<span class="post-link">View The ' . ucfirst($this->type) .'<span>':
              '<a class="post-link" href="'.$this->link.'">View Post</a>';
            ?>
          </span>
          <!--
          <?php if($this->type === 'blog' && get_the_category_list()): ?>
            <div class="blog-category">
              Posted in <?= get_the_category_list(); ?>
            </div>
          <?php endif; ?>
          <?php if($this->type === 'blog' && get_the_tag_list()): ?>
            <div class="blog-category">
              Tagged <?= get_the_tag_list(); ?>
            </div>
          <?php endif; ?>
          -->
        <?php if($this->type !== 'blog'){ ?>
          </a>
        <?php } ?>
      </article>
    <?php
  }
}

class ContentNodeCollection extends ContentNode
{
    public function __construct($id)
    {
        parent::__construct($id, true);
        $interview_count = 0;
        $timeline_count = 0;
        $posts_in_term = get_posts([
          'post_type' => ['interview','timeline'],
          'tax_query' => [
            [
              'taxonomy' => 'collection',
              'field' => 'id',
              'terms' => $id
            ]
          ],
          'posts_per_page' => -1,
          'field' => 'ids',
        ]);

        foreach ($posts_in_term as $post_in_term) {
            $type = get_post_type($post_in_term->ID);
            if ($type === 'interview') {
                $interview_count++;
            }
            if ($type === 'timeline') {
                $timeline_count++;
            }
        }

        $this->interview_count = $interview_count;
        $this->timeline_count = $timeline_count;
    }
}
