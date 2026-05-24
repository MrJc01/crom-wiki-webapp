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
<script src="https://cdn.tailwindcss.com?plugins=typography"></script>
<script src="https://unpkg.com/htmx.org@1.9.12"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Google Font Outfit -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- material icons -->
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script>
    // Customização do Tailwind para usar Outfit como fonte padrão e cores ricas
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Outfit', 'sans-serif'],
                    mono: ['JetBrains Mono', 'Fira Code', 'Menlo', 'monospace'],
                },
                typography: (theme) => ({
                    DEFAULT: {
                        css: {
                            '--tw-prose-body': '#cbd5e1', // slate-300
                            '--tw-prose-headings': '#f1f5f9', // slate-100
                            '--tw-prose-links': '#38bdf8', // sky-400
                            '--tw-prose-bold': '#ffffff',
                            '--tw-prose-quotes': '#94a3b8', // slate-400
                            '--tw-prose-quote-borders': '#0ea5e9', // sky-500
                            '--tw-prose-hr': '#1e293b',
                            '--tw-prose-th-borders': '#334155',
                            '--tw-prose-td-borders': '#1e293b',
                            a: {
                                textDecoration: 'none',
                                borderBottomWidth: '1px',
                                borderBottomColor: 'rgba(14, 165, 233, 0.2)',
                                transition: 'all 0.2s',
                                '&:hover': {
                                    color: '#7dd3fc',
                                    borderBottomColor: '#38bdf8',
                                }
                            },
                            blockquote: {
                                fontStyle: 'italic',
                                backgroundColor: 'rgba(14, 165, 233, 0.05)',
                                padding: '0.75rem 1.25rem',
                                borderRadius: '0 1rem 1rem 0',
                            },
                            code: {
                                color: '#7dd3fc',
                                backgroundColor: '#020617',
                                padding: '0.125rem 0.375rem',
                                borderRadius: '0.375rem',
                                borderWidth: '1px',
                                borderColor: 'rgba(30, 41, 59, 0.6)',
                                '&::before': { content: '""' },
                                '&::after': { content: '""' },
                            },
                            pre: {
                                backgroundColor: '#020617',
                                borderWidth: '1px',
                                borderColor: 'rgba(30, 41, 59, 0.8)',
                                borderRadius: '1rem',
                                padding: '1.25rem',
                                boxShadow: 'inset 0 2px 4px 0 rgba(0,0,0,0.06)',
                            },
                            h2: {
                                borderBottomWidth: '1px',
                                borderBottomColor: 'rgba(30, 41, 59, 0.6)',
                                paddingBottom: '0.5rem',
                                marginTop: '2rem',
                                marginBottom: '1rem',
                            },
                            img: {
                                borderRadius: '1rem',
                                boxShadow: '0 20px 25px -5px rgba(0,0,0,0.3)',
                            }
                        }
                    }
                })
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

<script>
    // Função utilitária global para adicionar botões premium de cópia em todos os elementos <pre>
    window.addCopyButtonsToPreElements = function(container) {
        if (!container) return;
        const preElements = container.querySelectorAll('pre');
        preElements.forEach((pre) => {
            // Evita adicionar o botão duplicadamente se já existir
            if (pre.querySelector('.copy-code-btn')) return;

            // Garante o posicionamento relativo para o botão absoluto
            pre.classList.add('relative', 'group');

            // Cria o botão de cópia
            const button = document.createElement('button');
            button.className = 'copy-code-btn absolute top-3 right-3 p-1.5 rounded-lg bg-slate-900/60 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition duration-200 opacity-0 group-hover:opacity-100 focus:opacity-100 focus:outline-none z-10 cursor-pointer flex items-center justify-center';
            button.title = 'Copiar Código';
            button.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H5.25m11.25 2.25l3.375 3.375m0 0l-3.375 3.375m3.375-3.375H8.25m11.25-3.562v-1.012c0-.621-.504-1.125-1.125-1.125H9.75a1.125 1.125 0 00-1.125 1.125v10.125c0 .621.504 1.125 1.125 1.125h9.75a1.125 1.125 0 001.125-1.125V11m-4.5-5.25H12" />
                </svg>
            `;

            // Adiciona o evento de clique para copiar
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                // Pega o código interno, limpando eventuais botões ou HTML espúrio
                const codeEl = pre.querySelector('code');
                const textToCopy = codeEl ? codeEl.textContent : pre.textContent;

                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Feedback visual temporário de "Copiado!"
                    button.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-emerald-400">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-[9px] font-bold font-mono text-emerald-400 ml-1">Copiado!</span>
                    `;
                    button.classList.add('border-emerald-500/30', 'bg-emerald-500/5');

                    setTimeout(() => {
                        button.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H5.25m11.25 2.25l3.375 3.375m0 0l-3.375 3.375m3.375-3.375H8.25m11.25-3.562v-1.012c0-.621-.504-1.125-1.125-1.125H9.75a1.125 1.125 0 00-1.125 1.125v10.125c0 .621.504 1.125 1.125 1.125h9.75a1.125 1.125 0 001.125-1.125V11m-4.5-5.25H12" />
                            </svg>
                        `;
                        button.classList.remove('border-emerald-500/30', 'bg-emerald-500/5');
                    }, 2000);
                }).catch(err => {
                    console.error('Falha ao copiar texto: ', err);
                });
            });

            pre.appendChild(button);
        });
    };

    // Listener para habilitar que o HTMX renderize erros (como 403, 404, 500) dentro dos containers alvo
    document.addEventListener('DOMContentLoaded', () => {
        document.body.addEventListener('htmx:beforeOnLoad', function (evt) {
            const status = evt.detail.xhr.status;
            if (status === 403 || status === 404 || status === 500) {
                evt.detail.shouldSwap = true;
                evt.detail.isError = false;
            }
        });
    });
</script>

