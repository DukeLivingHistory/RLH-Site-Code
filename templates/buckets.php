<section class="buckets">
  <?php $buckets = ['Interviews','Collections','Timelines', 'Blog']; ?>
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
      <p class="buckets-text js-eqHeight--bucket">
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
