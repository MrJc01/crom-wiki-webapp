<?php

namespace app\tests\Functional\Portal;

use app\tests\Support\FunctionalTester;
use app\modules\page_crud\models\PageDocumented;

class PrettyUrlCest
{
    /**
     * Roda antes de cada teste para autenticar o usuário
     */
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->seeResponseCodeIs(200);
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin123',
        ]);
        $I->seeInCurrentUrl('index-test.php');
    }

    /**
     * Testar se a URL amigável do Discover funciona (/discover)
     */
    public function testDiscoverPrettyUrlLoads(FunctionalTester $I)
    {
        $I->amOnPage('/discover');
        $I->seeResponseCodeIs(200);
        $I->see('Descubra os Módulos do Portal', 'h2');
        $I->seeInSource('page_crud');
    }

    /**
     * Testar se a URL amigável do dashboard inicial funciona (/dashboard)
     */
    public function testDashboardPrettyUrlLoads(FunctionalTester $I)
    {
        $I->amOnPage('/dashboard');
        $I->seeResponseCodeIs(200);
        $I->see('CROM', 'h1');
        $I->see('Crie e colabore em documentos Markdown locais', 'p');
    }

    /**
     * Testar se a URL amigável de listagem do page_crud funciona (/p)
     */
    public function testPageCrudPrettyUrlLoads(FunctionalTester $I)
    {
        $I->amOnPage('/p');
        $I->seeResponseCodeIs(200);
        $I->see('PORTAL DE PÁGINAS DOCUMENTADAS', 'div');
        $I->see('Navegue e Pesquise no Banco', 'h2');
    }
}
