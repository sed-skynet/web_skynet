function skynet_eventos_destacados_shortcode() {

    static $css_loaded = false;

    /* ==============================
       CSS – CARGA ÚNICA
    ============================== */
    if (!$css_loaded) {
        $css_loaded = true;
        echo '<style>
        /* ==============================
           EVENTOS DESTACADOS
        ============================== */
#eventos{
    position:relative;
    z-index:5;
    background:#f9fafb;
    padding:4rem clamp(16px, 4vw, 40px);
    margin:0 auto;
    max-width:100vw;
    border-radius:2.5rem;
    overflow:hidden;
}

/* Ajustes responsive del contenedor principal */
@media (max-width: 968px) {
    #eventos{
        padding:3rem clamp(16px, 4vw, 40px);
        border-radius:2rem;
    }
}

@media (max-width: 640px) {
    #eventos{
        padding:2.5rem clamp(16px, 4vw, 40px);
        border-radius:1.5rem;
    }
}

.contenedor-eventos{
    max-width:1280px;
    margin:0 auto;
    padding:0;
    width:100%;
}

.eventos-header{
    text-align:center;
    margin-bottom:3.5rem;
    padding:0 1rem;
}

@media (max-width: 640px) {
    .eventos-header{
        margin-bottom:2.5rem;
    }
}

.eventos-header h2{
    font-size:clamp(2rem, 4vw, 2.5rem);
    color:#1a365d!important;
    font-weight:800;
    margin-bottom:0.75rem;
    line-height:1.2;
}

.eventos-header p{
    color:#4b5563!important;
    font-size:clamp(1rem, 2vw, 1.1rem);
    max-width:600px;
    margin:0 auto;
    line-height:1.6;
}

/* ? GRID MEJORADO - 3 COLUMNAS */
.eventos-grid{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:2rem;
    max-width:1100px;
    margin:0 auto;
    padding:0 1rem;
}

/* Responsive: 2 columnas en tablets */
@media (max-width: 968px) {
    .eventos-grid{
        grid-template-columns:repeat(2, 1fr);
        gap:1.75rem;
        max-width:800px;
    }
}

/* Responsive: 1 columna en móviles */
@media (max-width: 640px) {
    .eventos-grid{
        grid-template-columns:1fr;
        gap:1.5rem;
        max-width:450px;
        padding:0 0.5rem;
    }
}

.evento-card{
    background:#ffffff!important;
    border-radius:1.5rem;
    overflow:hidden;
    box-shadow:0 4px 20px rgba(0,0,0,.08);
    transition:all .35s cubic-bezier(0.4, 0, 0.2, 1);
    display:flex;
    flex-direction:column;
    height:100%;
    will-change:transform;
}

.evento-card:hover{
    transform:translateY(-8px);
    box-shadow:0 12px 40px rgba(0,0,0,.15);
}

@media (max-width: 640px) {
    .evento-card:hover{
        transform:translateY(-4px);
    }
}

.card-imagen{
    position:relative;
    height:240px;
    background:#f1f5f9;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
}

@media (max-width: 640px) {
    .card-imagen{
        height:200px;
    }
}

.card-imagen img{
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center 0%;
    transition:transform .4s cubic-bezier(0.4, 0, 0.2, 1);
}

.evento-card:hover .card-imagen img{
    transform:scale(1.05);
}

.card-overlay{
    position:absolute;
    top:1rem;
    right:1rem;
    z-index:2;
}

@media (max-width: 640px) {
    .card-overlay{
        top:0.75rem;
        right:0.75rem;
    }
}

.categoria{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;
    padding:.5rem 1.2rem;
    border-radius:30px;
    font-size:.85rem;
    font-weight:700;
    white-space:nowrap;
    box-shadow:0 4px 12px rgba(37,99,235,.3);
    transition:all .3s ease;
}

@media (max-width: 640px) {
    .categoria{
        padding:.4rem 1rem;
        font-size:.8rem;
    }
}

