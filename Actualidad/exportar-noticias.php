<?php
/**
 * Plugin Name: Exportar Noticias Skynet
 * Description: Exporta todas las noticias a JSON. Borrar tras usar.
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', function () {
    add_menu_page(
        'Exportar Noticias',
        '⬇ Exportar Noticias',
        'manage_options',
        'exportar-noticias',
        'skynet_exportar_noticias_page',
        'dashicons-download',
        99
    );
} );

function skynet_exportar_noticias_page() {
    // Si se pulsa el botón de descarga → enviar JSON directamente
    if ( isset( $_GET['descargar'] ) && $_GET['descargar'] === '1' ) {
        skynet_descargar_json();
        exit;
    }

    // Contar cuántas hay
    $total = wp_count_posts( 'noticia' );
    $publicadas = intval( $total->publish ?? 0 );
    $borrador   = intval( $total->draft   ?? 0 );
    ?>
    <div style="max-width:600px;margin:40px auto;font-family:sans-serif;">
        <h1 style="font-size:24px;margin-bottom:8px;">⬇ Exportar Noticias</h1>
        <p style="color:#555;margin-bottom:24px;">
            Se exportarán <strong><?= $publicadas ?> publicadas</strong> y
            <strong><?= $borrador ?> borradores</strong> con todos sus bloques de contenido,
            categorías, imagen destacada y PDF adjunto.
        </p>

        <a href="<?= esc_url( admin_url( 'admin.php?page=exportar-noticias&descargar=1' ) ) ?>"
           style="display:inline-block;padding:12px 24px;background:#2b90e3;color:#fff;
                  border-radius:8px;text-decoration:none;font-weight:700;font-size:15px;">
            Descargar noticias.json
        </a>

        <p style="margin-top:32px;color:#e53;font-size:13px;">
            ⚠ Borra este plugin en cuanto termines (Plugins → Desactivar → Borrar).
        </p>
    </div>
    <?php
}

function skynet_descargar_json() {
    $posts = get_posts( [
        'post_type'      => 'noticia',
        'post_status'    => [ 'publish', 'draft' ],
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );

    $data = [];

    foreach ( $posts as $post ) {
        $blocks   = get_post_meta( $post->ID, '_news_blocks', true ) ?: [];
        $pdf_id   = get_post_meta( $post->ID, '_pdf_doc', true );
        $pdf_url  = $pdf_id ? wp_get_attachment_url( $pdf_id ) : null;

        $thumb_id  = get_post_thumbnail_id( $post->ID );
        $thumb_url = $thumb_id ? wp_get_attachment_url( $thumb_id ) : null;

        $terms      = get_the_terms( $post->ID, 'categoria_noticia' );
        $categorias = ( $terms && ! is_wp_error( $terms ) )
            ? wp_list_pluck( $terms, 'name' )
            : [];

        $tags_raw = get_the_tags( $post->ID );
        $etiquetas = ( $tags_raw && ! is_wp_error( $tags_raw ) )
            ? wp_list_pluck( $tags_raw, 'name' )
            : [];

        $data[] = [
            'id'           => $post->ID,
            'titulo'       => $post->post_title,
            'estado'       => $post->post_status,
            'fecha'        => $post->post_date,
            'categorias'   => $categorias,
            'etiquetas'    => $etiquetas,
            'imagen'       => $thumb_url,
            'pdf'          => $pdf_url,
            'blocks'       => $blocks,
        ];
    }

    $json = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename="noticias-skynet-' . date( 'Y-m-d' ) . '.json"' );
    header( 'Content-Length: ' . strlen( $json ) );
    header( 'Cache-Control: no-cache' );

    echo $json;
}
