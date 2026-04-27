<?php
/**
 * SKYNET SYSTEMS — Optimizaciones de rendimiento para WordPress
 *
 * INSTRUCCIONES:
 * Copia el contenido de este archivo al final del functions.php de tu tema activo.
 * Ruta en servidor: wp-content/themes/TU-TEMA/functions.php
 *
 * PARA EL .htaccess (añadir en la raíz de WordPress, antes de # BEGIN WordPress):
 * ─────────────────────────────────────────────────────────────────────
 * <IfModule mod_expires.c>
 *   ExpiresActive On
 *   ExpiresByType text/css              "access plus 1 year"
 *   ExpiresByType application/javascript "access plus 1 year"
 *   ExpiresByType image/webp            "access plus 1 year"
 *   ExpiresByType image/png             "access plus 1 year"
 *   ExpiresByType image/jpeg            "access plus 1 year"
 *   ExpiresByType image/svg+xml         "access plus 1 year"
 *   ExpiresByType font/woff2            "access plus 1 year"
 * </IfModule>
 * <IfModule mod_deflate.c>
 *   AddOutputFilterByType DEFLATE text/html text/css application/javascript
 * </IfModule>
 * ─────────────────────────────────────────────────────────────────────
 */

// ── 1. DESENCOLAR RECURSOS INNECESARIOS EN LA HOME ──────────────────────────
// Magnific Popup solo se necesita en páginas con galería/media, no en la home.
// Esto ahorra ~21 KiB de CSS y ~610ms de bloqueo de renderizado.
add_action( 'wp_enqueue_scripts', function () {
    if ( is_front_page() ) {
        // Magnific Popup CSS + JS (ajusta el handle si fuera diferente)
        wp_dequeue_style( 'magnific-popup' );
        wp_dequeue_script( 'magnific-popup' );
        wp_dequeue_script( 'jquery-magnific-popup' );
    }
}, 99 );


// ── 2. JQUERY EN EL PIE DE PÁGINA (en lugar del <head>) ─────────────────────
// jQuery en el <head> bloquea el renderizado 610ms. Moverlo al footer
// requiere que los plugins que dependen de él también lo acepten.
// PRECAUCIÓN: activa esto y comprueba que todo funciona correctamente.
// Si algo se rompe, comenta estas líneas.
/*
add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_admin() ) {
        wp_deregister_script( 'jquery' );
        wp_register_script(
            'jquery',
            includes_url( '/js/jquery/jquery.min.js' ),
            [],
            '3.7.1',
            true  // true = pie de página
        );
        wp_enqueue_script( 'jquery' );
    }
}, 99 );
*/


// ── 3. GOOGLE FONTS — CARGA NO BLOQUEANTE ───────────────────────────────────
// Si Blocksy/Elementor cargan Google Fonts de forma bloqueante, reemplázalos
// con esta versión que usa media="print" + onload (no bloquea renderizado).
// Solo actívalo si Lighthouse sigue reportando Google Fonts como render-blocking.
/*
add_action( 'wp_head', function () {
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap"
          media="print"
          onload="this.media='all'">
    <noscript>
      <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap">
    </noscript>
    <?php
}, 1 );
*/


// ── 4. DESHABILITAR EMOJIS DE WORDPRESS (ahorra ~15 KB innecesarios) ────────
add_action( 'init', function () {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
} );


// ── 5. DESENCOLAR RECURSOS INNECESARIOS EN SERVICIOS IT ─
// servicios-it usa un popup de telefono propio, sin Contact Form 7.
add_action( 'wp_enqueue_scripts', function () {
    if ( is_page( [ 'servicios-it', 'soluciones-tic', 'desarrollo', 'comunicacion-web', 'ciberseguridad' ] ) ) {
        // Contact Form 7
        wp_dequeue_script( 'contact-form-7' );
        wp_dequeue_style( 'contact-form-7' );
        wp_dequeue_script( 'wpcf7-swv' );
        wp_dequeue_style( 'wpcf7-swv' );

        // jQuery UI y Migrate (no necesarios en esta página)
        wp_dequeue_script( 'jquery-ui-core' );
        wp_dequeue_script( 'jquery-migrate' );
    }
}, 99 );


// ── 6. PREFETCH EN HOVER — navegación casi instantánea ──────────────────────
// instant.page (~1 KB) empieza a precargar la siguiente página en cuanto el
// usuario pone el cursor sobre un enlace interno (~300 ms antes del clic).
// Resultado: la navegación entre páginas se siente inmediata.
add_action( 'wp_footer', function () {
    echo '<script src="https://instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aCdq+" crossorigin="anonymous"></script>' . "\n";
}, 99 );


// ── 7. DNS PREFETCH PARA DOMINIOS EXTERNOS ───────────────────────────────────
// Resuelve el DNS de las CDNs antes de que el navegador las necesite.
add_action( 'wp_head', function () {
    echo '<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">' . "\n";
    echo '<link rel="dns-prefetch" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="https://fonts.gstatic.com">' . "\n";
    echo '<link rel="dns-prefetch" href="https://lottie.host">' . "\n";
}, 1 );


