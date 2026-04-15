<?php
/**
 * Template Name: Single Noticia
 * Single post template for 'noticia' custom post type.
 * Renders _news_blocks meta data with the Cyber/blue design.
 */

if (!defined('ABSPATH')) exit;

get_header();

while (have_posts()):
    the_post();

    $blocks         = get_post_meta(get_the_ID(), '_news_blocks', true) ?: [];
    $pdf_id         = get_post_meta(get_the_ID(), '_pdf_doc', true);
    $pdf_url        = $pdf_id ? wp_get_attachment_url($pdf_id) : null;
    $thumb_url      = get_the_post_thumbnail_url(get_the_ID(), 'full');

    $terms          = get_the_terms(get_the_ID(), 'categoria_noticia');
    $categoria      = ($terms && !is_wp_error($terms))
        ? implode(', ', wp_list_pluck($terms, 'name'))
        : 'General';
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    :root {
        --accent:      #2b90e3;
        --accent-dark: #1a6bb8;
        --accent-light:#3bc6e7;
    }

    /* ── Background ─────────────────────────────── */
    .news-single-page {
        min-height: 100vh;
        background: radial-gradient(circle at top, #0f3556 0%, #0b243a 45%, #061a2b 100%);
        color: #fff;
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        padding: 60px 24px 100px;
        margin-top: 24px;
    }

    /* ── Container ──────────────────────────────── */
    .news-single-inner {
        max-width: 760px;
        margin: 0 auto;
    }

    /* ── Back link ──────────────────────────────── */
    .news-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(224, 242, 255, .6);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 32px;
        transition: color .2s ease;
    }
    .news-back:hover { color: var(--accent-light); }

    /* ── Category badge ─────────────────────────── */
    .news-single-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 6px 12px;
        border-radius: 8px;
        margin-bottom: 14px;
        background: rgba(59, 130, 246, .15);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, .3);
    }

    /* ── Title ──────────────────────────────────── */
    .news-single h1 {
        font-size: clamp(32px, 5.5vw, 52px);
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: -2px;
        margin: 0 0 28px 0;
        background: linear-gradient(135deg, #ffffff 0%, #e6f4ff 60%, #cce9ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
        width: 100%;
    }

    .news-single h1::after {
        content: "";
        display: block;
        width: 100%;
        height: 4px;
        margin-top: 22px;
        border-radius: 4px;
        background: linear-gradient(90deg, var(--accent), var(--accent-light));
        opacity: .9;
    }

    /* ── Date/meta ──────────────────────────────── */
    .news-single-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 13px;
        color: rgba(224, 242, 255, .55);
        margin-bottom: 36px;
    }

    /* ── Featured image ─────────────────────────── */
    .news-single-img {
        width: 100%;
        height: auto;
        border-radius: 24px;
        margin: 0 0 50px;
        border: 1px solid rgba(255, 255, 255, .12);
        box-shadow: 0 12px 40px rgba(0,0,0,.35);
        display: block;
    }

    /* ── Article glass card ─────────────────────── */
    .news-single-content {
        background: linear-gradient(135deg, rgba(18,107,151,.45), rgba(10,46,78,.35));
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 24px;
        padding: 32px 34px 36px;
    }

    /* ── Sections ───────────────────────────────── */
    .news-section { margin-bottom: 40px; }
    .news-section:last-child { margin-bottom: 0; }

    .news-section-title {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        color: var(--accent-light);
        margin-bottom: 14px;
        position: relative;
    }
    .news-section-title::after {
        content: "";
        display: block;
        width: 28px;
        height: 2px;
        margin-top: 10px;
        background: linear-gradient(90deg, var(--accent), var(--accent-light));
        border-radius: 3px;
        opacity: .9;
    }

    /* ── Lead / Entradilla ──────────────────────── */
    .news-section-lead {
        font-size: 18px;
        line-height: 1.7;
        font-weight: 600;
        color: #ffffff;
        margin: 0;
        padding-bottom: 40px;
        border-bottom: 1px solid rgba(255,255,255,.12);
    }

    /* ── Body paragraphs ────────────────────────── */
    .news-section-body p {
        font-size: 16px;
        line-height: 1.65;
        color: rgba(224,242,255,.75);
        margin: 0 0 18px 0;
    }
    .news-section-body p:last-child { margin-bottom: 0; }

    /* ── Headings inside body ───────────────────── */
    .news-section-body h2 {
        font-size: 22px;
        font-weight: 800;
        color: #ffffff;
        margin: 32px 0 14px;
        line-height: 1.25;
    }
    .news-section-body h3 {
        font-size: 18px;
        font-weight: 700;
        color: rgba(224,242,255,.9);
        margin: 26px 0 12px;
        line-height: 1.3;
    }

    /* ── Strong / bold ──────────────────────────── */
    .news-section-body strong,
    .news-section-body b {
        color: #ffffff;
        font-weight: 700;
    }

    /* ── Lists ──────────────────────────────────── */
    .news-section-body ul,
    .news-section-body ol {
        margin: 0 0 18px 0;
        padding-left: 24px;
    }
    .news-section-body li {
        font-size: 16px;
        line-height: 1.65;
        color: rgba(224,242,255,.75);
        margin-bottom: 8px;
    }
    .news-section-body li strong { color: #ffffff; }

    /* ── Links ──────────────────────────────────── */
    .news-section-body a {
        color: var(--accent-light);
        text-decoration: underline;
        text-decoration-color: rgba(59,198,231,.35);
        text-underline-offset: 3px;
        transition: color .2s ease, text-decoration-color .2s ease;
    }
    .news-section-body a:hover {
        color: #ffffff;
        text-decoration-color: rgba(255,255,255,.5);
    }

    /* ── Conclusion ─────────────────────────────── */
    .news-section-conclusion {
        padding: 28px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(59,130,246,.12), rgba(59,130,246,.04));
        border: 1px solid rgba(59,130,246,.25);
        margin-top: 8px;
    }
    .news-section-conclusion .news-section-title { margin-bottom: 14px; }
    .news-section-conclusion p {
        color: #ffffff;
        font-size: 15px;
        line-height: 1.55;
        margin: 0;
    }

    /* ── Inline images ──────────────────────────── */
    .news-figure {
        margin: 56px 0;
    }
    .news-figure img {
        width: 100%;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,.12);
        box-shadow: 0 10px 36px rgba(0,0,0,.35);
        display: block;
    }

    /* ── PDF link ───────────────────────────────── */
    .news-pdf-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: 36px;
        padding: 14px 22px;
        border-radius: 14px;
        background: rgba(0,212,255,.12);
        border: 1px solid rgba(0,212,255,.4);
        color: #00d4ff;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
        transition: all .25s ease;
    }
    .news-pdf-link:hover {
        background: rgba(0,212,255,.22);
        box-shadow: 0 0 20px rgba(0,212,255,.35);
    }

    /* ── Responsive ─────────────────────────────── */
    @media (max-width: 768px) {
        .news-single-page  { padding: 36px 16px 80px; }
        .news-single h1    { font-size: 32px; letter-spacing: -1px; }
        .news-single-content { padding: 24px 20px 28px; }
        .news-section-conclusion { padding: 22px; }
        .news-figure { margin: 36px 0; }
    }
