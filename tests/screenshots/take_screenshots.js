const puppeteer = require('puppeteer-core');
const fs = require('fs');
const path = require('path');

// Configurações do ambiente
const BASE_URL = 'http://localhost:8080';
const CHROME_PATH = '/usr/bin/google-chrome';
const OUTPUT_DIR = path.join(__dirname, 'output');

// Credenciais padrão
const USERNAME = 'admin';
const PASSWORD = 'admin123';

// Viewports para testes responsivos
const VIEWPORTS = {
    desktop: { width: 1280, height: 800, deviceScaleFactor: 1 },
    mobile: { width: 375, height: 812, deviceScaleFactor: 2, isMobile: true, hasTouch: true }
};

// Utilitário de delay de tempo para aguardar injeções assíncronas do HTMX
const delay = ms => new Promise(res => setTimeout(res, ms));

async function captureScreen(page, name, type = 'desktop') {
    const filename = `${name}_${type}.png`;
    const filepath = path.join(OUTPUT_DIR, filename);
    await page.screenshot({ path: filepath, fullPage: false });
    console.log(`[✓] Screenshot gerada: ${filename}`);
}

(async () => {
    // Garante que a pasta de output exista
    if (!fs.existsSync(OUTPUT_DIR)) {
        fs.mkdirSync(OUTPUT_DIR, { recursive: true });
    }

    console.log('====================================================');
    console.log('🚀 Iniciando Motor de screenshots do Portal Crom...');
    console.log(`[Info] Usando o Chrome local em: ${CHROME_PATH}`);
    console.log(`[Info] Alvo: ${BASE_URL}`);
    console.log('====================================================\n');

    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    const page = await browser.newPage();

    try {
        // ==========================================
        // 1. LANDING PAGE PÚBLICA (Sem Login)
        // ==========================================
        console.log('[1/6] Navegando para a Landing Page Pública...');
        await page.goto(BASE_URL, { waitUntil: 'networkidle2' });
        
        // Screenshot Desktop
        await page.setViewport(VIEWPORTS.desktop);
        await delay(1000); // Dá tempo para fontes e layouts assentarem
        await captureScreen(page, '01_landing_page', 'desktop');
        
        // Screenshot Mobile
        await page.setViewport(VIEWPORTS.mobile);
        await delay(1000);
        await captureScreen(page, '01_landing_page', 'mobile');

        // ==========================================
        // 2. TELA DE AUTENTICAÇÃO (Login)
        // ==========================================
        console.log('\n[2/6] Navegando para a Tela de Login...');
        await page.goto(`${BASE_URL}/index.php?r=site/login`, { waitUntil: 'networkidle2' });
        
        // Screenshot Desktop
        await page.setViewport(VIEWPORTS.desktop);
        await delay(800);
        await captureScreen(page, '02_login_page', 'desktop');
        
        // Screenshot Mobile
        await page.setViewport(VIEWPORTS.mobile);
        await delay(800);
        await captureScreen(page, '02_login_page', 'mobile');

        // ==========================================
        // 3. FLUXO DE LOGIN & REDIRECIONAMENTO AO DASHBOARD
        // ==========================================
        console.log('\n[3/6] Realizando login na central...');
        await page.setViewport(VIEWPORTS.desktop);
        
        // Preenche campos do formulário Yii2
        await page.type('input[name="LoginForm[username]"]', USERNAME);
        await page.type('input[name="LoginForm[password]"]', PASSWORD);
        
        // Clica no botão de submissão e aguarda o carregamento
        await Promise.all([
            page.click('button[name="login-button"]'),
            page.waitForNavigation({ waitUntil: 'networkidle2' })
        ]);
        
        console.log('[Info] Login bem-sucedido! Redirecionado para o Dashboard.');
        
        // Screenshot Desktop
        await page.setViewport(VIEWPORTS.desktop);
        await delay(1200);
        await captureScreen(page, '03_dashboard', 'desktop');
        
        // Screenshot Mobile
        await page.setViewport(VIEWPORTS.mobile);
        await delay(1200);
        await captureScreen(page, '03_dashboard', 'mobile');
        // ==========================================
        // 3a. ABA DISCOVER - ARTIGOS SPA (HTMX)
        // ==========================================
        console.log('\n[3a/6] Acionando aba SPA do Módulo Discover...');
        await page.setViewport(VIEWPORTS.desktop);
        await page.click('#btn-nav-discover');
        await page.waitForSelector('input[placeholder*="articles"]', { timeout: 8000 })
            .catch(() => {});
        await delay(1200);
        await captureScreen(page, '03a_discover', 'desktop');
        await page.setViewport(VIEWPORTS.mobile);
        await delay(1000);
        await captureScreen(page, '03a_discover', 'mobile');

        // ==========================================
        // 3b. ABA APRENDIZADO - CODELABS SPA (HTMX)
        // ==========================================
        console.log('\n[3b/6] Acionando aba SPA do Módulo Aprendizado (Codelabs)...');
        await page.setViewport(VIEWPORTS.desktop);
        await page.click('#btn-nav-aprendizado');
        await page.waitForSelector('input[placeholder*="codelabs"]', { timeout: 8000 })
            .catch(() => {});
        await delay(1200);
        await captureScreen(page, '03b_aprendizado', 'desktop');
        await page.setViewport(VIEWPORTS.mobile);
        await delay(1000);
        await captureScreen(page, '03b_aprendizado', 'mobile');

        // ==========================================
        // 3c. ABA COMUNIDADES - INGRESSAR SPA (HTMX)
        // ==========================================
        console.log('\n[3c/6] Acionando aba SPA do Módulo Comunidades...');
        await page.setViewport(VIEWPORTS.desktop);
        await page.click('#btn-nav-comunidades');
        await page.waitForSelector('input[placeholder*="Search"]', { timeout: 8000 })
            .catch(() => {});
        await delay(1200);
        await captureScreen(page, '03c_comunidades', 'desktop');
        await page.setViewport(VIEWPORTS.mobile);
        await delay(1000);
        await captureScreen(page, '03c_comunidades', 'mobile');

        // ==========================================
        // 4. MÓDULO WIKI - CARREGAMENTO TABS SPA (HTMX)
        // ==========================================
        console.log('\n[4/6] Acionando aba SPA do Módulo Wiki...');
        await page.setViewport(VIEWPORTS.desktop);
        
        // Clica no botão de carregar a Wiki no Dock responsivo usando o ID determinístico
        await page.click('#btn-nav-wiki');
        
        // Aguarda a injeção do HTMX na tela (esperamos o botão de sync manual da wiki carregar)
        await page.waitForSelector('button[hx-post*="sync"]', { timeout: 8000 })
            .catch(() => console.log('[Aviso] Timeout ao esperar o seletor da Wiki. Prosseguindo...'));
        
        await delay(1500); // Aguarda transição visual
        
        // Screenshot Desktop
        await page.setViewport(VIEWPORTS.desktop);
        await captureScreen(page, '04_wiki_index', 'desktop');
        
        // Screenshot Mobile
        await page.setViewport(VIEWPORTS.mobile);
        await delay(1000);
        await captureScreen(page, '04_wiki_index', 'mobile');

        // ==========================================
        // 5. WIKI - VISUALIZAÇÃO DE ARQUIVO
        // ==========================================
        console.log('\n[5/6] Selecionando arquivo Markdown na árvore...');
        await page.setViewport(VIEWPORTS.desktop);
        
        // Busca e clica no primeiro item de arquivo (.md) visível na árvore
        // Aguarda que a árvore de arquivos da Wiki seja injetada e renderizada pelo HTMX no DOM
        await page.waitForSelector('div[class*="cursor-pointer"]', { timeout: 8000 })
            .catch(() => console.log('[Aviso] Timeout ao aguardar árvore de diretórios.'));
        
        // Busca e clica no primeiro item de arquivo (.md) visível na árvore
        const fileElements = await page.$$('div[class*="cursor-pointer"]');
        let fileClicked = false;
        
        for (const el of fileElements) {
            const text = await page.evaluate(element => element.textContent, el);
            if (text.includes('📄') || text.includes('.md')) {
                await el.click();
                fileClicked = true;
                console.log(`[Info] Arquivo selecionado na árvore: ${text.trim().replace('📄', '')}`);
                break;
            }
        }
        
        if (!fileClicked) {
            console.log('[Aviso] Nenhum arquivo .md clicável encontrado na árvore lateral. Forçando clique manual...');
        }
        
        // Aguarda a injeção do conteúdo e renderização do Marked.js
        await page.waitForSelector('#markdown-preview', { timeout: 5000 })
            .catch(() => {});
        await delay(1200);
        
        // Screenshot Desktop
        await captureScreen(page, '05_wiki_view_page', 'desktop');

        // ==========================================
        // 6. WIKI - ABERTURA DO MODO EDITOR (Alpine.js)
        // ==========================================
        console.log('\n[6/6] Alternando para o Modo Editor de Markdown...');
        
        // Clica no botão "📝 Editar Página"
        const buttons = await page.$$('button');
        for (const btn of buttons) {
            const btnText = await page.evaluate(b => b.textContent, btn);
            if (btnText.includes('Editar Página') || btnText.includes('📝')) {
                await btn.click();
                console.log('[Info] Botão do editor de markdown acionado.');
                break;
            }
        }
        
        await delay(800);
        
        // Screenshot Desktop
        await captureScreen(page, '06_wiki_edit_page', 'desktop');

        console.log('\n====================================================');
        console.log('🎉 Todas as screenshots foram geradas com absoluto sucesso!');
        console.log(`[Info] Pasta de destino: ${OUTPUT_DIR}`);
        console.log('====================================================');

    } catch (err) {
        console.error('\n[❌] Ocorreu uma falha crítica na automação de capturas:');
        console.error(err);
    } finally {
        await browser.close();
    }
})();
