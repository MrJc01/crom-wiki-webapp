<?php

namespace app\tests\Functional\Portal;

use app\tests\Support\FunctionalTester;
use app\modules\page_crud\models\PageDocumented;

class PublicPageCest
{
    /**
     * Roda antes de cada teste. Garante que os registros de teste existam no banco com a flag correta.
     */
    public function _before(FunctionalTester $I)
    {
        // Garante a existência da página pública
        $publicPage = PageDocumented::findOne(['slug' => 'testes/pagina-publica-teste']);
        if (!$publicPage) {
            $publicPage = new PageDocumented();
            $publicPage->slug = 'testes/pagina-publica-teste';
            $publicPage->title = 'Página Pública Homologada';
            $publicPage->content = '# Artigo Público' . "\n\n" . 'Conteúdo acessível a visitantes sem autenticação.';
            $publicPage->created_by = 'admin';
            $publicPage->category = 'Produtividade';
        }
        $publicPage->is_public = 1; // Força pública
        $publicPage->save();

        // Garante a existência da página privada
        $privatePage = PageDocumented::findOne(['slug' => 'testes/pagina-privada-teste']);
        if (!$privatePage) {
            $privatePage = new PageDocumented();
            $privatePage->slug = 'testes/pagina-privada-teste';
            $privatePage->title = 'Página Privada Homologada';
            $privatePage->content = '# Artigo Privado' . "\n\n" . 'Conteúdo restrito a membros autenticados.';
            $privatePage->created_by = 'admin';
            $privatePage->category = 'Segurança';
        }
        $privatePage->is_public = 0; // Força privada
        $privatePage->save();
    }

    /**
     * Testar se um visitante anônimo consegue ler uma página documentada marcada como pública (/p/slug)
     */
    public function testAnonymousUserCanAccessPublicPage(FunctionalTester $I)
    {
        // Acessa diretamente a URL pública curta
        $I->amOnPage('/p/testes/pagina-publica-teste');
        $I->seeResponseCodeIs(200);
        
        // Assegura elementos do leitor público premium
        $I->see('Página Pública Homologada', 'h1');
        $I->see('CROM Documentação Pública');
        $I->see('Acessar Mainframe');
    }

    /**
     * Testar se um visitante anônimo é redirecionado ao login ao tentar ler uma página privada (/p/slug)
     */
    public function testAnonymousUserIsRedirectedFromPrivatePage(FunctionalTester $I)
    {
        // Tenta acessar a URL privada curta
        $I->amOnPage('/p/testes/pagina-privada-teste');
        
        // Assegura que o Yii2 redirecionou o usuário seguro para a tela de login
        $I->seeInCurrentUrl('/login');
        $I->see('Portal CROM', 'h1');
        $I->seeElement('#login-form');
    }
}