// ── 8. ELIMINAR RECURSOS INNECESARIOS DEL FRONTEND ──────────────────────────
// Dashicons solo se necesita en el admin. En el frontend ~47 KB gratis.
// wp-embed.js tampoco sirve si no incrustas posts de otros sitios.
// Gutenberg block-library CSS ~71 KB — no hace falta si solo usas Elementor.
add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_admin() ) {
        wp_dequeue_style( 'dashicons' );
        wp_dequeue_script( 'wp-embed' );

        // Eliminar CSS de Gutenberg si solo usas Elementor (sin bloques Gutenberg)
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'global-styles' );          // WordPress 5.9+
        wp_dequeue_style( 'classic-theme-styles' );   // WordPress 6.1+
    }
}, 100 );


// ── 9. PRELOAD DE LA FUENTE PRINCIPAL ────────────────────────────────────────
// El navegador descubre las fuentes tarde (cuando parsea el CSS).
// Con preload las pide en paralelo desde el primer byte del HTML.
add_action( 'wp_head', function () {
    echo '<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" onload="this.rel=\'stylesheet\'" crossorigin>' . "\n";
}, 2 );


// ── 10. ELIMINAR QUERY STRINGS DE RECURSOS ESTÁTICOS ────────────────────────
// Los ?ver=X.X.X impiden el caché en CDN y proxies.
add_filter( 'script_loader_src', 'skynet_remove_query_strings', 15 );
add_filter( 'style_loader_src',  'skynet_remove_query_strings', 15 );
function skynet_remove_query_strings( $src ) {
    if ( is_admin() ) return $src;
    $parts = explode( '?ver=', $src );
    return $parts[0];
}


// ── 11. CSS CRÍTICO INLINE POR PÁGINA ────────────────────────────────────────
// Inyecta en <head> el CSS mínimo para renderizar el above-the-fold sin
// esperar a que carguen las hojas de estilo externas del tema.
//
// Estructura de archivos (relativa a la raíz del tema activo):
//   critical-css/global.css      → fondo + cabecera  (todas las páginas)
//   critical-css/home.css        → hero Inicio
//   critical-css/servicios-it.css → hero Servicios IT / Infraestructura IT
//   critical-css/desarrollo.css  → hero Desarrollo / Comunicación Web
//   critical-css/actualidad.css  → cabecera Actualidad
//   critical-css/nosotros.css    → sección Nosotros
//   critical-css/inner.css       → fallback páginas internas
//
// Para regenerar un archivo: edita el CSS en critical-css/ y se aplica
// automáticamente en el siguiente pageload (no hay caché de PHP aquí).
add_action( 'wp_head', function () {
    $dir = get_template_directory() . '/critical-css/';

    // Mapa página → archivo CSS específico
    $page_css = null;
    if ( is_front_page() || is_home() ) {
        $page_css = $dir . 'home.css';
    } elseif ( is_page( [ 'servicios-it', 'infraestructura-it', 'soluciones-tic' ] ) ) {
        $page_css = $dir . 'servicios-it.css';
    } elseif ( is_page( [ 'desarrollo', 'comunicacion-web' ] ) ) {
        $page_css = $dir . 'desarrollo.css';
    } elseif ( is_page( 'actualidad' ) || is_post_type_archive( 'noticia' ) ) {
        $page_css = $dir . 'actualidad.css';
    } elseif ( is_page( 'nosotros' ) ) {
        $page_css = $dir . 'nosotros.css';
    } elseif ( is_page( [ 'ciberseguridad', 'normativa', 'contacto', 'soporte-clientes' ] ) ) {
        $page_css = $dir . 'inner.css';
    }

    // Leer y combinar CSS
    $css = '';
    $global_file = $dir . 'global.css';
    if ( file_exists( $global_file ) ) {
        $css .= file_get_contents( $global_file );
    }
    if ( $page_css && file_exists( $page_css ) ) {
        $css .= file_get_contents( $page_css );
    }

    if ( $css ) {
        echo '<style id="sky-critical">' . $css . '</style>' . "\n";
    }
}, 1 ); // priority 1 → antes que cualquier otro wp_head


// ── 12. VIEW TRANSITIONS API + INSTANT.PAGE ──────────────────────────────────
// Combina dos técnicas para una navegación casi instantánea:
//   a) instant.page: precarga la página al hover (~300 ms antes del clic).
//   b) View Transitions API: el navegador aplica un fade nativo entre páginas
//      sin ningún JS adicional. Compatible con Chrome 111+, Edge 111+, Safari 18+.
//      En navegadores sin soporte simplemente se ignora (degradación elegante).
add_action( 'wp_head', function () {
    // Meta hint para View Transitions (navegación same-origin)
    echo '<meta name="view-transition" content="same-origin">' . "\n";
    // CSS: activa la transición automática al navegar
    echo '<style id="sky-view-transitions">@view-transition{navigation:auto}</style>' . "\n";
}, 2 );

// instant.page ya está en la sección 6 — no duplicar aquí.
