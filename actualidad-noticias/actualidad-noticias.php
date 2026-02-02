<?php
/**
 * Plugin Name: Actualidad al Día - NOTICIAS CYBER EDITION
 * Description: Sistema de noticias con paleta cyan profesional, efectos de partículas y diseño de alto impacto.
 * Version: 5.0 CYBER PROFESSIONAL EDITION
 * Author: Skynet Systems
 */

if (!defined('ABSPATH'))
    exit;

class Actualidad_Noticias_Ultra
{

    public function __construct()
    {
        add_action('init', [$this, 'register_cpt']);
        add_action('add_meta_boxes', [$this, 'register_meta_boxes']);
        add_action('save_post_noticia', [$this, 'save_meta']);
        add_shortcode('actualidad_noticias', [$this, 'render_noticias']);
        add_action('admin_menu', [$this, 'register_admin_page']);
        add_action('wp_head', [$this, 'inject_frontend_styles']);
    }

    public function register_cpt()
    {
        register_post_type('noticia', [
            'labels' => ['name' => 'Noticias', 'singular_name' => 'Noticia'],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-megaphone',
            'supports' => ['title', 'editor', 'thumbnail'],
        ]);
    }

    public function register_admin_page()
    {
        add_submenu_page('edit.php?post_type=noticia', 'Editor Cyber', '✦ Crear Noticia Pro', 'manage_options', 'crear-noticia-pro', [$this, 'admin_ultra_editor']);
    }

    /* ===============================
     * EDITOR DE ADMINISTRACIÓN (CYBER)
     * =============================== */
    public function admin_ultra_editor()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            $this->handle_admin_create();
        ?>
        <style>
            :root {
                --accent-cyan: #00d4ff;
                --primary-blue: #0a2e4e;
                --secondary-blue: #126b97;
                --dark-navy: #061a2b;
                --glass-border: rgba(255, 255, 255, 0.2);
                --text-main: rgba(255, 255, 255, 0.85);
                --text-muted: #cdefff;
                --text-accent: #a5d8ff;
            }

