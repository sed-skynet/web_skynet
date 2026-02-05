<?php get_header(); ?>

<main class="news-single">
    <canvas id="news-particles"></canvas>
    <div class="container">

        <span class="u-badge">
            <?= esc_html(get_post_meta(get_the_ID(), '_categoria', true)) ?>
        </span>
        <h1><?php the_title(); ?></h1>

        <?php if (has_post_thumbnail()): ?>
            <?php the_post_thumbnail('large', ['class' => 'news-single-img']); ?>
        <?php endif; ?>

        <article class="news-single-content">
            <?php
            $blocks = get_post_meta(get_the_ID(), '_news_blocks', true);

            if (!empty($blocks) && is_array($blocks)) {
                foreach ($blocks as $block) {
                    switch ($block['type']) {
                        case 'entradilla':
                            echo '<section class="news-section news-section-lead">';
                            echo '<p class="news-lead">' . wp_kses_post($block['content']) . '</p>';
                            echo '</section>';
                            break;

                        case 'desarrollo':
                            echo '<section class="news-section news-section-body">';
                            echo '<h3 class="news-section-title">Análisis</h3>';
                            echo '<p>' . wp_kses_post($block['content']) . '</p>';
                            echo '</section>';
                            break;

                        case 'conclusion':
                            echo '<section class="news-section news-section-conclusion">';
                            echo '<h3 class="news-section-title">Conclusión</h3>';
                            echo '<p>' . wp_kses_post($block['content']) . '</p>';
                            echo '</section>';
                            break;
                        case 'image':
                            echo '<figure class="news-figure">';
                            echo '<img src="' . esc_url($block['content']) . '" alt="">';
                            echo '</figure>';
                            break;
                    }
                }
            }
            ?>
        </article>

    </div>
    <style>
        /* ===========================
   VARIABLES BASE (IGUAL QUE LISTADO)
=========================== */
        :root {
            --accent: #2b90e3;
            --accent-dark: #1a6bb8;
            --accent-light: #3bc6e7;
            --bg-dark: #0b243a;
            --text-main: #ffffff;
            --text-soft: rgba(224, 242, 255, 0.75);
            --glass-bg: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.12);
            font-family: 'Inter', ui-sans-serif, system-ui;
        }

        /* ===========================
   CONTEXTO GENERAL
=========================== */
        body {
            background: radial-gradient(circle at top,
                    #0f3556 0%,
                    #0b243a 45%,
                    #061a2b 100%);
            color: var(--text-main);
        }

        main.news-single {
            position: relative;
            z-index: 1;
            padding-top: 160px;
        }

        /* ===========================
   CONTENEDOR GLASS EDITORIAL
=========================== */

        .news-single .container {
            max-width: 100%;
            padding: 120px 20px;
        }

        .news-single article,
        .news-single h1,
        .news-single .u-badge,
        .news-single-img,
        .news-back {
            max-width: 760px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ===========================
   BADGE CATEGORÍA
=========================== */

        .u-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 6px 12px;
            border-radius: 8px;
            margin-bottom: 24px;
            background: rgba(59, 130, 246, .15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, .3);
        }

        /* ===========================
   TÍTULO
=========================== */

        .news-single h1 {
            font-size: clamp(44px, 6.5vw, 52px);
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -2px;
            margin-bottom: 28px;

            background: linear-gradient(135deg,
                    #ffffff 0%,
                    #e6f4ff 60%,
                    #cce9ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .news-single h1::after {
            content: "";
            display: block;
            width: 100%;
            /* 🔥 TODO el ancho del título */
            height: 4px;
            margin-top: 22px;
            border-radius: 4px;
            background: linear-gradient(90deg,
                    var(--accent),
                    var(--accent-light));
            opacity: 0.9;
        }

        .news-single h1 {
            display: inline-block;
            /* CLAVE */
            width: 100%;
        }

        .u-badge {
            margin-bottom: 14px;
        }

        @media (max-width: 768px) {
            .news-single h1 {
                font-size: 34px;
                line-height: 1.15;
                letter-spacing: -1px;
            }
        }

        /* ===========================
   IMAGEN DESTACADA
=========================== */

        .news-single-img {
            width: 100%;
            height: auto;
            border-radius: 24px;
            margin: 40px auto 110px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 12px 40px rgba(0, 0, 0, .35);
        }

        /* ===========================
   CONTENIDO EDITORIAL
=========================== */

        .news-single-content {
            background: linear-gradient(135deg,
                    rgba(18, 107, 151, 0.45),
                    rgba(10, 46, 78, 0.35));
            backdrop-filter: blur(18px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 32px 34px 36px;
            margin-left: -24px !important;
            /* 👈 acerca el contenido al título */
            /* ↓ más compacto */
        }

        /* Párrafos */
        .news-single-content p {
            font-size: 16px;
            /* 👈 +1px exacto */
            line-height: 1.6;
            /* un poco más aire */
            /* más compacto */
            color: var(--text-soft);
            margin-bottom: 18px;
            /* menos aire */
        }

        /* ===========================
   ENTRADILLA
=========================== */

        .news-lead {
            font-size: 18px;
            /* 👈 +1px, suficiente */
            line-height: 1.7;
            /* un poco más aire */
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 40px;
        }

        /* ===========================
   IMÁGENES INTERNAS
=========================== */

        .news-figure {
            margin: 56px 0;
        }

        .news-figure img {
            width: 100%;
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 36px rgba(0, 0, 0, .35);
        }

        /* ===========================
   BOTÓN VOLVER
=========================== */

        .news-back {
            display: inline-block;
            margin-top: 60px;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 600;
            text-decoration: none;
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all .25s ease;
        }

        .news-back:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* ===========================
   LIMPIEZA DE ASIDES
=========================== */

        .news-single aside,
        .news-single .sidebar,
        .news-single .widget-area {
            display: none !important;
        }

        /* ===========================
   MOBILE
=========================== */

        @media (max-width: 768px) {
            .news-single-content {
                padding: 32px 24px 40px;
            }

            .news-single h1 {
                font-size: 32px;
            }

            .news-lead {
                font-size: 18px;
            }
        }

        /* ===========================
   HERO GRID SINGLE NOTICIA
=========================== */
        html,
        body {

            background: linear-gradient(180deg,
                    #0f3556 0%,
                    #0b243a 50%,
                    #061a2b 100%) !important;
        }

        .news-single .container {
            display: grid;
            background: transparent !important;
            grid-template-columns: 1.1fr 0.9fr;
            grid-template-areas:
                "badge content"
                "title content"
                "image content";
            column-gap: 40px;
            align-items: start;
            margin-top: -210px;
        }

        .news-back-top {
            grid-column: 2;
            justify-self: start;
            margin-bottom: 12px;
            padding: 0;
            background: none;
            border: none;
            color: rgba(224, 242, 255, 0.75);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .news-single-content {
            margin-top: 16px !important;
        }

        .news-back-top::before {
            content: "←";
            opacity: 0.7;
        }

        .news-back-top:hover {
            color: #ffffff;
        }

        .u-badge {
            margin-bottom: 6px;
        }

        .news-back-top {
            grid-area: back;
            margin: 0 0 18px;
            padding: 0;
            background: none;
            border: none;
            color: rgba(224, 242, 255, 0.75);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: fit-content;
        }

        .news-back-top::before {
            content: "←";
            font-size: 14px;
            opacity: 0.7;
        }

        .news-back-top:hover {
            color: #ffffff;
        }



        /* Asignación de áreas */
        .u-badge {
            grid-area: badge;
        }

        .news-single h1 {
            grid-area: title;
        }

        .news-single-img {
            grid-area: image;
            margin-top: 24px;
            z-index: 2;
        }

        .news-single-content {
            grid-area: content;
            margin-top: 64px !important;
            /* 👈 AQUÍ BAJA */
        }


        .news-back {
            grid-area: back;
            margin-top: 40px;
        }

        /* Ajustes visuales */
        .news-single h1 {
            margin-bottom: 32px;
        }

        .news-single-content {
            position: sticky;
        }

        /* ===========================
   SECCIONES EDITORIALES
=========================== */

        .news-section {
            margin-bottom: 40px;
        }

        /* Título de sección */
        .news-section-title {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--accent-light);
            margin-bottom: 14px;
            position: relative;
        }

        /* Línea decorativa bajo el título */
        .news-section-title::after {
            content: "";
            display: block;
            width: 28px;
            /* antes 48 */
            height: 2px;
            margin-top: 10px;
            background: linear-gradient(90deg,
                    var(--accent),
                    var(--accent-light));
            border-radius: 3px;
            opacity: 0.9;
        }

        /* Entradilla más marcada */
        .news-section-lead {
            padding-bottom: 40px;
            /* antes 40 */
            margin-bottom: 48px;
            /* antes 48 */
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        /* Desarrollo */
        .news-section-body p {
            font-size: 15px;
        }

        /* Conclusión destacada */
        .news-section-conclusion {
            padding: 28px;
            border-radius: 20px;
            background: linear-gradient(135deg,
                    rgba(59, 130, 246, 0.12),
                    rgba(59, 130, 246, 0.04));
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        @media (max-width: 768px) {
            .news-section {
                margin-bottom: 40px;
            }

            .news-section-conclusion {
                padding: 22px;
            }
        }

        .news-section-conclusion p {
            color: #ffffff;
            font-size: 15px;
            line-height: 1.55;
        }

        @media (max-width: 1024px) {
            .news-single .container {
                grid-template-columns: 1fr;
                grid-template-areas:
                    "badge"
                    "title"
                    "image"
                    "content"
                    "back";
                row-gap: 32px;
            }

            .news-single-content {
                position: relative;
                top: auto;
            }
        }

        #news-particles {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            opacity: 0.65;
            /* 👈 controla intensidad */
        }

        /* Aseguramos que el contenido va por encima */
        main.news-single {
            position: relative;
            z-index: 2;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const canvas = document.getElementById("news-particles");
            if (!canvas) return;

            const ctx = canvas.getContext("2d");
            let w, h, dpr;
            let particles = [];

            const COUNT = 120;          // 🔥 más densidad
            const LINK_DIST = 160;      // 🔥 líneas más largas

            function resize() {
                dpr = window.devicePixelRatio || 1;
                w = window.innerWidth;
                h = window.innerHeight;
                canvas.width = w * dpr;
                canvas.height = h * dpr;
                canvas.style.width = w + "px";
                canvas.style.height = h + "px";
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            }

            class Particle {
                constructor() {
                    this.reset();
                }

                reset() {
                    this.x = Math.random() * w;
                    this.y = Math.random() * h;
                    this.r = Math.random() * 2.4 + 1.2; // 🔥 MÁS GRANDES
                    this.vx = (Math.random() - 0.5) * 0.45;
                    this.vy = (Math.random() - 0.5) * 0.45;
                    this.alpha = Math.random() * 0.5 + 0.4; // 🔥 MÁS OPACAS
                }

                update() {
                    this.x += this.vx;
                    this.y += this.vy;
                    if (this.x < -50 || this.x > w + 50 || this.y < -50 || this.y > h + 50) {
                        this.reset();
                    }
                }

                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(160, 220, 255, ${this.alpha})`;
                    ctx.shadowColor = "rgba(80,200,255,0.6)";
                    ctx.shadowBlur = 8;
                    ctx.fill();
                    ctx.shadowBlur = 0;
                }
            }

            function init() {
                particles = [];
                for (let i = 0; i < COUNT; i++) {
                    particles.push(new Particle());
                }
            }

            function drawLines() {
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < LINK_DIST) {
                            ctx.strokeStyle = `rgba(90,190,255, ${0.15 * (1 - dist / LINK_DIST)})`;
                            ctx.lineWidth = 1;
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }
            }

            function animate() {
                ctx.clearRect(0, 0, w, h);
                particles.forEach(p => {
                    p.update();
                    p.draw();
                });
                drawLines();
                requestAnimationFrame(animate);
            }

            resize();
            init();
            animate();
            window.addEventListener("resize", resize);
        });
    </script>


</main>

<?php get_footer(); ?>