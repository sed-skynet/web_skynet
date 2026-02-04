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
        add_action('after_setup_theme', [$this, 'enable_thumbnails']);
        add_action('init', [$this, 'register_cpt']);
        add_action('add_meta_boxes', [$this, 'register_meta_boxes']);
        add_action('save_post_noticia', [$this, 'save_meta']);
        add_shortcode('actualidad_noticias', [$this, 'render_noticias']);
        add_action('admin_menu', [$this, 'register_admin_page']);
        add_action('wp_head', [$this, 'inject_frontend_styles']);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        add_action('admin_head', [$this, 'inject_admin_block_styles']);
    }

    public function enable_thumbnails()
    {
        add_theme_support('post-thumbnails');
        add_theme_support('post-thumbnails', ['noticia']);
    }
    // 1) En el constructor (dentro de __construct) añade:
// add_action('admin_enqueue_scripts', [$this, 'admin_assets']);

    // 2) Añade este método dentro de la clase Actualidad_Noticias_Ultra:
    public function admin_assets()
    {
        // Solo en nuestra página de editor personalizado
        if (!isset($_GET['page']) || $_GET['page'] !== 'crear-noticia-pro') {
            return;
        }

        // URL base del plugin (asegúrate de que los archivos woff2 estén en assets/fonts/)
        $base = plugin_dir_url(__FILE__) . 'assets/fonts/';

        // Registrar handle vacío para añadir CSS inline
        wp_register_style('actualidad-admin-inline', false);
        wp_enqueue_style('actualidad-admin-inline');

        $css = "
    /* Self-hosted Inter (WOFF2) */
    @font-face {
        font-family: 'InterCustom';
        src: url('{$base}Inter-Regular.woff2') format('woff2');
        font-weight: 400;
        font-style: normal;
        font-display: swap;
    }
    @font-face {
        font-family: 'InterCustom';
        src: url('{$base}Inter-SemiBold.woff2') format('woff2');
        font-weight: 600;
        font-style: normal;
        font-display: swap;
    }

    /* Forzar Inter en nuestra UI admin y mejorar suavizado */
    #wpbody-content .ultra-wrap,
    #wpbody-content .ultra-wrap * ,
    #wpbody-content .ultra-wrap input,
    #wpbody-content .ultra-wrap textarea,
    #wpbody-content .ultra-wrap select,
    #wpbody-content .ultra-wrap .ultra-input {
        font-family: 'InterCustom', 'Segoe UI', system-ui, -apple-system, 'Helvetica Neue', Arial, sans-serif !important;
        -webkit-font-smoothing: antialiased !important;
        -moz-osx-font-smoothing: grayscale !important;
        text-rendering: optimizeLegibility !important;
        font-weight: 600 !important; /* más legible en Windows */
        text-shadow: none !important;
    }

    /* Quitar efectos que rompen el antialiasing sobre inputs */
    #wpbody-content .glass-form {
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
    }

    /* Forzar color y eliminar sombras internas en los inputs */
    #wpbody-content .ultra-wrap input.ultra-input,
    #wpbody-content .ultra-wrap textarea.ultra-input {
        text-shadow: none !important;
        box-shadow: none !important;
    }
    ";

        wp_add_inline_style('actualidad-admin-inline', $css);
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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            :root {
                --accent-cyan: #00d4ff;
                --primary-blue: #0a2e4e;
                --secondary-blue: #126b97;
                --dark-navy: #061a2b;
                --glass-border: rgba(255, 255, 255, 0.2);
                --text-main: #ffffff;
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
                font-size: 2.7rem !important;
                font-weight: 700 !important;
                letter-spacing: -0.6px;
                background: transparent !important;
                border: none !important;
                border-bottom: 2px solid rgba(255, 255, 255, 0.2) !important;
                padding-bottom: 12px !important;
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

            /* Botón Publicar Cyber Glow */
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
                transition: all .35s ease;
                margin-top: 20px;
                width: 100%;
                box-shadow: 0 8px 20px rgba(0, 212, 255, 0.4), 0 0 30px rgba(0, 212, 255, 0.2);
                font-size: 0.95rem;
            }

            .btn-publish:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 35px rgba(0, 212, 255, 0.5), 0 0 50px rgba(0, 212, 255, 0.3);
                background: linear-gradient(135deg, #00e5ff 0%, var(--accent-cyan) 100%);
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

            :root {
                --premium-white: #F7FBFF;
                /* blanco premium */
            }

            /* Forzar blanco premium en todo el UI del editor personalizado */
            #wpbody-content .ultra-wrap,
            #wpbody-content .ultra-wrap *,
            #wpbody-content .ultra-wrap input,
            #wpbody-content .ultra-wrap textarea,
            #wpbody-content .ultra-wrap select,
            #wpbody-content .ultra-wrap .ultra-input {
                color: var(--premium-white) !important;
                -webkit-text-fill-color: var(--premium-white) !important;
                /* for WebKit-based browsers */
                caret-color: var(--premium-white) !important;
                -webkit-font-smoothing: antialiased !important;
                -moz-osx-font-smoothing: grayscale !important;
            }

            /* Placeholder ligeramente atenuado pero premium */
            #wpbody-content .ultra-wrap input::placeholder,
            #wpbody-content .ultra-wrap textarea::placeholder {
                color: rgba(247, 251, 255, 0.65) !important;
            }

            /* Asegurar contraste cuando el input está enfocado */
            #wpbody-content .ultra-wrap input.ultra-input:focus,
            #wpbody-content .ultra-wrap textarea.ultra-input:focus {
                color: var(--premium-white) !important;
                -webkit-text-fill-color: var(--premium-white) !important;
            }

            /* Opcional: añadir un ligero sombreado al texto para más presencia sobre fondos blur/gradiente */
            #wpbody-content .ultra-wrap input,
            #wpbody-content .ultra-wrap textarea {
                text-shadow: 0 1px 0 rgba(0, 0, 0, 0.18) !important;
            }

            /* Placeholder difuminado y sutil para el input .ultra-title */
            .ultra-title::placeholder,
            .ultra-title::-webkit-input-placeholder,
            .ultra-title::-moz-placeholder,
            .ultra-title:-ms-input-placeholder,
            .ultra-title::-ms-input-placeholder {
                color: rgba(247, 251, 255, 0.32) !important;
                /* muy atenuado */
                font-weight: 400 !important;
                /* más ligero que el texto real */
                font-style: italic !important;
                /* opcional: hacer itálica para indicar "placeholder" */
                text-shadow: none !important;
                /* quitar sombras que aumentan contraste */
                opacity: 1 !important;
                /* usar color RGBA, no opacity global (compatibilidad) */
                transition: color .18s ease-in-out !important;
            }

            /* Aún más sutil cuando el campo pierde foco (opcional) */
            .ultra-title:not(:focus)::placeholder {
                color: rgba(247, 251, 255, 0.24) !important;
            }

            /* Asegura que el input con texto real mantenga estilo fuerte */
            .ultra-title {
                font-weight: 700 !important;
                /* conserva título fuerte cuando hay contenido */
            }

            /* ===== Placeholder difuminado y claramente distinto del texto ===== */
            #wpbody-content .ultra-wrap input.ultra-title::placeholder,
            #wpbody-content .ultra-wrap input.ultra-title::-webkit-input-placeholder,
            #wpbody-content .ultra-wrap input.ultra-title::-moz-placeholder,
            #wpbody-content .ultra-wrap input.ultra-title:-ms-input-placeholder,
            #wpbody-content .ultra-wrap input.ultra-title::-ms-input-placeholder,
            #wpbody-content .ultra-wrap textarea.ultra-input::placeholder,
            #wpbody-content .ultra-wrap textarea.ultra-input::-webkit-input-placeholder,
            #wpbody-content .ultra-wrap textarea.ultra-input::-moz-placeholder,
            #wpbody-content .ultra-wrap textarea.ultra-input:-ms-input-placeholder,
            #wpbody-content .ultra-wrap textarea.ultra-input::-ms-input-placeholder {
                color: rgba(247, 251, 255, 0.22) !important;
                /* mucho más atenuado */
                font-weight: 400 !important;
                /* más ligero */
                font-style: italic !important;
                /* opcional: indica placeholder */
                font-size: 1.6rem !important;
                /* más pequeño que el título real (ajusta al gusto) */
                text-shadow: none !important;
                /* eliminar sombras que aumentan contraste */
                -webkit-text-fill-color: unset !important;
                /* anula fill blanco aplicado globalmente */
                opacity: 1 !important;
                /* usa RGBA en color, no opacity global */
                transition: color .18s ease-in-out !important;
            }

            /* Cuando el input tiene foco mantenemos el placeholder muy sutil (si queda visible) */
            #wpbody-content .ultra-wrap input.ultra-title:focus::placeholder,
            #wpbody-content .ultra-wrap textarea.ultra-input:focus::placeholder {
                color: rgba(247, 251, 255, 0.18) !important;
            }

            /* Si necesitas que el placeholder sea aún más pequeño en títulos, baja font-size */
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
        <style>
            /* Contenedor relativo para el faux placeholder */
            .faux-input-wrap {
                position: relative;
                display: block;
            }

            /* Span que actúa como placeholder (estilo difuminado premium) */
            /* Faux placeholder: mismo tamaño/peso, solo color más débil */
            .faux-placeholder {
                position: absolute;
                left: 26px;
                /* ajusta si necesitas alinearlo */
                top: 50%;
                transform: translateY(-50%);
                pointer-events: none;
                font: inherit !important;
                /* hereda tamaño, peso y familia del input */
                color: rgba(247, 251, 255, 0.28) !important;
                /* color más débil; ajusta el último valor (0.28) */
                font-style: normal !important;
                /* quitar itálica si la tenías */
                transition: opacity .14s ease, color .14s ease;
                opacity: 1;
                z-index: 2;
                text-shadow: none !important;
            }

            .faux-placeholder.hidden {
                opacity: 0;
                visibility: hidden;
            }

            /* Ocultar cuando el campo tiene texto o está enfocado */
            .faux-placeholder.hidden {
                opacity: 0;
                visibility: hidden;
            }

            /* Asegura que el input esté por encima del background pero debajo del texto real cuando escribes */
            .faux-input-wrap .ultra-title {
                position: relative;
                z-index: 3;
                background: transparent;
            }

            /* =========================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            PARTICLES BACKGROUND
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         ========================= */
            #cyber-particles {
                position: fixed;
                inset: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
                pointer-events: none;
            }

            .ultra-wrap {
                position: relative;
                z-index: 2;
            }

            .faux-placeholder {
                font-size: 2.7rem;
                font-weight: 600;
                letter-spacing: -0.6px;
                background: linear-gradient(90deg,
                        rgba(247, 251, 255, 0.35),
                        rgba(165, 216, 255, 0.45));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                opacity: 0.9;
            }

            .ultra-title:focus+.faux-placeholder {
                opacity: 0;
                transform: translateY(-6px);
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.ultra-title').forEach(function (input) {
                    // crear wrapper
                    var wrapper = document.createElement('div');
                    wrapper.className = 'faux-input-wrap';

                    // insertar wrapper antes del input y mover el input dentro
                    input.parentNode.insertBefore(wrapper, input);
                    wrapper.appendChild(input);

                    // crear span con el texto del placeholder
                    var phText = input.getAttribute('placeholder') || '';
                    var span = document.createElement('span');
                    span.className = 'faux-placeholder';
                    span.textContent = phText;
                    wrapper.appendChild(span);

                    // quitar placeholder nativo (evita interferencias)
                    input.removeAttribute('placeholder');

                    // toggle function
                    var toggle = function () {
                        if (input.value && input.value.trim().length > 0) {
                            span.classList.add('hidden');
                        } else {
                            span.classList.remove('hidden');
                        }
                    };

                    // eventos
                    input.addEventListener('input', toggle);
                    input.addEventListener('focus', function () { span.classList.add('hidden'); });
                    input.addEventListener('blur', toggle);

                    // estado inicial
                    toggle();
                });
            });

            // =========================
            // CYBER PARTICLES BACKGROUND
            // =========================
            const canvas = document.getElementById('cyber-particles');
            const ctx = canvas.getContext('2d');

            let particles = [];
            const PARTICLE_COUNT = 80;

            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            function createParticles() {
                particles = [];
                for (let i = 0; i < PARTICLE_COUNT; i++) {
                    particles.push({
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height,
                        r: Math.random() * 1.8  0.6,
                        vx: (Math.random() - 0.5) * 0.3,
                        vy: (Math.random() - 0.5) * 0.3,
                        alpha: Math.random() * 0.6  0.2
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         });
                }
            }
            createParticles();

            function animateParticles() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                particles.forEach(p => {
                    p.x = p.vx;
                    p.y = p.vy;

                    if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
                    if (p.y < 0 || p.y > canvas.height) p.vy *= -1;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(0,212,255,${p.alpha})`;
                    ctx.fill();
                });

                requestAnimationFrame(animateParticles);
            }

            animateParticles();

        </script>
        <canvas id="cyber-particles"></canvas>
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
                        <input type="text" name="titulo" class="ultra-input ultra-title" placeholder="¿Qué está pasando?"
                            required>
                    </div>
                    <div class="input-group">
                        <label>CONTENIDO EDITORIAL</label>

                        <div id="blocks-editor"></div>
                    </div>
                </div>

                <div class="sidebar-editor">
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
                    <button type="button" id="btn-preview" class="btn-preview">
                        👁 Vista previa
                    </button>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const editor = document.getElementById('blocks-editor');
                        if (!editor) return;

                        const DEFAULT_BLOCKS = ['entradilla', 'desarrollo', 'conclusion'];

                        const createBlock = (type, removable = true) => {
                            const block = document.createElement('div');
                            block.className = 'block-item';
                            block.dataset.type = type;
                            block.innerHTML = `
                                    <div class="block-toolbar">
                                    <span class="drag-handle" title="Mover bloque">⋮⋮</span>
                                        <div class="block-left-actions">
                                            <button type="button" class="block-add" title="Añadir texto debajo"></button>
                                            <button type="button" class="block-add-image" title="Añadir imagen debajo">🖼</button>
                                            <span class="block-tag">${type}</span>
                                        </div>
                                        ${removable ? '<button type="button" class="block-remove" title="Eliminar bloque"></button>' : ''}
                                    </div>
                                    <textarea class="ultra-input"
                                        name="blocks[${type}][]"
                                        placeholder="Escribe ${type}..."></textarea>
                                `;

                            const removeBtn = block.querySelector('.block-remove');
                            if (removeBtn) {
                                removeBtn.addEventListener('click', () => block.remove());
                            }
                            const addBtn = block.querySelector('.block-add');
                            const addImageBtn = block.querySelector('.block-add-image');

                            if (addImageBtn) {
                                addImageBtn.addEventListener('click', () => {
                                    insertImageBelow(block);
                                });
                            }

                            if (addBtn) {
                                addBtn.addEventListener('click', () => {
                                    const newBlock = createBlock(type, true);
                                    block.after(newBlock);
                                });
                            }


                            editor.appendChild(block);
                            return block;
                        };
                        // =========================
                        // BLOQUE IMAGEN SIMPLE
                        // =========================
                        const insertImageBelow = (afterBlock) => {
                            const frame = wp.media({
                                title: 'Seleccionar imagen',
                                button: { text: 'Insertar imagen' },
                                multiple: false,
                                library: { type: 'image' }
                            });

                            frame.on('select', () => {
                                const img = frame.state().get('selection').first().toJSON();

                                const imgBlock = document.createElement('div');
                                imgBlock.className = 'block-item block-image';

                                imgBlock.innerHTML = `
            <div class="block-toolbar">
                <span class="drag-handle">⋮⋮</span>
                <span class="block-tag">imagen</span>
                <button type="button" class="block-remove"></button>
            </div>

            <input type="hidden" name="blocks[image][]" value="${img.url}">
            <img src="${img.url}" class="block-image-preview">
        `;

                                imgBlock.querySelector('.block-remove').onclick = () => imgBlock.remove();
                                afterBlock.after(imgBlock);
                            });

                            frame.open();
                        };

                        // =========================
                        // BLOQUE IMAGEN
                        // =========================
                        const createImageBlock = (image) => {
                            const block = document.createElement('div');
                            block.className = 'block-item block-image';
                            block.dataset.type = 'image';

                            block.innerHTML = `
                            <div class="block-toolbar">
                                <span class="drag-handle">⋮⋮</span>
                                <span class="block-tag">imagen</span>
                                <button type="button" class="block-remove"></button>
                            </div>

                            <input type="hidden" name="blocks[][type]" value="image">
                            <input type="hidden" name="blocks[][id]" value="${image.id}">
                            <input type="hidden" name="blocks[][url]" value="${image.url}">

                            <img src="${image.url}" class="block-image-preview">

                            <input type="text"
                                class="ultra-input image-caption"
                                name="blocks[][caption]"
                                placeholder="Pie de foto (opcional)">
                        `;

                            block.querySelector('.block-remove').onclick = () => block.remove();
                            return block;
                        };
                        // =========================
                        // SELECTOR DE IMÁGENES WP
                        // =========================
                        const openImagePicker = (afterBlock) => {
                            const frame = wp.media({
                                title: 'Seleccionar imagen',
                                button: { text: 'Insertar imagen' },
                                multiple: false,
                                library: { type: 'image' }
                            });

                            frame.on('select', () => {
                                const img = frame.state().get('selection').first().toJSON();
                                const imgBlock = createImageBlock(img);
                                afterBlock.after(imgBlock);
                            });

                            frame.open();
                        };


                        // 🔹 CREAR LOS 3 BLOQUES BASE (NO ELIMINABLES)
                        DEFAULT_BLOCKS.forEach(type => createBlock(type, false));

                        // 🔹 BOTONES → AÑADEN BLOQUES EXTRA
                        document.querySelectorAll('.btn-add').forEach(btn => {
                            btn.addEventListener('click', () => {
                                createBlock(btn.dataset.type, true);
                            });
                        });
                        // =========================
                        // DRAG & DROP DE BLOQUES
                        // =========================
                        Sortable.create(editor, {
                            animation: 180,
                            draggable: '.block-item',

                            ghostClass: 'block-ghost',
                            chosenClass: 'block-chosen',
                            dragClass: 'block-dragging',

                            // ⛔ NO iniciar drag desde inputs editables
                            onMove: function (evt) {
                                const target = evt.originalEvent.target;
                                return !target.closest('textarea, input, select, button');
                            }
                        });
                    });
                </script>
            </form>
            <div id="preview-modal" class="preview-modal">
                <div class="preview-inner">
                    <button class="preview-close">✕</button>
                    <div id="preview-content"></div>
                </div>
            </div>
            <script>
                document.getElementById('btn-preview').addEventListener('click', () => {
                    const preview = document.getElementById('preview-content');
                    preview.innerHTML = '';

                    // TÍTULO
                    const title = document.querySelector('input[name="titulo"]').value;
                    preview.innerHTML += `<h1 style="font-size:42px;margin-bottom:20px">${title}</h1>`;

                    // CATEGORÍA
                    const cat = document.getElementById('segmento').value;
                    preview.innerHTML += `<span style="color:#00d4ff;font-weight:700">${cat}</span>`;

                    // IMAGEN DESTACADA (si existe)
                    const cover = document.querySelector('#preview_cover_container img');
                    if (cover) {
                        preview.innerHTML += `<img src="${cover.src}" style="width:100%;margin:30px 0;border-radius:14px">`;
                    }

                    // BLOQUES
                    document.querySelectorAll('#blocks-editor .block-item').forEach(block => {
                        if (block.classList.contains('block-image')) {
                            const img = block.querySelector('img');
                            if (img) {
                                preview.innerHTML += `
                    <figure style="margin:30px 0">
                        <img src="${img.src}" style="width:100%;border-radius:12px">
                    </figure>
                `;
                            }
                        } else {
                            const textarea = block.querySelector('textarea');
                            if (textarea && textarea.value.trim()) {
                                preview.innerHTML += `<p style="font-size:18px;line-height:1.7;margin:22px 0">${textarea.value}</p>`;
                            }
                        }
                    });

                    document.getElementById('preview-modal').classList.add('active');
                    document.body.classList.add('preview-open');
                });

                // Cerrar preview
                document.querySelector('.preview-close').onclick = () => {
                    document.getElementById('preview-modal').classList.remove('active');
                    document.body.classList.remove('preview-open');
                };

            </script>
        </div>
        <?php
    }


    public function inject_admin_block_styles()
    {
        if (!isset($_GET['page']) || $_GET['page'] !== 'crear-noticia-pro') {
            return;
        }
        ?>
        <style>
            /* ===== BLOQUES EDITOR (ADMIN) ===== */
            .preview-modal {
                position: fixed;
                inset: 0;
                background: rgba(3, 10, 18, .92);
                z-index: 999999;
                display: none;

                /* 🔒 EL OVERLAY NUNCA SCROLLEA */
                overflow: hidden;
            }

            .preview-modal.active {
                display: block;
            }

            .preview-modal.active {
                display: block;
            }

            .preview-inner {
                max-width: 980px;
                margin: 60px auto;
                background: #0b1622;
                border-radius: 20px;
                padding: 40px;
                color: #fff;
                position: relative;
                box-shadow: 0 0 60px rgba(0, 212, 255, .25);

                max-height: calc(100vh - 120px);
                overflow-y: auto;
            }

            .preview-close {
                position: absolute;
                top: 18px;
                right: 18px;
                background: none;
                border: none;
                color: #fff;
                font-size: 20px;
                cursor: pointer;
            }

            .btn-preview {
                width: 100%;
                margin-top: 10px;
                padding: 16px;
                border-radius: 14px;
                border: 1px solid rgba(0, 212, 255, .6);
                background: rgba(0, 212, 255, .12);
                color: #fff;
                font-weight: 700;
                letter-spacing: 1px;
                cursor: pointer;
                transition: all .25s ease;
                box-shadow: inset 0 0 14px rgba(0, 212, 255, .15);
            }

            .btn-preview:hover {
                background: rgba(0, 212, 255, .25);
                box-shadow:
                    0 0 20px rgba(0, 212, 255, .6),
                    inset 0 0 18px rgba(255, 255, 255, .2);
            }

            #blocks-editor {
                display: flex;
                flex-direction: column;
                gap: 18px;
            }

            .block-item {
                background: rgba(255, 255, 255, .05);
                border: 1px solid rgba(0, 212, 255, .35);
                border-radius: 14px;
                padding: 16px;
            }

            .block-toolbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;
                color: var(--accent-cyan);
                text-transform: uppercase;
                font-size: .7rem;
            }

            .block-left-actions {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .block-left-actions {
                margin-left: 12px;
                /* ajusta: 8px–16px según gusto */
            }


            /* BOTÓN + */
            .block-add {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                border: 2px solid var(--accent-cyan);
                background: rgba(0, 212, 255, .25);
                cursor: pointer;
                position: relative;
                box-shadow: 0 0 12px rgba(0, 212, 255, .55);
            }

            .block-add::before,
            .block-add::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                background: #fff;
                transform: translate(-50%, -50%);
            }

            .block-add::before {
                width: 14px;
                height: 2px;
            }

            .block-add::after {
                width: 2px;
                height: 14px;
            }

            .block-add:hover {
                transform: scale(1.15);
                background: rgba(0, 212, 255, .45);
            }

            /* BOTÓN − */
            .block-remove {
                width: 28px;
                height: 28px;
                border-radius: 50%;
                border: none;
                background: rgba(255, 107, 107, .2);
                cursor: pointer;
                position: relative;
                box-shadow: 0 0 10px rgba(255, 107, 107, .6);
            }

            .block-remove::before,
            .block-remove::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 14px;
                height: 2px;
                background: #ff6b6b;
            }

            .block-remove::before {
                transform: translate(-50%, -50%) rotate(45deg);
            }

            .block-remove::after {
                transform: translate(-50%, -50%) rotate(-45deg);
            }

            /* ===== DRAG HANDLE ===== */
            .drag-handle {
                cursor: grab;
                font-size: 16px;
                letter-spacing: -2px;
                color: var(--accent-cyan);
                opacity: 0.7;
                margin-right: 10px;
                user-select: none;
            }

            .drag-handle:hover {
                opacity: 1;
                text-shadow: 0 0 8px rgba(0, 212, 255, 0.8);
            }

            .block-dragging {
                opacity: 0.6;
            }

            .block-ghost {
                background: rgba(0, 212, 255, 0.15);
                border: 2px dashed var(--accent-cyan);
            }

            /* ===== CURSOR DE ARRASTRE (UX CLARO) ===== */

            /* Card completa: se puede mover */
            .block-item {
                cursor: grab;
            }

            /* Mientras se arrastra */
            .block-item.sortable-chosen,
            .block-item.sortable-drag {
                cursor: grabbing !important;
            }

            /* Inputs mantienen su cursor natural */
            .block-item textarea,
            .block-item input {
                cursor: text;
            }

            /* Botones mantienen pointer */
            .block-item button {
                cursor: pointer;
            }

            /* Hover sutil para indicar que se puede mover */
            .block-item:hover {
                box-shadow: 0 0 0 1px rgba(0, 212, 255, 0.6),
                    0 0 18px rgba(0, 212, 255, 0.25);
            }

            /* ===== BLOQUE IMAGEN ===== */
            .block-image-preview {
                max-width: 100%;
                border-radius: 12px;
                margin: 10px 0;
                box-shadow: 0 0 18px rgba(0, 212, 255, .25);
            }

            .block-image .image-caption {
                margin-top: 8px;
            }

            .block-add-image {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: 2px solid rgba(0, 212, 255, 0.9);
                background: linear-gradient(135deg,
                        rgba(0, 212, 255, 0.55),
                        rgba(0, 153, 204, 0.55));
                cursor: pointer;
                font-size: 15px;
                display: grid;
                place-items: center;
                color: #ffffff;
                box-shadow:
                    0 0 10px rgba(0, 212, 255, 0.9),
                    inset 0 0 6px rgba(255, 255, 255, 0.35);
            }

            .block-add-image:hover {
                transform: scale(1.18);
                box-shadow:
                    0 0 18px rgba(0, 212, 255, 1),
                    0 0 30px rgba(0, 212, 255, 0.6);
            }

            .block-image-preview {
                max-width: 100%;
                border-radius: 12px;
                margin-top: 10px;
                box-shadow: 0 0 18px rgba(0, 212, 255, .25);
            }

            /* 🔒 Bloqueo total del scroll del admin cuando el modal está activo */
            body.preview-open,
            body.preview-open #wpwrap,
            body.preview-open #wpcontent,
            body.preview-open #wpbody,
            body.preview-open #wpbody-content {
                overflow: hidden !important;
            }

            .news-read-more {
                margin-top: auto;
                display: inline-block;
                padding: 12px 18px;
                border-radius: 12px;
                font-weight: 700;
                text-align: center;
                text-decoration: none;
                color: #ffffff;
                background: linear-gradient(135deg, var(--accent), var(--accent-dark));
                box-shadow: 0 6px 20px rgba(43, 144, 227, 0.35);
                transition: all .25s ease;
            }

            .news-read-more:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 30px rgba(43, 144, 227, 0.55);
            }

            .news-read-more {
                margin-top: auto;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;

                padding: 14px 18px;
                border-radius: 14px;

                font-weight: 800;
                letter-spacing: .5px;
                text-transform: uppercase;

                background: linear-gradient(135deg, #2b90e3, #3bc6e7);
                color: #fff !important;

                box-shadow: 0 8px 24px rgba(43, 144, 227, .45);
            }

            .news-read-more:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 36px rgba(43, 144, 227, .65);
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
            $img = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if (!$img)
                $img = 'https://via.placeholder.com/800x600/0a2e4e/00d4ff?text=Noticia';
            ?>
            <div class="news-ultra-card">
                <img src="<?= esc_url($img) ?>" class="news-ultra-img" alt="<?= esc_attr(get_the_title()) ?>">
                <div class="news-ultra-overlay">
                    <span class="u-badge"><?= esc_html($cat ?: 'General') ?></span>
                    <h3><?= get_the_title() ?></h3>
                    <p><?= wp_trim_words(get_the_content(), 18) ?></p>
                    <a href="<?= esc_url(get_permalink()) ?>" class="news-read-more">
                        Leer noticia →
                    </a>
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
        if (!wp_verify_nonce($_POST['crear_noticia_admin_nonce'], 'crear_noticia_admin')) {
            return;
        }

        $post_id = wp_insert_post([
            'post_type' => 'noticia',
            'post_title' => sanitize_text_field($_POST['titulo']),
            'post_content' => '', // 👈 contenido clásico desactivado
            'post_status' => 'publish',
        ]);

        if (is_wp_error($post_id)) {
            return;
        }

        $blocks = [];

        if (!empty($_POST['blocks'])) {
            foreach ($_POST['blocks'] as $type => $items) {
                foreach ($items as $content) {
                    $blocks[] = [
                        'type' => sanitize_text_field($type),
                        'content' => wp_kses_post($content)
                    ];
                }
            }
        }

        update_post_meta($post_id, '_news_blocks', $blocks);

        // 📌 Categoría
        update_post_meta($post_id, '_categoria', sanitize_text_field($_POST['categoria']));

        // 📌 Imagen destacada
        if (!empty($_FILES['imagen']['name'])) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            $img_id = media_handle_upload('imagen', $post_id);
            if (!is_wp_error($img_id)) {
                set_post_thumbnail($post_id, $img_id);
            }
        }

        // 📌 PDF principal
        if (!empty($_FILES['pdf_doc']['name'])) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';

            $pdf_id = media_handle_upload('pdf_doc', $post_id);
            if (!is_wp_error($pdf_id)) {
                update_post_meta($post_id, '_pdf_doc', $pdf_id);
            }
        }

        echo '<div class="notice notice-success is-dismissible">
            <p><strong>✓ Publicación lanzada con éxito</strong></p>
          </div>';
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