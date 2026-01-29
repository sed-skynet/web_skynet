<?php
/**
 * Plugin Name: Actualidad al Día - Noticias
 * Description: CRUD de noticias frontend/backend (solo admin crea/edita/borra)
 * Version: 1.1
 * Author: Skynet Systems
 */

if (!defined('ABSPATH'))
    exit;

class Actualidad_Noticias
{

    public function __construct()
    {
        add_action('init', [$this, 'register_cpt']);
        add_action('add_meta_boxes', [$this, 'register_meta_boxes']);
        add_action('save_post_noticia', [$this, 'save_meta']);

        add_shortcode('actualidad_noticias', [$this, 'render_noticias']);
        add_action('admin_menu', [$this, 'register_admin_page']);
    }

    /* ===============================
     * CPT
     * =============================== */
    public function register_cpt()
    {
        register_post_type('noticia', [
            'labels' => [
                'name' => 'Noticias',
                'singular_name' => 'Noticia',
            ],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-media-document',
            'supports' => ['title', 'editor', 'thumbnail'],
        ]);
    }
    public function register_admin_page()
    {
        add_submenu_page(
            'edit.php?post_type=noticia',
            'Crear noticia',
            'Crear noticia',
            'manage_options',
            'crear-noticia',
            [$this, 'admin_crear_noticia_page']
        );
    }
    public function admin_crear_noticia_page()
    {
        if (!current_user_can('manage_options'))
            return;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handle_admin_create();
        }
        ?>

