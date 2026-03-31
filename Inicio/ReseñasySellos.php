/**
 * Shortcode combinado: Trustindex + Tira de Sellos + Título
 */
function skynet_opiniones_y_sellos_shortcode( $atts = [] ) {

  $atts = shortcode_atts([
    'ti_sc'       => '[trustindex no-registration=google]',
    'title'       => '',
    'subtitle'    => '',
    'accent'      => '#00cfff',
    'icon_height' => '60',
    'gap'         => '40',
    'speed'       => '30',
    'class'       => '',
  ], $atts, 'opiniones_y_sellos');

  $accent      = preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $atts['accent']) ? $atts['accent'] : '#00cfff';
  $icon_height = max(24, intval($atts['icon_height']));
  $gap         = max(10, intval($atts['gap']));
  $speed       = max(8, intval($atts['speed']));
  $uid         = wp_unique_id('ti-pack-');

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

    <!-- ===== Opiniones (Trustindex SIN romper estilos) ===== -->
    <div class="ti-pack__opiniones">
      <?php echo $trustindex_html; ?>
    </div>

    <!-- ===== Tira de sellos ===== -->
    

  </div>
</section>

<style>
/* ================= CONTENEDOR ================= */
#<?php echo $uid; ?>{
  padding:6vh var(--home-side-pad, clamp(16px, 4vw, 40px));
  position:relative;
  z-index:2;
}
#<?php echo $uid; ?> .ti-pack__inner{
  max-width: var(--home-max, 1280px);
  margin:0 auto;
}

/* ================= HEADER ================= */
#<?php echo $uid; ?> .ti-pack__header{
  text-align:center;
  margin-bottom:2.2rem;
}
#<?php echo $uid; ?> .ti-pack__title{
  font:700 2.5rem 'Segoe UI',sans-serif;
  color:var(--ti-accent);
  margin-bottom:.4rem;
}
#<?php echo $uid; ?> .ti-pack__subtitle{
  color:#cdefff;
  font-size:1.05rem;
  max-width:820px;
  margin:0 auto;
}

/* ================= TRUSTINDEX (RESPETADO) ================= */
#<?php echo $uid; ?> .ti-pack__opiniones{
  max-width:100%;
  margin:0 auto 26px;
}

/* ================= TIRA ================= */
#<?php echo $uid; ?> .ti-pack__tira{
  margin:10px auto 28px;
  overflow:hidden;
}
#<?php echo $uid; ?> .sellos-track{
  display:flex;
  align-items:center;
  gap:<?php echo $gap; ?>px;
  animation:scroll-<?php echo $uid; ?> <?php echo $speed; ?>s linear infinite;
}
#<?php echo $uid; ?> .ti-pack__tira:hover .sellos-track{
  animation-play-state:paused;
}

/* Logos base */
#<?php echo $uid; ?> .sello{
  height: 65px;
  max-height: 65px;
  object-fit: contain;
  filter: drop-shadow(0 2px 6px rgba(0,0,0,.25));
  opacity: .95;
  transition: transform .18s ease, filter .18s ease;
}

#<?php echo $uid; ?> .sello:hover{ transform:scale(1.05); }
#<?php echo $uid; ?> .sello-grande{
  height: 95px;
  max-height: 95px;
}
/* ================= LOGOS INSTITUCIONALES ================= */
#<?php echo $uid; ?> img[alt="INCIBE"],
#<?php echo $uid; ?> img[alt="Pacto Digital"]{
  background:none!important;
  padding:0!important;
  border:none!important;
  filter:drop-shadow(0 4px 14px rgba(0,0,0,.35)) brightness(1.1) contrast(1.1);
}
#<?php echo $uid; ?> img[alt="INCIBE"]{
  height: 120px;
  max-height: 120px;
}

#<?php echo $uid; ?> img[alt="Pacto Digital"]{
  height: 85px;
  max-height: 85px;
}

/* Animación */
@keyframes scroll-<?php echo $uid; ?>{
  to{ transform:translateX(-50%); }
}
@media (prefers-reduced-motion:reduce){
  #<?php echo $uid; ?> .sellos-track{ animation:none; }
}
</style>

<?php
  return ob_get_clean();
}
add_shortcode('opiniones_y_sellos', 'skynet_opiniones_y_sellos_shortcode');


