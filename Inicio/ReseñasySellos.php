/**
 * Shortcode de opiniones (sin sellos) para la home.
 */
function skynet_opiniones_y_sellos_shortcode( $atts = [] ) {

  $atts = shortcode_atts([
    'ti_sc'     => '[trustindex no-registration=google]',
    'title'     => '',
    'subtitle'  => '',
    'accent'    => '#00cfff',
    'class'     => '',
  ], $atts, 'opiniones_y_sellos');

  $accent = preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $atts['accent']) ? $atts['accent'] : '#00cfff';
  $uid    = wp_unique_id('ti-pack-');

  $trustindex_html = do_shortcode( $atts['ti_sc'] );

  ob_start(); ?>

<section id="<?php echo esc_attr($uid); ?>" class="ti-pack <?php echo esc_attr($atts['class']); ?>" style="--ti-accent: <?php echo esc_attr($accent); ?>;">

  <?php if ( $atts['title'] ): ?>
    <header class="ti-pack__header">
      <h2 class="ti-pack__title"><?php echo esc_html($atts['title']); ?></h2>
      <?php if ( $atts['subtitle'] ): ?>
        <p class="ti-pack__subtitle"><?php echo esc_html($atts['subtitle']); ?></p>
      <?php endif; ?>
    </header>
  <?php endif; ?>

  <div class="ti-pack__inner">
    <div class="ti-pack__opiniones">
      <?php echo $trustindex_html; ?>
    </div>
  </div>
</section>

<style>
#<?php echo $uid; ?>{
  padding:6vh var(--home-side-pad, clamp(16px, 4vw, 40px));
  position:relative;
  z-index:2;
}
#<?php echo $uid; ?> .ti-pack__inner{
  max-width: var(--home-max, 1280px);
  margin:0 auto;
}
#<?php echo $uid; ?> .ti-pack__header{
  text-align:center;
  margin-bottom:2.2rem;
}
#<?php echo $uid; ?> .ti-pack__title{
  font:700 2.5rem "Segoe UI",sans-serif;
  color:var(--ti-accent);
  margin-bottom:.4rem;
}
#<?php echo $uid; ?> .ti-pack__subtitle{
  color:#cdefff;
  font-size:1.05rem;
  max-width:820px;
  margin:0 auto;
}
#<?php echo $uid; ?> .ti-pack__opiniones{
  max-width:100%;
  margin:0 auto;
}
</style>

<?php
  return ob_get_clean();
}
add_shortcode('opiniones_y_sellos', 'skynet_opiniones_y_sellos_shortcode');

