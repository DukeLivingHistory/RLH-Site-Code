<section class="buckets">
  <?php
    $buckets = ['Interviews','Collections'];
    if(count(get_posts(['post_type' => 'timeline']))) {
      $buckets = array_merge($buckets, ['Timelines']);
    }
    $buckets = array_merge($buckets, ['Blog']);
  ?>
  <?php foreach( $buckets as $bucket ){ $lc = strtolower($bucket); ?>
    <div class="buckets-bucket buckets-bucket--<?= strtolower($bucket); ?>">
      <?php
        $img = wp_get_attachment_image_src(
          get_field( $lc.'_content_image', 'options' )
        )[0];
      ?>
      <figure class="buckets-hero" style="background-image:url(<?= $img; ?>)"></figure>
      <h2 class="buckets-head">
        <?= icon( preg_replace('/s$/', '', $lc), 'type' ); ?> <?= $bucket; ?>
      </h2>
      <p class="buckets-text">
        <?= get_field( $lc.'_content_description', 'options' ); ?>
      </p>
      <span class="buckets-linkWrapper">
        <a href="/<?= $lc; ?>/" class="buckets-link">
          Browse <?= $bucket; ?>
          <?= icon( 'right', 'link' ); ?>
        </a>
      </span>
    </div>
  <?php } ?>
</section>
