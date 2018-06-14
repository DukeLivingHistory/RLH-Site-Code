<header class="header">
  <?php if( is_front_page() ){ ?>
  <div class="header-logo header-logo--top">
    <?php $secondaryLogo = get_field( 'secondary_brand_logo', 'options' ); ?>
    <img src="<?= $secondaryLogo['url']; ?>" alt="<?= $secondaryLogo['alt']; ?>"/>
  </div>
  <div class="header-logo header-logo--bottom">
    <?php $primaryLogo = get_field( 'primary_brand_logo', 'options' ); ?>
    <img src="<?= $primaryLogo['url']; ?>" alt="<?= $primaryLogo['alt']; ?>"/>
  </div>
  <?php } ?>
  <nav class="header-nav">
    <h1 class="header-navLogo">
      <a href="/">
        <span>Rutherfurd Living History</span>
        <?php $altLogo = get_field( 'primary_brand_logo_alt', 'options' ); ?>
        <img src="<?= $altLogo['url']; ?>" alt="<?= $altLogo['alt']; ?>"/>
      </a>
    </h1>
    <button class="header-navToggle">
      <span class="header-navToggleButton"></span>
      <span class="header-navToggleLabel">Menu</span>
    </button>
    <div class="header-navInner">
      <?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => '', 'menu_class' => 'menu menu--primary' ] ); ?>
      <form method="get" action="<?php bloginfo('url'); ?>/">
        <input autocomplete="off" name="s" type="text" placeholder="Search" value="<?= $_GET['s'] ? $_GET['s'] : ''; ?>">
        <button type="submit"><?= icon( 'search' ); ?></button>
        <button type="button" id="option_toggle"><?= icon( 'options' ); ?></button>
        <div class="header-navInner-options <?= $_GET['whole_word'] || $_GET['case_sensitive'] ?   'open' : ''; ?> ">
          <div>
            <label for="whole_word">Whole-Word</label>
            <input id="whole_word" name="whole_word" type="checkbox" <?= $_GET['whole_word'] ? 'checked' : ''; ?> />
          </div>
          <div>
            <label for="case_sensitive">Case-Sensitive</label>
            <input name="case_sensitive" type="checkbox" <?= $_GET['case_sensitive'] ? 'checked' : ''; ?> />
          </div>
        </div>
      </form>
      <?php wp_nav_menu( [ 'theme_location' => 'utility', 'container' => '', 'menu_class' => 'menu menu--utility' ] ); ?>
    </div>
  </nav>
</header>
