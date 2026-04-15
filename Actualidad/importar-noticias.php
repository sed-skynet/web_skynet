<?php
/**
 * Plugin Name: Importar Noticias Skynet
 * Description: Importa noticias2.csv como CPT 'noticia' con imágenes destacadas. Borrar tras usar.
 * Version: 2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SKY_CSV', plugin_dir_path( __FILE__ ) . 'noticias3.csv' );

add_action( 'admin_menu', function () {
    add_menu_page(
        'Importar Noticias',
        '⬆ Importar Noticias',
        'manage_options',
        'importar-noticias',
        'skynet_importar_page',
        'dashicons-upload',
        99
    );
} );

function skynet_importar_page() {

    if ( isset( $_POST['sky_importar'] ) && check_admin_referer( 'sky_importar_nonce' ) ) {
        $source = sanitize_text_field( $_POST['source_domain'] ?? 'skynetold.local' );
        $result = skynet_run_import( $source );
        echo '<div class="notice notice-success" style="padding:14px 16px;margin-bottom:20px;">
                <strong>✅ Importación completada:</strong> '
            . esc_html( $result['creadas'] )  . ' noticias creadas &nbsp;·&nbsp; '
            . esc_html( $result['imagenes'] ) . ' imágenes importadas &nbsp;·&nbsp; '
            . esc_html( $result['omitidas'] ) . ' omitidas (ya existían)
              </div>';
    }

    if ( isset( $_POST['sky_borrar'] ) && check_admin_referer( 'sky_importar_nonce' ) ) {
        $n = skynet_borrar_importadas();
        echo '<div class="notice notice-warning" style="padding:14px 16px;margin-bottom:20px;">
                🗑 ' . esc_html( $n ) . ' noticias importadas eliminadas.
              </div>';
    }

    $total_csv = 0;
    if ( file_exists( SKY_CSV ) ) {
        $f = fopen( SKY_CSV, 'r' );
        fgetcsv( $f );
        while ( fgetcsv( $f ) ) $total_csv++;
        fclose( $f );
    }

    $total_wp = (int) ( wp_count_posts( 'noticia' )->publish ?? 0 );
    ?>
    <div style="max-width:660px;margin:40px auto;font-family:sans-serif;">
        <h1 style="font-size:24px;margin-bottom:6px;">⬆ Importar Noticias con Imágenes</h1>
        <p style="color:#555;margin-bottom:28px;">
            CSV detectado: <strong><?= $total_csv ?> artículos</strong>
            &nbsp;·&nbsp; Noticias en WP: <strong><?= $total_wp ?></strong>
        </p>

        <?php if ( ! file_exists( SKY_CSV ) ): ?>
            <div style="background:#fff3cd;border:1px solid #ffc107;padding:16px;border-radius:8px;color:#856404;">
                ⚠ No se encuentra <code>noticias2.csv</code> en la carpeta del plugin.<br>
                Súbelo a <code>wp-content/plugins/importar-noticias/</code>
            </div>
        <?php else: ?>
            <form method="post">
                <?php wp_nonce_field( 'sky_importar_nonce' ); ?>

                <div style="margin-bottom:22px;">
                    <label style="display:block;font-weight:700;margin-bottom:6px;font-size:14px;">
                        Dominio origen de las imágenes:
                    </label>
                    <input type="text" name="source_domain" value="skynetold.local"
                        style="width:100%;padding:9px 12px;border:1px solid #ccc;
                               border-radius:7px;font-size:14px;">
                    <p style="color:#777;font-size:12px;margin-top:5px;">
                        Si estás en Local by Flywheel deja <code>skynetold.local</code>.<br>
                        Si las imágenes ya están en otro servidor, escribe ese dominio.
                    </p>
                </div>

                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <button name="sky_importar" value="1" type="submit"
                        style="padding:12px 28px;background:#2b90e3;color:#fff;border:none;
                               border-radius:8px;font-size:15px;font-weight:700;cursor:pointer;">
                        ▶ Importar <?= $total_csv ?> noticias
                    </button>
                    <button name="sky_borrar" value="1" type="submit"
                        onclick="return confirm('¿Seguro? Se borrarán todas las noticias importadas.')"
                        style="padding:12px 20px;background:#dc3545;color:#fff;border:none;
                               border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">
                        🗑 Deshacer importación
                    </button>
                </div>
            </form>

            <p style="margin-top:28px;color:#c00;font-size:13px;">
                ⚠ Ejecuta la importación <strong>una sola vez</strong>.
                Borra el plugin cuando termines.
            </p>
        <?php endif; ?>
    </div>
    <?php
}

// ─────────────────────────────────────────────────────────────────────────────
// IMPORTACIÓN PRINCIPAL
// ─────────────────────────────────────────────────────────────────────────────
function skynet_run_import( $source_domain = 'skynetold.local' ) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $creadas  = 0;
    $omitidas = 0;
    $imagenes = 0;

    $f       = fopen( SKY_CSV, 'r' );
    fgetcsv( $f ); // saltar cabecera: ID,post_title,post_date,post_status,post_content,blocks,featured_image_url,categories

    while ( ( $row = fgetcsv( $f ) ) !== false ) {
        if ( count( $row ) < 5 ) continue;

        $old_id         = trim( $row[0] ?? '' );
        $titulo         = trim( $row[1] ?? '' );
        $fecha          = trim( $row[2] ?? current_time( 'mysql' ) );
        $estado         = trim( $row[3] ?? 'draft' );
        $contenido_html = $row[4] ?? '';
        // columna 5 = blocks (vacía), columna 6 = featured_image_url, columna 7 = categories
        $img_url        = trim( $row[6] ?? '' );
        $categorias_raw = trim( $row[7] ?? 'Actualidad' );

        if ( empty( $titulo ) ) continue;

        // Evitar duplicados por título
        $existe = get_page_by_title( $titulo, OBJECT, 'noticia' );
        if ( $existe ) { $omitidas++; continue; }

        // Reemplazar URLs locales en el contenido
        $site_url       = get_site_url();
        $contenido_html = preg_replace(
            '#https?://' . preg_quote( $source_domain, '#' ) . '#i',
            $site_url,
            $contenido_html
        );

        $blocks = skynet_html_to_blocks( $contenido_html );

        $post_id = wp_insert_post( [
            'post_type'     => 'noticia',
            'post_title'    => $titulo,
            'post_status'   => ( $estado === 'publish' ) ? 'publish' : 'draft',
            'post_date'     => $fecha,
            'post_date_gmt' => get_gmt_from_date( $fecha ),
            'meta_input'    => [
                '_news_blocks'   => $blocks,
                '_imported_from' => (int) $old_id,
            ],
        ], true );

        if ( is_wp_error( $post_id ) ) continue;

        wp_set_object_terms( $post_id, 'Actualidad', 'categoria_noticia', false );

        // ── Imagen destacada ──────────────────────────────────────────
        if ( ! empty( $img_url ) ) {
            // Normalizar dominio de la URL de imagen al dominio origen configurado
            $img_url_fixed = preg_replace(
                '#https?://[^/]+#i',
                'http://' . $source_domain,
                $img_url
            );

            $attach_id = skynet_sideload_image( $img_url_fixed, $post_id, $titulo );

            if ( $attach_id && ! is_wp_error( $attach_id ) ) {
                set_post_thumbnail( $post_id, $attach_id );
                $imagenes++;
            }
        }

        $creadas++;
    }

    fclose( $f );
    return compact( 'creadas', 'omitidas', 'imagenes' );
}

// ─────────────────────────────────────────────────────────────────────────────
// DESCARGA Y SUBE UNA IMAGEN A LA BIBLIOTECA DE MEDIOS
// ─────────────────────────────────────────────────────────────────────────────
function skynet_sideload_image( $url, $post_id, $desc = '' ) {

    // Si ya existe en la biblioteca (mismo nombre de fichero), reutilizarla
    $filename = basename( parse_url( $url, PHP_URL_PATH ) );
    $existing = get_posts( [
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'meta_query'     => [[
            'key'     => '_wp_attached_file',
            'value'   => $filename,
            'compare' => 'LIKE',
        ]],
    ] );
    if ( ! empty( $existing ) ) {
        return $existing[0]->ID;
    }

    // Descargar a fichero temporal
    $tmp = download_url( $url, 30 );
    if ( is_wp_error( $tmp ) ) return $tmp;

    $file_array = [
        'name'     => $filename,
        'tmp_name' => $tmp,
    ];

    $attach_id = media_handle_sideload( $file_array, $post_id, $desc );

    if ( is_wp_error( $attach_id ) ) {
        @unlink( $tmp );
    }

    return $attach_id;
}

// ─────────────────────────────────────────────────────────────────────────────
// HTML → _news_blocks
// ─────────────────────────────────────────────────────────────────────────────
function skynet_html_to_blocks( $html ) {
    $html         = trim( $html );
    $texto_limpio = wp_strip_all_tags( $html );
    $parrafos     = array_values( array_filter(
        array_map( 'trim', preg_split( '/\n{2,}/', $texto_limpio ) )
    ) );

    $entradilla = $parrafos[0] ?? '';
    if ( mb_strlen( $entradilla ) > 300 ) {
        $entradilla = mb_substr( $entradilla, 0, 300 );
        $entradilla = mb_substr( $entradilla, 0, mb_strrpos( $entradilla, ' ' ) ) . '…';
    }

    return [
        [ 'type' => 'entradilla', 'content' => $entradilla ],
        [ 'type' => 'desarrollo', 'content' => $html ],
    ];
}

// ─────────────────────────────────────────────────────────────────────────────
// DESHACER: borra todas las noticias marcadas con _imported_from
// ─────────────────────────────────────────────────────────────────────────────
function skynet_borrar_importadas() {
    $posts = get_posts( [
        'post_type'      => 'noticia',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'meta_key'       => '_imported_from',
    ] );
    foreach ( $posts as $p ) {
        wp_delete_post( $p->ID, true );
    }
    return count( $posts );
}
