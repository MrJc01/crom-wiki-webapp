<?php

namespace app\tests\Functional\Portal;

use app\tests\Support\FunctionalTester;

class PortalPagesCest
{
    /**
     * Testar se a página inicial pública é exibida com sucesso para visitantes anônimos.
     */
    public function testPublicHomePageIsDisplayed(FunctionalTester $I)
    {
        $I->amOnRoute('site/index');
        $I->seeResponseCodeIs(200);
        $I->see('CROM WIKI', 'span');
        $I->see('Soberania tecnológica não se pede,', 'h1');
        $I->see('wiki.crom.run', 'div');
        $I->see('Acessar o Portal', 'a');
    }

    /**
     * Testar se a página de login é exibida corretamente e possui a identidade visual CROM.
     */
    public function testLoginPageIsDisplayed(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->seeResponseCodeIs(200);
        $I->see('Portal CROM', 'h1');
        $I->see('Soberania não se pede, constrói-se.', 'p');
        $I->seeElement('form#login-form');
        $I->seeElement('input[name="LoginForm[username]"]');
        $I->seeElement('input[name="LoginForm[password]"]');
    }

    /**
     * Testar se o login com credenciais incorretas falha e exibe a mensagem de erro.
     */
    public function testLoginWithInvalidCredentials(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'usuario_inexistente',
            'LoginForm[password]' => 'senha_errada',
        ]);
        $I->seeResponseCodeIs(200); // Permanece na página de login
        $I->see('Nome de usuário ou senha incorretos.', '.text-rose-400');
    }

    /**
     * Testar login com credenciais corretas, redirecionamento para o Dashboard
     * e presença dos componentes visuais do portal.
     */
    public function testLoginSuccessfullyAndAccessDashboard(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin123',
        ]);

        // Redireciona com sucesso para a página inicial (Dashboard)
        $I->seeInCurrentUrl('index-test.php');
        $I->seeResponseCodeIs(200);

        // Verifica elementos estruturais do Workspace SPA estilizado em TailwindCSS
        $I->see('CROM', 'h1');
        $I->see('Crie e colabore em documentações locais em Markdown com autonomia radical e controle de governança direto na base.', 'p');

        // Verifica a presença do Dock lateral (Sidebar)
        $I->seeElement('aside');
        
        // Verifica a presença do cabeçalho de abas reativas
        $I->seeElement('header');
        $I->see('Para você', 'span');

        // Verifica a presença da badge online de presença atômica
        $I->seeElement('#online-badge');
        $I->see('ONLINE', '#online-badge');
    }

    /**
     * Testar fluxo de logout do usuário logado.
     */
    public function testLogoutSuccessfully(FunctionalTester $I)
    {
        // Primeiro realiza o login
        $I->amOnRoute('site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin123',
        ]);

        $I->seeInCurrentUrl('index-test.php');

        // Dispara o logout
        $I->amOnRoute('site/logout');

        // Deve deslogar e redirecionar para a Landing Page pública
        $I->seeInCurrentUrl('index-test.php');
        $I->see('CROM WIKI', 'span');
    }
}