            /* Forzar fondo admin con gradiente radial */
            #wpbody-content,
            #wpwrap,
            #wpcontent {
                background: radial-gradient(circle at top left, var(--primary-blue) 0%, var(--secondary-blue) 50%, var(--dark-navy) 100%) !important;
                color: var(--text-main) !important;
            }

            /* Contenedor principal */
            .ultra-wrap {
                font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
                padding: 60px 50px;
                max-width: 1400px;
                margin: auto;
                position: relative;
            }

            /* Partículas de fondo animadas */
            .ultra-wrap::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background:
                    radial-gradient(2px 2px at 20% 30%, var(--accent-cyan), transparent),
                    radial-gradient(2px 2px at 60% 70%, var(--accent-cyan), transparent),
                    radial-gradient(1px 1px at 50% 50%, var(--accent-cyan), transparent),
                    radial-gradient(1px 1px at 80% 10%, var(--accent-cyan), transparent);
                background-size: 200% 200%;
                opacity: 0.3;
                animation: particles 20s ease-in-out infinite;
                pointer-events: none;
                z-index: 0;
            }

            @keyframes particles {

                0%,
                100% {
                    background-position: 0% 0%, 100% 100%, 50% 50%, 0% 100%;
                }

                50% {
                    background-position: 100% 100%, 0% 0%, 25% 75%, 100% 0%;
                }
            }

            /* Header profesional con borde lateral cyan */
            .header-pro {
                margin-bottom: 60px;
                padding: 25px 0 30px 35px;
                border-left: 6px solid var(--accent-cyan);
                overflow: visible;
                background: linear-gradient(90deg, rgba(0, 212, 255, 0.1) 0%, transparent 100%);
                box-shadow: -4px 0 15px rgba(0, 212, 255, 0.3);
                position: relative;
                z-index: 1;
            }

            .header-pro h1 {
                font-size: 3.5rem;
                font-weight: 900;
                margin: 0;
                line-height: 1.15;
                display: inline-block;
                padding-bottom: 12px;
                overflow: visible;
                background: linear-gradient(to right, #fff, var(--text-accent));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                letter-spacing: -0.5px;
            }

            .header-pro p {
                margin-top: 12px;
                line-height: 1.6;
                color: var(--text-muted);
                font-size: 1.1rem;
                font-weight: 500;
            }

            /* Tag estilo cyber */
            .cyber-tag {
                display: inline-block;
                background: rgba(0, 212, 255, 0.15);
                color: var(--accent-cyan);
                padding: 8px 20px;
                border-radius: 50px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 2px;
                border: 1px solid var(--accent-cyan);
                font-size: 11px;
                margin-bottom: 20px;
                box-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
            }

            #wpbody-content * {
                overflow: visible;
            }

            /* Formulario Glass Effect Cyan */
            .glass-form {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 40px;
                padding: 45px;
                border-radius: 32px;
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
                border: 1px solid var(--glass-border);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(255, 255, 255, 0.02);
                position: relative;
                z-index: 1;
            }

            /* Borde superior con glow cyan */
            .glass-form::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100px;
                height: 4px;
                background: var(--accent-cyan);
                border-radius: 8px;
                box-shadow: 0 0 15px var(--accent-cyan);
            }

            /* Inputs profesionales */
            .input-group {
                margin-bottom: 28px;
            }

            .input-group label {
                font-size: 0.7rem;
                letter-spacing: 2.5px;
                color: var(--accent-cyan);
                font-weight: 800;
                margin-bottom: 14px;
                display: block;
                text-transform: uppercase;
            }

            .ultra-input {
                width: 100%;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 14px;
                padding: 16px 22px;
                color: var(--text-main);
                font-size: 1rem;
                transition: all .3s ease;
                font-family: inherit;
                box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.02);
            }

            .ultra-input:focus {
                border-color: var(--accent-cyan);
                box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.25), 0 0 20px rgba(0, 212, 255, 0.3);
                outline: none;
                background: rgba(255, 255, 255, 0.08);
            }

            .ultra-input::placeholder {
                color: rgba(255, 255, 255, 0.4);
            }

            /* Título grande */
            .ultra-title {
                font-size: 2.5rem !important;
                font-weight: 700 !important;
                background: transparent !important;
                border: none !important;
                border-bottom: 2px solid rgba(255, 255, 255, 0.2) !important;
                padding-bottom: 12px !important;
            }

            /* Texto del título: máxima legibilidad */
            /* Título principal – blanco suavizado */
            /* Título principal – mismo blanco que el contenido editorial */
            .ultra-title {
                color: rgba(255, 255, 255, 0.85) !important;
                -webkit-text-fill-color: rgba(255, 255, 255, 0.85);
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.18);
            }

            .ultra-title::placeholder {
                color: rgba(255, 255, 255, 0.4);
                font-weight: 400;
            }


            /* Textarea */
            textarea.ultra-input {
                resize: vertical;
                line-height: 1.6;
                font-family: 'Inter', sans-serif;
            }

            /* =========================
                                                                                                                                       SELECT CYBER STYLE
                                                                                                                                       ========================= */
            .select-wrapper {
                position: relative;
                width: 100%;
            }

            .select-wrapper select {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                z-index: 10;
                cursor: pointer;
                appearance: none;
                -webkit-appearance: none;
            }

            .select-btn {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 18px 24px;
                border-radius: 14px;
                cursor: pointer;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: var(--text-main);
                font-weight: 600;
                transition: all .3s ease;
                box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.02);
            }

            .select-wrapper:hover .select-btn {
                transform: translateY(-2px);
                border-color: var(--accent-cyan);
                box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
                background: rgba(255, 255, 255, 0.08);
            }

            .select-value {
                font-size: 1rem;
                color: var(--text-main);
            }

            .select-arrow {
                font-size: 1.2rem;
                opacity: 0.7;
                transition: transform .3s ease;
                color: var(--accent-cyan);
            }

            .select-wrapper:active .select-arrow {
                transform: rotate(180deg);
            }

            /* =========================
                                                                                                                                       FILE INPUT CYBER
                                                                                                                                       ========================= */
            .file-ultra input[type="file"] {
                display: none;
            }

            .file-btn {
                display: flex;
                flex-direction: column;
                gap: 8px;
                padding: 18px 24px;
                border-radius: 14px;
                cursor: pointer;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: var(--text-main);
                font-weight: 600;
                transition: all .3s ease;
                box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.02);
            }

            .file-btn:hover {
                transform: translateY(-2px);
                border-color: var(--accent-cyan);
                box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
                background: rgba(255, 255, 255, 0.08);
            }

            .file-btn small {
                font-size: 0.8rem;
                color: var(--text-muted);
                opacity: 0.85;
                font-weight: 400;
            }

            /* Botón Publicar – limpio, sin glow */
            .btn-publish {
                background: linear-gradient(135deg, var(--accent-cyan) 0%, #0099cc 100%);
                border: none;
                padding: 20px;
                border-radius: 14px;
                color: #ffffff;
                font-weight: 800;
                letter-spacing: 2px;
                text-transform: uppercase;
                cursor: pointer;
                transition: background 0.25s ease, transform 0.2s ease;
                margin-top: 20px;
                width: 100%;
                box-shadow: none;
                /* ❌ sin brillo */
                font-size: 0.95rem;
            }

            .btn-publish:hover {
                transform: translateY(-1px);
                background: linear-gradient(135deg, var(--accent-cyan) 0%, #0099cc 100%);

            }

            .btn-publish:active {
                transform: translateY(-1px);
            }

            /* Mensajes de éxito */
            .notice-success {
                background: rgba(0, 212, 255, 0.15) !important;
                border-left-color: var(--accent-cyan) !important;
                color: var(--text-main) !important;
            }

            /* Responsive */
            @media (max-width: 1200px) {
                .glass-form {
                    grid-template-columns: 1fr;
                }
            }

            /* Contenedores de previsualización (Nuevos) */
            .cyber-preview-area {
                margin-top: 15px;
                border-radius: 14px;
                overflow: hidden;
                background: rgba(0, 0, 0, 0.3);
                border: 1px dashed var(--accent-cyan);
                display: none;
                /* Se activa por JS */
                justify-content: center;
                align-items: center;
                min-height: 50px;
                padding: 10px;
            }

            .cyber-preview-area img {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                box-shadow: 0 0 15px rgba(0, 212, 255, 0.2);
            }

            .preview-active {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .pdf-item-preview {
                color: var(--accent-cyan);
                font-size: 0.85rem;
                background: rgba(0, 212, 255, 0.1);
                padding: 5px 12px;
                border-radius: 6px;
                width: 100%;
            }

            /* =========================
                                                                                                           AJUSTE VERTICAL SIDEBAR
                                                                                                           ========================= */
            .sidebar-editor {
                margin-top: 120px;
                /* baja el sidebar */
            }

            /* Quitar fondo blanco forzado por WP en inputs */
            input[type="text"].ultra-input,
            input.ultra-input {
                background: transparent !important;
                background-color: transparent !important;
                box-shadow: none !important;
            }

            /* Evitar autofill blanco/amarillo del navegador */
            input.ultra-input:-webkit-autofill,
            input.ultra-input:-webkit-autofill:hover,
            input.ultra-input:-webkit-autofill:focus {
                -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.05) inset !important;
                -webkit-text-fill-color: rgba(255, 255, 255, 0.85) !important;
            }

            /* TITULAR PRINCIPAL – tipografía editorial premium */
            input.ultra-input[name="titulo"] {
                font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
                font-size: 1.8rem !important;
                font-weight: 800 !important;
                letter-spacing: -0.03em;
                line-height: 1.2;

                color: rgba(255, 255, 255, 0.92) !important;
                -webkit-text-fill-color: rgba(255, 255, 255, 0.92);

                background: transparent !important;
                border: none !important;
                border-bottom: 2px solid rgba(0, 212, 255, 0.45) !important;

                padding: 8px 4px 14px 4px !important;
                text-shadow:
                    0 1px 1px rgba(0, 0, 0, 0.25),
                    0 0 18px rgba(0, 212, 255, 0.12);

                transition: border-color .3s ease, text-shadow .3s ease;
            }

            /* Placeholder del título igual al contenido editorial */
            input.ultra-input[name="titulo"]::placeholder {
                color: rgba(255, 255, 255, 0.4) !important;
                font-weight: 400 !important;
                letter-spacing: normal !important;
                text-shadow: none !important;
                -webkit-text-fill-color: rgba(255, 255, 255, 0.4) !important;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // 1. Lógica de Textos y Selects (Tu original)
                const selectEl = document.getElementById('segmento');
                const labelEl = document.querySelector('.select-value');
                if (selectEl && labelEl) {
                    selectEl.addEventListener('change', (e) => { labelEl.textContent = e.target.value; });
                }

                // 2. Función Maestra de Previsualización
                const setupPreview = (inputId, containerId, isImage) => {
                    const input = document.getElementById(inputId);
                    const container = document.getElementById(containerId);
                    if (!input || !container) return;

                    input.addEventListener('change', function () {
                        container.innerHTML = ''; // Limpiar
                        const smallTxt = this.nextElementSibling.querySelector('small');

                        if (this.files && this.files.length > 0) {
                            container.classList.add('preview-active');
                            if (smallTxt) smallTxt.textContent = this.files.length > 1 ? `${this.files.length} archivos` : this.files[0].name;

                            Array.from(this.files).forEach(file => {
                                if (isImage && file.type.startsWith('image/')) {
                                    const reader = new FileReader();
                                    reader.onload = e => {
                                        const img = document.createElement('img');
                                        img.src = e.target.result;
                                        container.appendChild(img);
                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    const info = document.createElement('div');
                                    info.className = 'pdf-item-preview';
                                    info.innerHTML = `📎 ${file.name}`;
                                    container.appendChild(info);
                                }
                            });
                        } else {
                            container.classList.remove('preview-active');
                            if (smallTxt) smallTxt.textContent = 'No se ha seleccionado ningún archivo';
                        }
                    });
                };

                // Ejecutar para los 3 campos
                setupPreview('media_cover', 'preview_cover_container', true);
                setupPreview('media_pdf', 'preview_pdf_container', false);
                setupPreview('media_pdfs_extra', 'preview_extra_container', false);
            });
        </script>
        <div class="ultra-wrap">

            <div class="header-pro">
                <h1>Nueva Publicación</h1>

                <p>Editor profesional con tecnología de vanguardia</p>
            </div>

            <form method="post" enctype="multipart/form-data" class="glass-form">
                <?php wp_nonce_field('crear_noticia_admin', 'crear_noticia_admin_nonce'); ?>

                <div class="main-editor">
                    <div class="input-group">
                        <label>TITULAR PRINCIPAL</label>
                        <input type="text" name="titulo" class="ultra-input" placeholder="¿Qué está pasando?" required>
                    </div>
                    <div class="input-group">
                        <label>CONTENIDO EDITORIAL</label>
                        <textarea name="contenido" class="ultra-input" style="min-height: 400px;"
                            placeholder="Desarrolle aquí el contenido completo de la noticia..." required></textarea>
                    </div>
                </div>

                <div class="sidebar-editor offset-down">
                    <div class="input-group select-ultra">
                        <label>CATEGORÍA</label>
                        <div class="select-wrapper">
                            <select name="categoria" id="segmento">
                                <option value="Tecnología">Tecnología</option>
                                <option value="Deportes">Deportes</option>
                                <option value="Cripto">Cripto</option>
                                <option value="Ciencia">Ciencia</option>
                            </select>

                            <div class="select-btn">
                                <span class="select-value">Tecnología</span>
                                <span class="select-arrow">▾</span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group file-ultra">
                        <label>DOCUMENTO PDF</label>
                        <div id="preview_pdf_container" class="cyber-preview-area"></div>

                        <input type="file" name="pdf_doc" id="media_pdf" accept="application/pdf">
                        <label for="media_pdf" class="file-btn">
                            <span>📄 Seleccionar PDF</span>
                            <small>No se ha seleccionado ningún archivo</small>
                        </label>
                    </div>

                    <div class="input-group file-ultra">
                        <label>IMAGEN DE PORTADA</label>
                        <div id="preview_cover_container" class="cyber-preview-area"></div>

                        <input type="file" name="imagen" id="media_cover" accept="image/*">
                        <label for="media_cover" class="file-btn">
                            <span>📁 Seleccionar archivo</span>
                            <small>No se ha seleccionado ningún archivo</small>
                        </label>
                    </div>
                    <button type="submit" class="btn-publish">Publicar Ahora ✦</button>
                </div>
            </form>
        </div>
        <?php
    }

    /* ===============================
     * FRONTEND: EXPERIENCIA VISUAL CYBER
     * =============================== */
    public function inject_frontend_styles()
    {
        ?>
        <style>
            .news-ultra-container {
                --u-accent: #00d4ff;
                --u-primary: #0a2e4e;
                --u-secondary: #126b97;
                --u-dark: #061a2b;
                background: radial-gradient(circle at top left, var(--u-primary) 0%, var(--u-secondary) 50%, var(--u-dark) 100%);
                padding: 100px 5%;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 40px;
                position: relative;
                overflow: hidden;
            }

            /* Efecto de partículas en el fondo */
            .news-ultra-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background:
                    radial-gradient(2px 2px at 20% 30%, var(--u-accent), transparent),
                    radial-gradient(2px 2px at 60% 70%, var(--u-accent), transparent),
                    radial-gradient(1px 1px at 50% 50%, var(--u-accent), transparent);
                background-size: 200% 200%;
                opacity: 0.2;
                animation: particleMove 20s ease-in-out infinite;
                pointer-events: none;
            }

            @keyframes particleMove {

                0%,
                100% {
                    background-position: 0% 0%, 100% 100%, 50% 50%;
                }

                50% {
                    background-position: 100% 100%, 0% 0%, 25% 75%;
                }
            }

            .news-ultra-container:hover .news-ultra-card:not(:hover) {
                opacity: 0.5;
                filter: grayscale(0.3);
                transform: scale(0.97);
            }

            .news-ultra-card {
                position: relative;
                height: 500px;
                border-radius: 24px;
                overflow: hidden;
                transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            }

            .news-ultra-card:hover {
                border-color: var(--u-accent);
                box-shadow: 0 20px 60px rgba(0, 212, 255, 0.4), 0 0 80px rgba(0, 212, 255, 0.2);
                transform: translateY(-8px);
            }

            .news-ultra-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 1s ease;
                filter: brightness(0.8);
            }

            .news-ultra-card:hover .news-ultra-img {
                transform: scale(1.08);
                filter: brightness(1);
            }

            .news-ultra-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top,
                        rgba(6, 26, 43, 0.95) 0%,
                        rgba(6, 26, 43, 0.7) 40%,
                        rgba(6, 26, 43, 0) 80%);
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                padding: 35px;
                transition: all 0.4s;
            }

            .u-badge {
                align-self: flex-start;
                background: rgba(0, 212, 255, 0.15);
                color: var(--u-accent);
                padding: 8px 18px;
                border-radius: 50px;
                font-size: 0.65rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                margin-bottom: 18px;
                border: 1px solid var(--u-accent);
                box-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
            }

            .news-ultra-card h3 {
                color: #ffffff;
                font-size: 1.75rem;
                font-weight: 700;
                line-height: 1.2;
                margin: 0 0 14px 0;
                transform: translateY(20px);
                opacity: 0;
                transition: all 0.5s 0.1s;
                letter-spacing: -0.3px;
            }

            .news-ultra-card p {
                color: #cdefff;
                font-size: 0.95rem;
                margin: 0;
                line-height: 1.6;
                transform: translateY(20px);
                opacity: 0;
                transition: all 0.5s 0.2s;
            }

            .news-ultra-card:hover h3,
            .news-ultra-card:hover p {
                transform: translateY(0);
                opacity: 1;
            }

            /* Glow superior cyan en hover */
            .news-ultra-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: var(--u-accent);
                transform: scaleX(0);
                transition: transform 0.4s ease;
                box-shadow: 0 0 15px var(--u-accent);
            }

            .news-ultra-card:hover::before {
                transform: scaleX(1);
            }

            /* Efecto de brillo radial */
            .news-ultra-card::after {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(0, 212, 255, 0.15) 0%, transparent 70%);
                opacity: 0;
                transition: opacity 0.5s;
                pointer-events: none;
            }

            .news-ultra-card:hover::after {
                opacity: 1;
            }
        </style>
        <?php
    }

    public function render_noticias()
    {
        $q = new WP_Query(['post_type' => 'noticia', 'posts_per_page' => 6]);
        ob_start();
        echo '<div class="news-ultra-container">';
        while ($q->have_posts()):
            $q->the_post();
            $cat = get_post_meta(get_the_ID(), '_categoria', true);
            $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if (!$img)
                $img = 'https://via.placeholder.com/800x600/0a2e4e/00d4ff?text=Noticia';
            ?>
            <div class="news-ultra-card">
                <img src="<?= esc_url($img) ?>" class="news-ultra-img" alt="<?= esc_attr(get_the_title()) ?>">
                <div class="news-ultra-overlay">
                    <span class="u-badge"><?= esc_html($cat ?: 'General') ?></span>
                    <h3><?= get_the_title() ?></h3>
                    <p><?= wp_trim_words(get_the_content(), 18) ?></p>

                    <?php
                    // PDF principal
                    $pdf_id = get_post_meta(get_the_ID(), '_pdf_doc', true);
                    if ($pdf_id):
                        $pdf_url = wp_get_attachment_url($pdf_id);
                        ?>
                        <a href="<?= esc_url($pdf_url) ?>" target="_blank"
                            style="margin-top:14px; display:block; color:#00d4ff; font-weight:700; text-decoration:none;">
                            📄 Ver documento principal
                        </a>
                    <?php endif; ?>

                    <?php
                    // PDFs adicionales (_pdf_docs)
                    $pdfs = get_post_meta(get_the_ID(), '_pdf_docs', true);
                    if (!empty($pdfs) && is_array($pdfs)):
                        foreach ($pdfs as $pdf_id_extra):
                            $pdf_url_extra = wp_get_attachment_url($pdf_id_extra);
                            if ($pdf_url_extra):
                                ?>
                                <a href="<?= esc_url($pdf_url_extra) ?>" target="_blank"
                                    style="margin-top:6px; display:block; color:#00d4ff; font-weight:500; text-decoration:none;">
                                    📄 Documento adicional
                                </a>
                                <?php
                            endif;
                        endforeach;
                    endif;
                    ?>

                </div>
            </div>
        <?php endwhile;
        echo '</div>';
        wp_reset_postdata();
        return ob_get_clean();
    }


    private function handle_admin_create()
    {
        if (!wp_verify_nonce($_POST['crear_noticia_admin_nonce'], 'crear_noticia_admin'))
            return;

        $post_id = wp_insert_post([
            'post_type' => 'noticia',
            'post_title' => sanitize_text_field($_POST['titulo']),
            'post_content' => wp_kses_post($_POST['contenido']),
            'post_status' => 'publish',
        ]);

        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, '_categoria', sanitize_text_field($_POST['categoria']));

            if (!empty($_FILES['imagen']['name'])) {
                require_once ABSPATH . 'wp-admin/includes/media.php';
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';
                $img_id = media_handle_upload('imagen', $post_id);
                if (!is_wp_error($img_id)) {
                    set_post_thumbnail($post_id, $img_id);
                }
            }

            echo '<div class="notice notice-success is-dismissible"><p><strong>✓ Publicación lanzada con éxito</strong></p></div>';
        }
        // Subir PDF único
        if (!empty($_FILES['pdf_doc']['name'])) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';

            $pdf_id = media_handle_upload('pdf_doc', $post_id);

            if (!is_wp_error($pdf_id)) {
                update_post_meta($post_id, '_pdf_doc', $pdf_id);
            }
        }

        // Subir múltiples PDFs adicionales
        if (!empty($_FILES['pdfs_adicionales']['name'][0])) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            $files = $_FILES['pdfs_adicionales'];
            $attachment_ids = [];

            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file = [
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    ];

                    $_FILES['upload_file'] = $file;
                    $attachment_id = media_handle_upload('upload_file', $post_id);

                    if (!is_wp_error($attachment_id)) {
                        $attachment_ids[] = $attachment_id;
                    }
                }
            }
            if (!empty($attachment_ids)) {
                update_post_meta($post_id, '_pdf_docs', $attachment_ids);
            }
        }

    }

    public function register_meta_boxes()
    {
        add_meta_box(
            'noticia_datos',
            '✦ Configuración Editorial',
            [$this, 'meta_box_html'],
            'noticia',
            'side'
        );

        add_meta_box(
            'noticia_pdfs',
            '📄 Documentos Adjuntos',
            [$this, 'meta_box_pdfs'],
            'noticia',
            'normal'
        );
    }
    public function meta_box_pdfs($post)
    {
        $pdfs = get_post_meta($post->ID, '_pdf_docs', true);
        if (!is_array($pdfs))
            $pdfs = [];
        ?>

        <div id="pdf-container">
            <?php foreach ($pdfs as $pdf_id):
                $url = wp_get_attachment_url($pdf_id);
                ?>
                <div class="pdf-item" style="margin-bottom:8px;">
                    📄 <a href="<?= esc_url($url) ?>" target="_blank">Documento</a>
                    <input type="hidden" name="pdf_docs[]" value="<?= esc_attr($pdf_id) ?>">
                    <button type="button" class="button remove-pdf">✕</button>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" class="button button-primary" id="add-pdf">
            ➕ Añadir PDF
        </button>

        <script>
            jQuery(function ($) {
                let frame;

                $('#add-pdf').on('click', function (e) {
                    e.preventDefault();

                    if (frame) { frame.open(); return; }

                    frame = wp.media({
                        title: 'Seleccionar PDFs',
                        button: { text: 'Adjuntar PDFs' },
                        multiple: true,
                        library: { type: 'application/pdf' }
                    });

                    frame.on('select', function () {
                        frame.state().get('selection').each(function (file) {
                            const id = file.id;
                            const url = file.attributes.url;

                            $('#pdf-container').append(`
                                <div class="pdf-item" style="margin-bottom:8px;">
                                    📄 <a href="${url}" target="_blank">Documento</a>
                                    <input type="hidden" name="pdf_docs[]" value="${id}">
                                    <button type="button" class="button remove-pdf">✕</button>
                                </div>
                            `);
                        });
                    });

                    frame.open();
                });

                $(document).on('click', '.remove-pdf', function () {
                    $(this).closest('.pdf-item').remove();
                });
            });
        </script>

        <?php
    }


    public function meta_box_html($post)
    {
        $categoria = get_post_meta($post->ID, '_categoria', true);
        ?>
        <select name="categoria" style="width:100%; border-radius: 5px; padding: 8px;">
            <?php foreach (['Tecnología', 'Deportes', 'Cripto', 'Ciencia'] as $cat): ?>
                <option value="<?= $cat ?>" <?= selected($categoria, $cat) ?>><?= $cat ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function save_meta($post_id)
    {

        if (isset($_POST['categoria'])) {
            update_post_meta($post_id, '_categoria', sanitize_text_field($_POST['categoria']));
        }

        if (isset($_POST['pdf_docs'])) {
            $pdfs = array_map('intval', $_POST['pdf_docs']);
            update_post_meta($post_id, '_pdf_docs', $pdfs);
        } else {
            delete_post_meta($post_id, '_pdf_docs');
        }
    }

}

new Actualidad_Noticias_Ultra();