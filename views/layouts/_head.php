<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use app\assets\AppAsset;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(
    ['charset' => Yii::$app->charset],
    'charset',
);
$this->registerMetaTag(
    [
        'name' => 'viewport',
        'content' => 'width=device-width, initial-scale=1',
    ],
);
if (!empty($this->params['meta_description'])) {
    $this->registerMetaTag(
        [
            'name' => 'description',
            'content' => $this->params['meta_description'],
        ],
    );
}
if (!empty($this->params['meta_keywords'])) {
    $this->registerMetaTag(
        [
            'name' => 'keywords',
            'content' => $this->params['meta_keywords'],
        ],
    );
}
$this->registerLinkTag(
    [
        'rel' => 'icon',
        'type' => 'image/png',
        'href' => Yii::getAlias('@web/crom-logo.png'),
    ],
);
$this->registerLinkTag(
    [
        'rel' => 'apple-touch-icon',
        'href' => Yii::getAlias('@web/crom-logo.png'),
    ],
);
?>

<!-- CDNs Globais de Alta Performance para Visual Premium SPA -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/htmx.org@1.9.12"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Google Font Outfit -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<script>
    // Customização do Tailwind para usar Outfit como fonte padrão e cores ricas
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Outfit', 'sans-serif'],
                },
            }
        }
    }
</script>

<style>
    /* Estilos utilitários adicionais e scrollbar limpa */
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Loader Principal de Alta Performance */
    #page-loader-root {
        position: fixed;
        inset: 0;
        z-index: 999999;
        background-color: #020617; /* bg-slate-950 */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.4s ease;
    }
    .loader-logo-glow {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: loader-pulse 2s infinite ease-in-out;
    }
    .loader-glow-ring {
        position: absolute;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, transparent 70%);
        animation: glow-rotate 6s infinite linear;
    }
    @keyframes loader-pulse {
        0%, 100% { transform: scale(0.96); opacity: 0.85; }
        50% { transform: scale(1.04); opacity: 1; }
    }
    @keyframes glow-rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Barra de Progresso Superior Dinâmica (YouTube/GitHub Style) */
    #top-progress-bar {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0%;
        z-index: 1000000;
        background: linear-gradient(90deg, #38bdf8, #6366f1, #34a853);
        box-shadow: 0 0 10px rgba(56, 189, 248, 0.6);
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
        opacity: 0;
        pointer-events: none;
    }
</style>

