/**
 * Shortcode combinado: Trustindex + Tira de Sellos + Título
 * Uso:
 *   [opiniones_y_sellos title="Opiniones reales" subtitle="Lo que dicen nuestros clientes" accent="#00cfff" icon_height="46" gap="40" speed="30"]
 *   [opiniones_y_sellos ti_sc='[trustindex no-registration=google]']
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

  // Saneos
  $accent      = preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $atts['accent']) ? $atts['accent'] : '#00cfff';
  $icon_height = max(24, intval($atts['icon_height']));
  $gap         = max(10, intval($atts['gap']));
  $speed       = max(8, intval($atts['speed']));
  $uid         = wp_unique_id('ti-pack-');

  // Render Trustindex
  $trustindex_html = do_shortcode( $atts['ti_sc'] );

  ob_start(); ?>

  <section id="<?php echo esc_attr($uid); ?>" class="ti-pack <?php echo esc_attr($atts['class']); ?>" style="--ti-accent: <?php echo esc_attr($accent); ?>;">
    <?php if ( !empty($atts['title']) ): ?>
      <div class="ti-pack__header">
        <h2 class="ti-pack__title"><?php echo esc_html($atts['title']); ?></h2>
        <?php if ( !empty($atts['subtitle']) ): ?>
          <p class="ti-pack__subtitle"><?php echo esc_html($atts['subtitle']); ?></p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="ti-pack__inner">
      <div class="ti-pack__opiniones">
        <?php echo $trustindex_html; ?>
      </div>

      <!-- ======= Tira de sellos ======= -->
      <section class="ti-pack__tira" aria-label="Tira de sellos y partners (iconos)">
        <div class="sellos-track">

          <!-- Tanda 1 -->
          <img class="sello" src="https://skynet-sys.es/wp-content/uploads/2020/04/google-partner.png" alt="Google Partner">
          <img class="sello" src="https://skynet-sys.es/wp-content/uploads/2022/06/microsoft-gris.png" alt="Microsoft Partner">
          <img class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/03/silver-partner-synology-2.png" alt="Synology Silver Partner">

          <img class="sello sello-grande"
               src="https://darknet-sys.com/wp-content/uploads/2026/01/WatchGuardONE_Gold.png"
               alt="WatchGuard ONE Gold Partner">

          <img class="sello sello-grande" src="https://skynet-sys.es/wp-content/uploads/2022/03/Registered-Logo.png" alt="TP-LINK Partner">
          <img class="sello sello-grande" src="https://skynet-sys.es/wp-content/uploads/2021/03/incibe-partner2.png" alt="INCIBE">
          <img class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/02/pacto_entidad_adscrita_color.png" alt="Pacto Digital">
          <img class="sello sello-grande" src="https://skynet-sys.es/wp-content/uploads/2020/06/petec-partner.jpg" alt="PETEC">

          <!-- Tanda 2 (duplicada para loop continuo) -->
          <img class="sello" src="https://skynet-sys.es/wp-content/uploads/2020/04/google-partner.png" alt="Google Partner">
          <img class="sello" src="https://skynet-sys.es/wp-content/uploads/2022/06/microsoft-gris.png" alt="Microsoft Partner">
          <img class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/03/silver-partner-synology-2.png" alt="Synology Silver Partner">

          <img class="sello sello-grande"
               src="https://darknet-sys.com/wp-content/uploads/2026/01/WatchGuardONE_Gold.png"
               alt="WatchGuard ONE Gold Partner">

          <img class="sello sello-grande" src="https://skynet-sys.es/wp-content/uploads/2022/03/Registered-Logo.png" alt="TP-LINK Partner">
          <img class="sello sello-grande" src="https://skynet-sys.es/wp-content/uploads/2021/03/incibe-partner2.png" alt="INCIBE">
          <img class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/02/pacto_entidad_adscrita_color.png" alt="Pacto Digital">
          <img class="sello sello-grande" src="https://skynet-sys.es/wp-content/uploads/2020/06/petec-partner.jpg" alt="PETEC">

        </div>
      </section>
    </div>
  </section>

  <style>
  /* ====== Encaje de sección: mismo ancho y espaciados que tus cards ====== */
  #<?php echo $uid; ?>.ti-pack{
    padding: 6vh 5vw;
    position: relative;
    z-index: 2;
  }
  #<?php echo $uid; ?> .ti-pack__header{
  text-align: center;
  margin: 0 0 2.2rem;   /* antes: 1.2rem  -> MÁS espacio debajo del header */
  }