</style>

<div class="news-single-page">
    <div class="news-single-inner">

        <!-- Back -->
        <a href="<?= esc_url(get_post_type_archive_link('noticia') ?: home_url('/actualidad')) ?>" class="news-back">
            ← Volver a Actualidad
        </a>

        <div class="news-single">
            <!-- Category badge -->
            <span class="news-single-badge"><?= esc_html($categoria) ?></span>

            <!-- Title -->
            <h1><?= esc_html(get_the_title()) ?></h1>

            <!-- Meta -->
            <div class="news-single-meta">
                <span>📅 <?= esc_html(get_the_date()) ?></span>
                <span>🕐 <?= esc_html(get_the_time()) ?></span>
            </div>

            <!-- Featured image -->
            <?php if ($thumb_url): ?>
                <img src="<?= esc_url($thumb_url) ?>" alt="<?= esc_attr(get_the_title()) ?>" class="news-single-img">
            <?php endif; ?>

            <!-- Blocks -->
            <?php if (!empty($blocks)): ?>
                <div class="news-single-content">
                    <?php foreach ($blocks as $block):
                        $type    = $block['type']    ?? '';
                        $content = $block['content'] ?? '';

                        if ($type === 'image'):
                            // Inline image block
                            ?>
                            <figure class="news-figure">
                                <img src="<?= esc_url($content) ?>" alt="">
                            </figure>

                        <?php elseif ($type === 'entradilla'): ?>
                            <div class="news-section">
                                <p class="news-section-lead"><?= nl2br(esc_html($content)) ?></p>
                            </div>

                        <?php elseif ($type === 'desarrollo'): ?>
                            <div class="news-section">
                                <h2 class="news-section-title">Análisis</h2>
                                <div class="news-section-body">
                                    <?= wp_kses_post($content) ?>
                                </div>
                            </div>

                        <?php elseif ($type === 'conclusion'): ?>
                            <div class="news-section news-section-conclusion">
                                <h2 class="news-section-title">Conclusión</h2>
                                <div class="news-section-body">
                                    <?= wp_kses_post($content) ?>
                                </div>
                            </div>

                        <?php elseif ($content): ?>
                            <!-- Any extra block type -->
                            <div class="news-section">
                                <h2 class="news-section-title"><?= esc_html($type) ?></h2>
                                <div class="news-section-body">
                                    <?= wp_kses_post($content) ?>
                                </div>
                            </div>

                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- PDF -->
            <?php if ($pdf_url): ?>
                <a href="<?= esc_url($pdf_url) ?>" target="_blank" rel="noopener" class="news-pdf-link">
                    📄 Ver documento adjunto
                </a>
            <?php endif; ?>

        </div><!-- .news-single -->
    </div><!-- .news-single-inner -->
</div><!-- .news-single-page -->

<?php
endwhile;

get_footer();
