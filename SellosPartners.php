/**
 * Shortcode de tira de sellos/partners para usar en otras paginas.
 */
function skynet_sellos_partners_shortcode( $atts = [] ) {

  $atts = shortcode_atts([
    'gap'      => '40',
    'speed'    => '50',
    'title'    => 'Partners tecnológicos',
    'subtitle' => 'Alianzas estratégicas para soluciones sólidas, seguras y escalables.',
    'class'    => '',
  ], $atts, 'sellos_partners');

  $gap   = max(10, intval($atts['gap']));
  $speed = max(8, intval($atts['speed']));
  $uid   = wp_unique_id('sellos-pack-');

  ob_start(); ?>

<section id="<?php echo esc_attr($uid); ?>" class="sellos-pack <?php echo esc_attr($atts['class']); ?>" aria-label="Sellos y partners">
  <div class="sellos-shell">
    <header class="sellos-head">
      <h2 class="sellos-title"><?php echo esc_html($atts['title']); ?></h2>
      <?php if (!empty($atts['subtitle'])): ?>
        <p class="sellos-subtitle"><?php echo esc_html($atts['subtitle']); ?></p>
      <?php endif; ?>
      <span class="sellos-line" aria-hidden="true"></span>
    </header>

    <div class="sellos-viewport">
      <div class="sellos-track">
        <div class="sello-card"><img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2020/04/google-partner.png" alt="Google Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2022/06/microsoft-gris.png" alt="Microsoft Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/03/silver-partner-synology-2.png" alt="Synology Silver Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2022/03/Registered-Logo.png" alt="TP-LINK Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/bp-del-e1769514187514.png" alt="Dell Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/wg1-Gold-Reseller-logo.png" alt="WatchGuard Gold Reseller"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/acens-logo-2.webp" alt="Acens Partner"></div>

        <div class="sello-card"><img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2020/04/google-partner.png" alt="Google Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2022/06/microsoft-gris.png" alt="Microsoft Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2021/03/silver-partner-synology-2.png" alt="Synology Silver Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://skynet-sys.es/wp-content/uploads/2022/03/Registered-Logo.png" alt="TP-LINK Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/bp-del-e1769514187514.png" alt="Dell Partner"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/wg1-Gold-Reseller-logo.png" alt="WatchGuard Gold Reseller"></div>
        <div class="sello-card"><img decoding="async" class="sello" src="https://darknet-sys.com/wp-content/uploads/2026/01/acens-logo-2.webp" alt="Acens Partner"></div>
      </div>
    </div>
  </div>
</section>

<style>
#<?php echo $uid; ?>{
  padding:clamp(16px, 2.2vh, 24px) 0 4vh;
  position:relative;
  z-index:2;
}
#<?php echo $uid; ?> .sellos-shell{
  max-width:min(1100px, calc(100% - 2rem));
  margin:0 auto;
  padding:clamp(24px, 3vw, 40px);
  border-radius:22px;
  background: rgba(255, 255, 255, 0.22);
  border:1px solid rgba(0,207,255,.25);
  box-shadow:
    0 8px 32px rgba(44, 70, 130, 0.18),
    inset 0 1px 0 rgba(255,255,255,.14);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
}
#<?php echo $uid; ?> .sellos-head{
  text-align:center;
  margin:0 auto 1.2rem;
  max-width:860px;
}
#<?php echo $uid; ?> .sellos-title{
  margin:0;
  font-size:clamp(1.6rem,2.8vw,2.3rem);
  font-weight:800;
  letter-spacing:.02em;
  color:#ffffff;
}
#<?php echo $uid; ?> .sellos-subtitle{
  margin:.45rem 0 0;
  font-size:clamp(.92rem,1.4vw,1.02rem);
  line-height:1.5;
  color:#9ecae6;
}
#<?php echo $uid; ?> .sellos-line{
  display:block;
  width:60px;
  height:3px;
  margin:.85rem auto 0;
  border-radius:999px;
  background:#00cfff;
  box-shadow:0 6px 16px rgba(0,207,255,.25);
}
#<?php echo $uid; ?> .sellos-viewport{
  position:relative;
  overflow:hidden;
  border-radius:14px;
  border:1px solid rgba(255,255,255,.2);
  background:rgba(255,255,255,.10);
  padding:20px;
}
#<?php echo $uid; ?> .sellos-viewport::before,
#<?php echo $uid; ?> .sellos-viewport::after{
  content:"";
  position:absolute;
  top:0;
  width:120px;
  height:100%;
  z-index:2;
  pointer-events:none;
}
#<?php echo $uid; ?> .sellos-viewport::before{
  left:0;
  background:linear-gradient(to right, rgba(49, 122, 163, .95), transparent);
}
#<?php echo $uid; ?> .sellos-viewport::after{
  right:0;
  background:linear-gradient(to left, rgba(49, 122, 163, .95), transparent);
}
#<?php echo $uid; ?> .sellos-track{
  display:flex;
  align-items:center;
  gap:<?php echo $gap; ?>px;
  width:max-content;
  animation:scroll-<?php echo $uid; ?> <?php echo $speed; ?>s linear infinite;
}
#<?php echo $uid; ?> .sellos-viewport:hover .sellos-track{
  animation-play-state:paused;
}
#<?php echo $uid; ?> .sello-card{
  width: clamp(170px, 18vw, 210px);
  height: 116px;
  padding: 18px 20px;
  display:flex;
  align-items:center;
  justify-content:center;
  flex: 0 0 auto;
  border-radius: 22px;
  background: rgba(247, 244, 238, 0.96);
  border: 1px solid rgba(255,255,255,.38);
  box-shadow: 0 10px 24px rgba(0,0,0,.12);
}
#<?php echo $uid; ?> .sello{
  width: 100%;
  height: 66px;
  max-width: 164px;
  max-height: 66px;
  object-fit:contain;
  filter: drop-shadow(0 2px 6px rgba(0,0,0,.18));
  opacity: 1;
  transition:transform .18s ease, filter .18s ease;
}
#<?php echo $uid; ?> .sello:hover{
  transform:scale(1.05);
}
#<?php echo $uid; ?> img[alt="WatchGuard ONE Gold Partner"]{
  max-width: 156px;
}
#<?php echo $uid; ?> img[alt="WatchGuard Gold Reseller"]{
  max-width: 168px;
}
@media (max-width: 640px){
  #<?php echo $uid; ?> .sello-card{
    width: 150px;
    height: 102px;
    padding: 16px;
    border-radius: 18px;
  }
  #<?php echo $uid; ?> .sello{
    height: 56px;
    max-height: 56px;
    max-width: 132px;
  }
  #<?php echo $uid; ?> img[alt="WatchGuard Gold Reseller"]{
    max-width: 142px;
  }
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
