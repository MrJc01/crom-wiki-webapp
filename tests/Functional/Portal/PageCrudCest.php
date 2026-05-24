<?php

namespace app\tests\Functional\Portal;

use app\tests\Support\FunctionalTester;
use app\modules\page_crud\models\PageDocumented;
use yii\helpers\Url;

class PageCrudCest
{
    /**
     * Roda antes de cada teste. Garante que o usuário de teste esteja autenticado.
     */
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin123',
        ]);
        $I->seeInCurrentUrl('index-test.php');
    }

    /**
     * Testar se a página principal do page_crud carrega as abas, a árvore e as estruturas básicas.
     */
    public function testPageCrudIndexPageLoads(FunctionalTester $I)
    {
        $I->amOnRoute('page_crud/default/index');
        $I->seeResponseCodeIs(200);

        // Verifica o título central do portal Discover
        $I->see('PORTAL DE PÁGINAS DOCUMENTADAS', 'div');
        $I->see('Navegue e Pesquise no Banco', 'h2');
        
        // Verifica a presença do botão de criar página documentada
        $I->see('Criar Nova Página Documentada', 'button');
    }

    /**
     * Testar se o endpoint de visualização de páginas retorna o JSON correto.
     */
    public function testPageCrudViewPageJson(FunctionalTester $I)
    {
        // Obtém o primeiro caminho das páginas
        $page = PageDocumented::find()->one();
        $I->assertNotNull($page, 'Deve existir pelo menos uma página inicial criada na migração.');

        $I->amOnRoute('page_crud/default/view', ['id' => $page->id]);
        $I->seeResponseCodeIs(200);
        
        // Verifica se os dados estruturais do JSON estão presentes na resposta
        $I->see('"slug"');
        $I->see('"title"');
        $I->see('"content"');
    }

    /**
     * Testar a gravação de conteúdo Markdown de Página Documentada.
     */
    public function testPageCrudSaveAction(FunctionalTester $I)
    {
        // Garante idempotência limpando registro anterior se houver
        $existing = PageDocumented::findOne(['slug' => 'testes/pagina-automatizada']);
        if ($existing) {
            $existing->delete();
        }

        $I->amOnRoute('page_crud/default/save', [
            'slug' => 'testes/pagina-automatizada',
            'title' => 'Página Automatizada de Teste',
            'content' => '# Conteúdo Automatizado' . "\n\n" . 'Texto do teste funcional.',
            'category' => 'Desenvolvedor',
            'is_public' => 1, // Envia marcado como público
            'admin_ids' => '1' // Mapeia administrador de ID 1
        ]);
        
        $I->seeResponseCodeIs(200);
        $I->see('"success":true');
        $I->see('"message"');

        // Verifica se a gravação persistiu no banco de dados de teste
        $updatedPage = PageDocumented::findOne(['slug' => 'testes/pagina-automatizada']);
        $I->assertNotNull($updatedPage);
        $I->assertEquals('Página Automatizada de Teste', $updatedPage->title);
        $I->assertStringContainsString('Texto do teste funcional.', $updatedPage->content);
        $I->assertEquals(1, $updatedPage->is_public);
        $I->assertContains(1, $updatedPage->adminIds);
    }

    /**
     * Testar a exclusão de uma Página Documentada.
     */
    public function testPageCrudDeleteAction(FunctionalTester $I)
    {
        // Garante idempotência limpando registro anterior se houver
        $existing = PageDocumented::findOne(['slug' => 'testes/pagina-deletavel']);
        if ($existing) {
            $existing->delete();
        }

        // Cria uma página para deletar
        $page = new PageDocumented();
        $page->slug = 'testes/pagina-deletavel';
        $page->title = 'Página Deletável';
        $page->content = 'Conteúdo deletável.';
        $page->created_by = 'admin';
        $page->category = 'Geral';
        $page->save();

        $I->amOnRoute('page_crud/default/delete', ['id' => $page->id]);
        $I->seeResponseCodeIs(200);
        $I->see('"success":true');

        // Garante que o item foi realmente removido da persistência
        $deletedPage = PageDocumented::findOne($page->id);
        $I->assertNull($deletedPage);
    }
}