#<?php echo $uid; ?> .ti-pack__title {
    font-family: 'Segoe UI', sans-serif;
    font-size: 2.5rem;         /* igual que .header-iso */
    font-weight: 700;          /* igual */
    color: #00cfff;            /* mismo color */
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 0.6rem;               /* separación opcional entre ícono/texto */
    margin-bottom: .45rem; /* antes: 1.5rem -> SUBTÍTULO más arriba */
}
/*  */
  #<?php echo $uid; ?> .ti-pack__subtitle{
    font-family: 'Segoe UI', sans-serif;
    font-size: 1.06rem;
    color: #cdefff;
    opacity: .9;
    margin: 0 auto 0;
    max-width: 820px;
    margin: 0 auto;
  }
  /* Inner con el mismo max-width que .card-iso-animada */
  #<?php echo $uid; ?> .ti-pack__inner{
    max-width: 1160px;
    margin: 0 auto;
  }

  /* Reset suave del widget Trustindex para que encaje con tu glass/oscuro */
  #<?php echo $uid; ?> .ti-pack__opiniones :is(.ti-widget, [class*="ti-widget"], [class*="ti-reviews"]){
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    margin: 0 auto 22px !important; /* pequeño gap con la tira de sellos */
    max-width: 100% !important;
  }

  /* ---- Tira de sellos (encapsulada) ---- */

#<?php echo $uid; ?> .ti-pack__tira{
  margin: 8px auto 28px;
  padding: 12px 0;
  overflow: hidden;
  position: relative;
  /* border-top y border-bottom eliminados */
}/*  */
  #<?php echo $uid; ?> .ti-pack__tira .sellos-track{
    display: flex;
    align-items: center;
    gap: <?php echo $gap; ?>px;
    animation: ti-sellos-scroll-<?php echo $uid; ?> <?php echo $speed; ?>s linear infinite;
    will-change: transform;
  }
  #<?php echo $uid; ?> .ti-pack__tira .sello{
    flex: 0 0 auto;
    height: <?php echo $icon_height; ?>px !important;
    max-height: <?php echo $icon_height; ?>px !important;
    object-fit: contain;
    filter: drop-shadow(0 2px 6px rgba(0,0,0,.25));
    opacity: .95;
    transition: transform .18s ease, opacity .18s ease;
  }
  #<?php echo $uid; ?> .ti-pack__tira .sello:hover{ transform: scale(1.05); opacity: 1; }
  #<?php echo $uid; ?> .ti-pack__tira .sello-grande{
    height: <?php echo $icon_height + 24; ?>px !important;
    max-height: <?php echo $icon_height + 24; ?>px !important;
  }
  #<?php echo $uid; ?> .ti-pack__tira:hover .sellos-track{ animation-play-state: paused; }

  
  @keyframes ti-sellos-scroll-<?php echo $uid; ?>{
    0%{ transform: translateX(0); }
    100%{ transform: translateX(-50%); }
  }

  /* Respeta reduce-motion */
  @media (prefers-reduced-motion: reduce){
    #<?php echo $uid; ?> .ti-pack__tira .sellos-track{ animation: none; }
  }
  </style>
  <?php
  return ob_get_clean();
}
add_shortcode('opiniones_y_sellos', 'skynet_opiniones_y_sellos_shortcode');