        <style>
            :root {
                --cyber-blue: #00f0ff;
                --cyber-pink: #ff006e;
                --cyber-purple: #8b5cf6;
                --cyber-green: #00ff9f;
                --glass-1: rgba(255, 255, 255, 0.03);
                --glass-2: rgba(255, 255, 255, 0.08);
                --border-glow: rgba(0, 240, 255, 0.3);
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            @keyframes bgShift {

                0%,
                100% {
                    background-position: 0% 50%;
                }

                50% {
                    background-position: 100% 50%;
                }
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-20px) rotate(5deg);
                }
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 0.3;
                    transform: scale(1);
                }

                50% {
                    opacity: 0.8;
                    transform: scale(1.05);
                }
            }

            @keyframes scanline {
                0% {
                    transform: translateY(-100%);
                }

                100% {
                    transform: translateY(100vh);
                }
            }

            @keyframes glitch {
                0% {
                    transform: translate(0);
                }

                20% {
                    transform: translate(-2px, 2px);
                }

                40% {
                    transform: translate(-2px, -2px);
                }

                60% {
                    transform: translate(2px, 2px);
                }

                80% {
                    transform: translate(2px, -2px);
                }

                100% {
                    transform: translate(0);
                }
            }

            @keyframes neon-flicker {

                0%,
                100% {
                    opacity: 1;
                    text-shadow: 0 0 10px var(--cyber-blue), 0 0 20px var(--cyber-blue), 0 0 30px var(--cyber-blue);
                }

                50% {
                    opacity: 0.8;
                    text-shadow: 0 0 5px var(--cyber-blue), 0 0 10px var(--cyber-blue);
                }
            }

            @keyframes particle-float {
                0% {
                    transform: translate(0, 0) scale(0);
                    opacity: 0;
                }

                50% {
                    opacity: 1;
                }

                100% {
                    transform: translate(var(--tx), var(--ty)) scale(1);
                    opacity: 0;
                }
            }

            @keyframes border-flow {
                0% {
                    background-position: 0% 50%;
                }

                50% {
                    background-position: 100% 50%;
                }

                100% {
                    background-position: 0% 50%;
                }
            }

            @keyframes button-glow {

                0%,
                100% {
                    box-shadow: 0 0 20px rgba(0, 240, 255, 0.5), 0 0 40px rgba(255, 0, 110, 0.3), inset 0 0 20px rgba(0, 240, 255, 0.2);
                }

                50% {
                    box-shadow: 0 0 40px rgba(0, 240, 255, 0.8), 0 0 60px rgba(255, 0, 110, 0.5), inset 0 0 30px rgba(0, 240, 255, 0.4);
                }
            }

            @keyframes data-stream {
                0% {
                    transform: translateY(-100%);
                    opacity: 0;
                }

                50% {
                    opacity: 1;
                }

                100% {
                    transform: translateY(100%);
                    opacity: 0;
                }
            }

            body.wp-admin .aad-quantum {
                margin: 0;
                min-height: 100vh;
                background: linear-gradient(135deg, #0a0e27, #1a1438, #0f0c29, #302b63, #24243e);
                background-size: 400% 400%;
                animation: bgShift 20s ease infinite;
                font-family: 'Inter', sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                color: #fff;
                overflow: hidden;
                position: relative;
            }

            body.wp-admin .aad-quantum::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background:
                    radial-gradient(circle at 20% 30%, rgba(0, 240, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 70%, rgba(255, 0, 110, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.05) 0%, transparent 70%);
                pointer-events: none;
                z-index: 1;
            }

            body.wp-admin .aad-quantum::after {
                content: '';
                position: fixed;
                top: 0;
                left: -100%;
                width: 300%;
                height: 3px;
                background: linear-gradient(90deg, transparent, var(--cyber-blue), transparent);
                animation: scanline 8s linear infinite;
                opacity: 0.3;
                z-index: 10;
                pointer-events: none;
            }

            .aad-quantum {
                position: relative;
            }

            /* Particles */
            .particle {
                position: fixed;
                width: 3px;
                height: 3px;
                background: var(--cyber-blue);
                border-radius: 50%;
                pointer-events: none;
                animation: particle-float 4s ease-in-out infinite;
                z-index: 2;
            }

            .particle:nth-child(1) {
                left: 10%;
                top: 20%;
                --tx: 40px;
                --ty: -80px;
                animation-delay: 0s;
            }

            .particle:nth-child(2) {
                left: 30%;
                top: 60%;
                --tx: -60px;
                --ty: -100px;
                animation-delay: 1s;
                background: var(--cyber-pink);
            }

            .particle:nth-child(3) {
                left: 70%;
                top: 30%;
                --tx: 80px;
                --ty: 90px;
                animation-delay: 2s;
                background: var(--cyber-purple);
            }

            .particle:nth-child(4) {
                left: 50%;
                top: 80%;
                --tx: -50px;
                --ty: -70px;
                animation-delay: 1.5s;
            }

            .particle:nth-child(5) {
                left: 85%;
                top: 50%;
                --tx: 70px;
                --ty: -60px;
                animation-delay: 0.5s;
                background: var(--cyber-green);
            }

            .particle:nth-child(6) {
                left: 15%;
                top: 70%;
                --tx: -80px;
                --ty: 100px;
                animation-delay: 2.5s;
            }

            /* Grid background */
            .cyber-grid {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image:
                    linear-gradient(rgba(0, 240, 255, 0.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(0, 240, 255, 0.03) 1px, transparent 1px);
                background-size: 50px 50px;
                animation: float 20s ease-in-out infinite;
                pointer-events: none;
                z-index: 1;
            }

            .news-app {
                width: 1400px;
                max-width: 96%;
                height: 820px;
                background: var(--glass-1);
                backdrop-filter: blur(30px) saturate(180%);
                border: 2px solid transparent;
                background-clip: padding-box;
                border-radius: 32px;
                box-shadow:
                    0 50px 150px rgba(0, 0, 0, 0.6),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1),
                    0 0 80px rgba(0, 240, 255, 0.2);
                display: grid;
                grid-template-columns: 2.3fr 1fr;
                overflow: hidden;
                position: relative;
                z-index: 5;
                animation: float 8s ease-in-out infinite;
            }

            .news-app::before {
                content: '';
                position: absolute;
                top: -2px;
                left: -2px;
                right: -2px;
                bottom: -2px;
                background: linear-gradient(45deg, var(--cyber-blue), var(--cyber-pink), var(--cyber-purple), var(--cyber-green));
                background-size: 400% 400%;
                border-radius: 32px;
                z-index: -1;
                animation: border-flow 6s ease infinite;
            }

            .news-app::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background:
                    linear-gradient(135deg, rgba(0, 240, 255, 0.02) 0%, transparent 50%),
                    linear-gradient(-45deg, rgba(255, 0, 110, 0.02) 0%, transparent 50%);
                border-radius: 32px;
                pointer-events: none;
                z-index: 1;
            }

            /* MAIN SECTION */
            .news-main {
                padding: 58px;
                display: flex;
                flex-direction: column;
                gap: 28px;
                position: relative;
                z-index: 2;
            }

            /* Holographic header */
            .holo-header {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 12px;
                animation: neon-flicker 4s ease-in-out infinite;
            }

            .holo-icon {
                width: 42px;
                height: 42px;
                background: linear-gradient(135deg, var(--cyber-blue), var(--cyber-purple));
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                box-shadow: 0 0 30px rgba(0, 240, 255, 0.5);
                animation: pulse 3s ease-in-out infinite;
            }

            .holo-text {
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 0.3em;
                text-transform: uppercase;
                background: linear-gradient(90deg, var(--cyber-blue), var(--cyber-pink), var(--cyber-purple));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-shadow: 0 0 20px rgba(0, 240, 255, 0.5);
            }

            .news-title {
                font-size: 52px;
                font-weight: 900;
                background: transparent;
                border: none;
                outline: none;
                color: #fff;
                padding: 0;
                line-height: 1.15;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                text-shadow: 0 0 30px rgba(0, 240, 255, 0.3);
                position: relative;
            }

            .news-title::placeholder {
                color: rgba(255, 255, 255, 0.25);
                background: linear-gradient(90deg, rgba(0, 240, 255, 0.3), rgba(255, 0, 110, 0.3));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .news-title:focus {
                text-shadow:
                    0 0 20px var(--cyber-blue),
                    0 0 40px var(--cyber-blue),
                    0 0 60px var(--cyber-blue);
                animation: glitch 0.3s ease;
            }

            .editor-wrapper {
                flex: 1;
                position: relative;
                border-radius: 24px;
                overflow: hidden;
            }

            .editor-wrapper::before {
                content: '';
                position: absolute;
                top: -2px;
                left: -2px;
                right: -2px;
                bottom: -2px;
                background: linear-gradient(135deg, rgba(0, 240, 255, 0.2), rgba(255, 0, 110, 0.2));
                border-radius: 24px;
                z-index: -1;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .editor-wrapper:focus-within::before {
                opacity: 1;
            }

            .news-editor {
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(10px);
                border-radius: 24px;
                padding: 32px;
                font-size: 17px;
                line-height: 1.8;
                color: #fff;
                border: 1px solid rgba(255, 255, 255, 0.08);
                outline: none;
                resize: none;
                font-family: 'Inter', sans-serif;
                font-weight: 400;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: inset 0 0 50px rgba(0, 0, 0, 0.3);
            }

            .news-editor::placeholder {
                color: rgba(255, 255, 255, 0.3);
            }

            .news-editor:focus {
                background: rgba(0, 0, 0, 0.5);
                border-color: rgba(0, 240, 255, 0.4);
                box-shadow:
                    inset 0 0 50px rgba(0, 0, 0, 0.3),
                    0 0 30px rgba(0, 240, 255, 0.2);
            }

            /* Word count indicator */
            .word-count {
                position: absolute;
                bottom: 16px;
                right: 16px;
                font-size: 11px;
                font-family: 'JetBrains Mono', monospace;
                color: var(--cyber-blue);
                background: rgba(0, 0, 0, 0.6);
                padding: 6px 14px;
                border-radius: 20px;
                border: 1px solid rgba(0, 240, 255, 0.3);
                backdrop-filter: blur(10px);
                animation: pulse 2s ease-in-out infinite;
            }

            /* SIDEBAR */
            .news-side {
                background: rgba(0, 0, 0, 0.35);
                backdrop-filter: blur(20px);
                border-left: 1px solid rgba(0, 240, 255, 0.15);
                padding: 42px 36px;
                display: flex;
                flex-direction: column;
                gap: 32px;
                position: relative;
                z-index: 2;
            }

            .news-side::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 2px;
                height: 100%;
                background: linear-gradient(180deg, transparent, var(--cyber-blue), var(--cyber-pink), transparent);
                animation: data-stream 3s ease-in-out infinite;
            }

            .side-block {
                position: relative;
                transition: transform 0.3s ease;
            }

            .side-block:hover {
                transform: translateX(4px);
            }

            .side-block h4 {
                margin: 0 0 14px;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                color: var(--cyber-blue);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .side-block h4::before {
                content: '▸';
                font-size: 14px;
                animation: pulse 2s ease-in-out infinite;
            }

            .side-block select,
            .side-block input[type=file] {
                width: 100%;
                padding: 16px 18px;
                border-radius: 16px;
                border: 1px solid rgba(0, 240, 255, 0.2);
                background: rgba(255, 255, 255, 0.05);
                color: #fff;
                outline: none;
                font-size: 15px;
                font-family: 'Inter', sans-serif;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                backdrop-filter: blur(10px);
            }

            .side-block select:hover,
            .side-block input[type=file]:hover {
                background: rgba(255, 255, 255, 0.1);
                border-color: var(--cyber-blue);
                box-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
                transform: translateY(-2px);
            }

            .side-block select:focus,
            .side-block input[type=file]:focus {
                background: rgba(255, 255, 255, 0.12);
                border-color: var(--cyber-blue);
                box-shadow: 0 0 30px rgba(0, 240, 255, 0.4), inset 0 0 20px rgba(0, 240, 255, 0.1);
            }

            .side-block select option {
                background: #1a1438;
                color: #fff;
                padding: 10px;
            }

            /* AI Assistant */
            .ai-assistant {
                background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(0, 240, 255, 0.15));
                border: 1px solid rgba(139, 92, 246, 0.3);
                border-radius: 18px;
                padding: 20px;
                margin-top: 8px;
            }

            .ai-assistant p {
                font-size: 13px;
                line-height: 1.6;
                margin: 0;
                color: rgba(255, 255, 255, 0.8);
            }

            .ai-assistant button {
                margin-top: 12px;
                width: 100%;
                padding: 12px;
                background: rgba(139, 92, 246, 0.2);
                border: 1px solid rgba(139, 92, 246, 0.4);
                border-radius: 12px;
                color: var(--cyber-purple);
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .ai-assistant button:hover {
                background: rgba(139, 92, 246, 0.3);
                box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
                transform: scale(1.02);
            }

            /* Stats */
            .stats {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                margin-top: 8px;
            }

            .stat-item {
                background: rgba(0, 0, 0, 0.4);
                border: 1px solid rgba(0, 240, 255, 0.2);
                border-radius: 14px;
                padding: 14px;
                text-align: center;
            }

            .stat-value {
                font-size: 22px;
                font-weight: 900;
                color: var(--cyber-blue);
                font-family: 'JetBrains Mono', monospace;
            }

            .stat-label {
                font-size: 10px;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: rgba(255, 255, 255, 0.5);
                margin-top: 4px;
            }

            /* Publish button */
            .publish {
                margin-top: auto;
                position: relative;
            }

            .publish::before {
                content: '';
                position: absolute;
                top: -20px;
                left: 0;
                right: 0;
                height: 1px;
                background: linear-gradient(90deg, transparent, var(--cyber-blue), transparent);
            }

            .publish button {
                width: 100%;
                padding: 22px;
                font-size: 18px;
                font-weight: 900;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                border: none;
                border-radius: 20px;
                background: linear-gradient(135deg, var(--cyber-blue), var(--cyber-pink));
                color: #fff;
                cursor: pointer;
                position: relative;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                animation: button-glow 3s ease-in-out infinite;
            }

            .publish button::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: translate(-50%, -50%);
                transition: width 0.6s ease, height 0.6s ease;
            }

            .publish button:hover::before {
                width: 400px;
                height: 400px;
            }

            .publish button:hover {
                transform: translateY(-4px) scale(1.02);
                box-shadow:
                    0 20px 60px rgba(0, 240, 255, 0.6),
                    0 0 80px rgba(255, 0, 110, 0.4),
                    inset 0 0 30px rgba(255, 255, 255, 0.2);
            }

            .publish button:active {
                transform: translateY(-2px) scale(0.98);
            }

            .publish button span {
                position: relative;
                z-index: 1;
            }

            /* Status indicator */
            .status-bar {
                position: absolute;
                top: 20px;
                right: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(10px);
                padding: 10px 18px;
                border-radius: 20px;
                border: 1px solid rgba(0, 240, 255, 0.3);
                font-size: 12px;
                font-weight: 600;
                z-index: 100;
            }

            .status-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: var(--cyber-green);
                box-shadow: 0 0 10px var(--cyber-green);
                animation: pulse 2s ease-in-out infinite;
            }

            /* Responsive */
            @media (max-width: 1200px) {
                .news-app {
                    grid-template-columns: 1fr;
                    height: auto;
                    max-height: 90vh;
                }

                .news-side {
                    border-left: none;
                    border-top: 1px solid rgba(0, 240, 255, 0.15);
                }
            }

            @media (max-width: 768px) {
                .news-title {
                    font-size: 36px;
                }

                .news-main {
                    padding: 32px 24px;
                }

                .news-side {
                    padding: 32px 24px;
                }
            }
        </style>
        <div class="aad-quantum">
            <!-- Particles -->
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>

            <!-- Grid -->
            <div class="cyber-grid"></div>

            <!-- Status bar -->
            <div class="status-bar">
                <div class="status-dot"></div>
                QUANTUM EDITOR ONLINE
            </div>

            <form class="news-app" method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('crear_noticia_admin', 'crear_noticia_admin_nonce'); ?>
                <div class="news-main">
                    <div class="holo-header">
                        <div class="holo-icon">✦</div>
                        <div class="holo-text">Neural Writer</div>
                    </div>

                    <input class="news-title" name="titulo" placeholder="Título cinemático..." />

                    <div class="editor-wrapper">
                        <textarea class="news-editor" name="contenido"
                            placeholder="Inicia tu historia... El sistema está capturando cada palabra."></textarea>
                        <div class="word-count">0 palabras • 0 caracteres</div>
                    </div>
                </div>
                <aside class="news-side">
                    <div class="side-block">
                        <h4>Categoría Neural</h4>
                        <select name="categoria">
                            <option>⚡ Tecnología</option>
                            <option>🏆 Deportes</option>
                            <option>🌐 Política</option>
                            <option>🔬 Ciencia</option>
                            <option>🎨 Arte & Cultura</option>
                            <option>💼 Negocios</option>
                        </select>
                    </div>

                    <div class="side-block">
                        <h4>Imagen Holográfica</h4>
                        <input type="file" name="imagen" accept="image/*">
                    </div>

                    <div class="side-block">
                        <h4>Etiquetas Inteligentes</h4>
                        <select multiple style="height: 80px;">
                            <option>#Breaking</option>
                            <option>#Exclusiva</option>
                            <option>#Tendencia</option>
                            <option>#Investigación</option>
                        </select>
                    </div>

                    <div class="ai-assistant">
                        <h4 style="margin-bottom: 8px;">🤖 Asistente IA</h4>
                        <p>El análisis neural sugiere agregar contexto histórico y datos estadísticos.</p>
                        <button type="button">Optimizar con IA</button>
                    </div>

                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-value">92</div>
                            <div class="stat-label">SEO Score</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">4.2</div>
                            <div class="stat-label">Legibilidad</div>
                        </div>
                    </div>

                    <div class="publish">
                        <button type="submit"><span>🚀 PUBLICAR AHORA</span></button>
                    </div>
                </aside>
            </form>
        </div>
        </div>
        <script>
            // Microinteracciones dinámicas
            const editor = document.querySelector('.news-editor');
            const wordCount = document.querySelector('.word-count');
            const title = document.querySelector('.news-title');

            // Contador de palabras en tiempo real
            editor?.addEventListener('input', (e) => {
                const text = e.target.value;
                const words = text.trim().split(/\s+/).filter(w => w.length > 0).length;
                const chars = text.length;
                wordCount.textContent = `${words} palabras • ${chars} caracteres`;
            });

            // Efecto glitch en título al escribir
            let glitchTimeout;
            title?.addEventListener('input', () => {
                clearTimeout(glitchTimeout);
                title.style.animation = 'none';
                setTimeout(() => {
                    title.style.animation = 'glitch 0.3s ease';
                }, 10);
            });

            // Efecto de ripple en botón
            const publishBtn = document.querySelector('.publish button');
            publishBtn?.addEventListener('click', function (e) {
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255,255,255,0.6)';
                ripple.style.width = ripple.style.height = '100px';
                ripple.style.left = e.offsetX - 50 + 'px';
                ripple.style.top = e.offsetY - 50 + 'px';
                ripple.style.animation = 'pulse 0.6s ease-out';
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        </script>

        <?php
    }



    /* ===============================
     * META BOX (ADMIN)
     * =============================== */
    public function register_meta_boxes()
    {
        add_meta_box(
            'noticia_datos',
            'Datos de la noticia',
            [$this, 'meta_box_html'],
            'noticia'
        );
    }

    public function meta_box_html($post)
    {
        wp_nonce_field('guardar_noticia_admin', 'noticia_admin_nonce');
        $categoria = get_post_meta($post->ID, '_categoria', true);
        ?>

        <select name="categoria" style="width:100%">
            <?php foreach (['Tecnología', 'Deportes', 'Política', 'Ciencia'] as $cat): ?>
                <option value="<?= esc_attr($cat) ?>" <?= selected($categoria, $cat) ?>>
                    <?= esc_html($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php
    }

    public function save_meta($post_id)
    {
        if (
            !isset($_POST['noticia_admin_nonce']) ||
            !wp_verify_nonce($_POST['noticia_admin_nonce'], 'guardar_noticia_admin')
        )
            return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!current_user_can('edit_post', $post_id))
            return;

        if (isset($_POST['categoria'])) {
            update_post_meta($post_id, '_categoria', sanitize_text_field($_POST['categoria']));
        }
    }

    /* ===============================
     * FORMULARIO FRONTEND
     * =============================== */
    public function render_form()
    {

        if (!current_user_can('manage_options'))
            return '';

        ob_start(); ?>

        <form method="post" enctype="multipart/form-data" action="<?= esc_url(admin_url('admin-post.php')) ?>"
            class="news-form">

            <input type="hidden" name="action" value="crear_noticia_front">
            <?php wp_nonce_field('crear_noticia_front', 'noticia_front_nonce'); ?>

            <input name="titulo" placeholder="Título" required>
            <textarea name="descripcion" placeholder="Descripción" required></textarea>

            <select name="categoria" required>
                <option value="">Categoría</option>
                <option>Tecnología</option>
                <option>Deportes</option>
                <option>Política</option>
                <option>Ciencia</option>
            </select>

            <input type="file" name="imagen" required>
            <button>Publicar noticia</button>
        </form>

        <?php return ob_get_clean();
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

        update_post_meta($post_id, '_categoria', sanitize_text_field($_POST['categoria']));

        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        if (!empty($_FILES['imagen']['name'])) {
            $img_id = media_handle_upload('imagen', $post_id);
            if (!is_wp_error($img_id)) {
                set_post_thumbnail($post_id, $img_id);
            }
        }

        echo '<div class="notice notice-success"><p>Noticia creada correctamente.</p></div>';
    }

    /* ===============================
     * CREAR NOTICIA (FRONT)
     * =============================== */
    public function crear_noticia_front()
    {

        if (!current_user_can('manage_options'))
            wp_die('No autorizado');

        if (
            !isset($_POST['noticia_front_nonce']) ||
            !wp_verify_nonce($_POST['noticia_front_nonce'], 'crear_noticia_front')
        ) {
            wp_die('Nonce inválido');
        }

        $post_id = wp_insert_post([
            'post_type' => 'noticia',
            'post_title' => sanitize_text_field($_POST['titulo']),
            'post_content' => sanitize_textarea_field($_POST['descripcion']),
            'post_status' => 'publish',
        ]);

        update_post_meta($post_id, '_categoria', sanitize_text_field($_POST['categoria']));

        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $img_id = media_handle_upload('imagen', $post_id);
        if (!is_wp_error($img_id)) {
            set_post_thumbnail($post_id, $img_id);
        }

        wp_redirect(wp_get_referer());
        exit;
    }

    /* ===============================
     * MOSTRAR NOTICIAS
     * =============================== */
    public function render_noticias()
    {

        $q = new WP_Query(['post_type' => 'noticia', 'posts_per_page' => 12]);

        ob_start();

        while ($q->have_posts()):
            $q->the_post();
            $cat = get_post_meta(get_the_ID(), '_categoria', true); ?>

            <div class="news-card">
                <?php the_post_thumbnail('large', ['class' => 'news-image']); ?>
                <div class="news-content">
                    <span class="category-badge <?= strtolower($cat) ?>"><?= esc_html($cat) ?></span>
                    <h3><?= get_the_title() ?></h3>
                    <p><?= wp_trim_words(get_the_content(), 28) ?></p>
                </div>
            </div>

        <?php endwhile;

        wp_reset_postdata();
        return ob_get_clean();
    }
}

new Actualidad_Noticias();