.evento-card:hover .categoria{
    transform:scale(1.05);
}

.card-contenido{
    padding:1.75rem;
    display:flex;
    flex-direction:column;
    flex-grow:1;
}

@media (max-width: 640px) {
    .card-contenido{
        padding:1.5rem;
    }
}

.card-contenido h3{
    color:#1a365d!important;
    font-size:clamp(1.1rem, 2vw, 1.2rem);
    margin-bottom:.75rem;
    font-weight:700;
    line-height:1.4;
    min-height:2.8em;
}

.card-contenido p{
    color:#4b5563!important;
    font-size:.95rem;
    line-height:1.6;
    margin-bottom:1rem;
    flex-grow:1;
}

@media (max-width: 640px) {
    .card-contenido p{
        font-size:.9rem;
    }
}

.card-meta{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-top:1px solid #e5e7eb;
    padding-top:1rem;
    font-size:.88rem;
    color:#6b7280;
    margin-top:auto;
    gap:0.5rem;
}

@media (max-width: 640px) {
    .card-meta{
        font-size:.85rem;
        padding-top:0.875rem;
    }
}

.card-meta span:first-child{
    font-weight:600;
    color:#1f2937;
}

.card-meta span:last-child{
    color:#2563eb;
    font-weight:500;
    text-align:right;
}

/* Animación de entrada */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.evento-card {
    animation: fadeInUp 0.6s ease forwards;
}

.evento-card:nth-child(1) { animation-delay: 0.1s; opacity: 0; }
.evento-card:nth-child(2) { animation-delay: 0.2s; opacity: 0; }
.evento-card:nth-child(3) { animation-delay: 0.3s; opacity: 0; }

/* ================= BOTÓN VER TODAS ================= */
.eventos-footer{
    text-align:center;
    margin-top:2.5rem;
}

.btn-ver-noticias{
    display:inline-block;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff!important;
    padding:0.9rem 1.8rem;
    border-radius:40px;
    font-weight:600;
    font-size:.95rem;
    text-decoration:none!important;
    transition:all .25s ease;
    box-shadow:0 6px 18px rgba(37,99,235,.25);
}

.btn-ver-noticias:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(37,99,235,.35);
}
        </style>';
    }

    /* ==============================
       QUERY
    ============================== */
$query = new WP_Query([
    'post_type'      => 'noticia',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC'
]);
    if (!$query->have_posts()) return '';

    /* ==============================
       HTML
    ============================== */
    ob_start(); ?>

    <section id="eventos" class="zona-nosotros_e">
        <div class="contenedor-eventos">

            <header class="eventos-header">
                <h2>Eventos destacados</h2>
                <p>Un repaso a los principales encuentros, foros y colaboraciones recientes.</p>
            </header>

            <div class="eventos-grid">

                <?php while ($query->have_posts()): $query->the_post();
                    $cats = get_the_terms(get_the_ID(), 'categoria_noticia');
                    $cat  = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';
                ?>

                <article class="evento-card">
                    <div class="card-imagen">
                        <?php if (has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('large'); ?>
                        <?php else: ?>
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect fill='%23e2e8f0' width='400' height='300'/%3E%3C/svg%3E" alt="Placeholder">
                        <?php endif; ?>

                        <?php if ($cat): ?>
                            <div class="card-overlay">
                                <span class="categoria"><?php echo esc_html($cat); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-contenido">
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>

                        <div class="card-meta">
                            <span><?php echo get_the_date('Y'); ?></span>
                            <span><?php echo esc_html($cat); ?></span>
                        </div>
                    </div>
                </article>

                <?php endwhile; wp_reset_postdata(); ?>

</div>

<div class="eventos-footer">
    <a class="btn-ver-noticias" href="/actualidad/">
        Ver todas las noticias
    </a>
</div>

</div>
</section>

    <?php
    return ob_get_clean();
}
add_shortcode('eventos_destacados', 'skynet_eventos_destacados_shortcode');
