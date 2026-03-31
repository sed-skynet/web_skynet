/**
 * Shortcode de tira de sellos/partners para usar en otras paginas.
 */
function skynet_sellos_partners_shortcode( $atts = [] ) {

  $atts = shortcode_atts([
    'gap'   => '40',
    'speed' => '30',
    'class' => '',
  ], $atts, 'sellos_partners');

  $gap   = max(10, intval($atts['gap']));
  $speed = max(8, intval($atts['speed']));
  $uid   = wp_unique_id('sellos-pack-');

  ob_start(); ?>

<section id="<?php echo esc_attr($uid); ?>" class="sellos-pack <?php echo esc_attr($atts['class']); ?>" aria-label="Sellos y partners">
  <div class="sellos-track">
    <img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2020/04/google-partner.png" alt="Google Partner">
    <img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2022/06/microsoft-gris.png" alt="Microsoft Partner">
    <img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/03/silver-partner-synology-2.png" alt="Synology Silver Partner">
    <img decoding="async" class="sello sello-grande" src="https://darknet-sys.com/wp-content/uploads/2026/01/WatchGuardONE_Gold.png" alt="WatchGuard ONE Gold Partner">
    <img decoding="async" class="sello sello-grande" src="https://skynet-sys.es/wp-content/uploads/2022/03/Registered-Logo.png" alt="TP-LINK Partner">
    <img decoding="async" class="sello sello-grande" src="https://darknet-sys.com/wp-content/uploads/2026/01/incibe.webp" alt="INCIBE">
    <img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/02/pacto_entidad_adscrita_color.png" alt="Pacto Digital">
    <img decoding="async" class="sello sello-grande" src="https://darknet-sys.com/wp-content/uploads/2026/01/PETEC.webp" alt="PETEC">
    <img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/bp-del-e1769514187514.png" alt="Dell Partner">
    <img decoding="async" class="sello sello-grande" src="https://darknet-sys.com/wp-content/uploads/2026/01/wg1-Gold-Reseller-logo.png" alt="WatchGuard Gold Reseller">
    <img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/acens-logo-2.webp" alt="Acens Partner">

    <img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2020/04/google-partner.png" alt="Google Partner">
    <img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2022/06/microsoft-gris.png" alt="Microsoft Partner">
    <img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/03/silver-partner-synology-2.png" alt="Synology Silver Partner">
    <img decoding="async" class="sello sello-grande" src="https://darknet-sys.com/wp-content/uploads/2026/01/WatchGuardONE_Gold.png" alt="WatchGuard ONE Gold Partner">
    <img decoding="async" class="sello sello-grande" src="https://skynet-sys.es/wp-content/uploads/2022/03/Registered-Logo.png" alt="TP-LINK Partner">
    <img decoding="async" class="sello sello-grande" src="https://darknet-sys.com/wp-content/uploads/2026/01/incibe.webp" alt="INCIBE">
    <img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/02/pacto_entidad_adscrita_color.png" alt="Pacto Digital">
    <img decoding="async" class="sello sello-grande" src="https://darknet-sys.com/wp-content/uploads/2026/01/PETEC.webp" alt="PETEC">
    <img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/bp-del-e1769514187514.png" alt="Dell Partner">
    <img decoding="async" class="sello sello-grande" src="https://darknet-sys.com/wp-content/uploads/2026/01/wg1-Gold-Reseller-logo.png" alt="WatchGuard Gold Reseller">
    <img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/acens-logo-2.webp" alt="Acens Partner">
  </div>
</section>

<style>
#<?php echo $uid; ?>{
  padding:2vh var(--home-side-pad, clamp(16px, 4vw, 40px)) 4vh;
  overflow:hidden;
  position:relative;
  z-index:2;
}
#<?php echo $uid; ?> .sellos-track{
  display:flex;
  align-items:center;
  gap:<?php echo $gap; ?>px;
  width:max-content;
  animation:scroll-<?php echo $uid; ?> <?php echo $speed; ?>s linear infinite;
}
#<?php echo $uid; ?>:hover .sellos-track{
  animation-play-state:paused;
}
#<?php echo $uid; ?> .sello{
  height:65px;
  max-height:65px;
  object-fit:contain;
  filter:drop-shadow(0 2px 6px rgba(0,0,0,.25));
  opacity:.95;
  transition:transform .18s ease, filter .18s ease;
}
#<?php echo $uid; ?> .sello:hover{
  transform:scale(1.05);
}
#<?php echo $uid; ?> .sello-grande{
  height:95px;
  max-height:95px;
}
#<?php echo $uid; ?> img[alt="INCIBE"],
#<?php echo $uid; ?> img[alt="Pacto Digital"]{
  background:none!important;
  padding:0!important;
  border:none!important;
  filter:drop-shadow(0 4px 14px rgba(0,0,0,.35)) brightness(1.1) contrast(1.1);
}
#<?php echo $uid; ?> img[alt="INCIBE"]{
  height:120px;
  max-height:120px;
}
#<?php echo $uid; ?> img[alt="Pacto Digital"]{
  height:85px;
  max-height:85px;
}
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
add_shortcode('sellos_partners', 'skynet_sellos_partners_shortcode');
