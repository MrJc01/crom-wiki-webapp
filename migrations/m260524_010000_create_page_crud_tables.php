<?php

use yii\db\Migration;

class m260524_010000_create_page_crud_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Criar tabela page_crud_pages
        $this->createTable('page_crud_pages', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull()->unique(),
            'title' => $this->string(255)->notNull(),
            'content' => $this->text()->notNull(),
            'created_by' => $this->string(255)->notNull(),
            'admin_id' => $this->integer()->null(),
            'category' => $this->string(100)->notNull()->defaultValue('Geral'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Criar índice na coluna slug para pesquisas rápidas
        $this->createIndex('idx-page_crud_pages-slug', 'page_crud_pages', 'slug');

        // 2. Desativar o módulo wiki na tabela core_modules
        $this->update('core_modules', [
            'is_active' => false
        ], ['id' => 'wiki']);

        // 3. Cadastrar o novo módulo page_crud ("Página Documentada")
        $this->insert('core_modules', [
            'id' => 'page_crud',
            'name' => 'Página Documentada',
            'entry_point' => 'page_crud/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>',
            'sort_order' => 2,
            'is_active' => true,
            'required_permission' => 'access-wiki' // Reutiliza a permissão de acesso para facilidade de RBAC
        ]);

        // 4. Inserir algumas páginas organizacionais de banco de dados iniciais
        $time = time();
        
        $this->insert('page_crud_pages', [
            'slug' => 'membros/guia-sre',
            'title' => 'Guia SRE do Portal Crom',
            'content' => "# 💻 Guia SRE do Portal Crom\n\nDiretrizes de sustentabilidade de infraestrutura, rotinas de backup de banco SQLite locais e monitoramento de concorrência no modo Write-Ahead Logging (WAL).\n\n### Práticas de Backup\n- **Diário:** Rotação de dumps incrementais locais.\n- **Concorrência:** Ajuste de timeout em transações sqlite concorrentes para até 5000ms.\n\n---\n*Página indexada em banco de dados.*",
            'created_by' => 'admin',
            'admin_id' => 1,
            'category' => 'Desenvolvedor',
            'created_at' => $time,
            'updated_at' => $time
        ]);

        $this->insert('page_crud_pages', [
            'slug' => 'governanca/financas',
            'title' => 'Finanças da Comunidade',
            'content' => "# 📊 Finanças da Comunidade CROM\n\nNossos fundos de desenvolvimento para suporte de VPS locais baseadas em BCoins e doações.\n\n### Balanços Gerais\n- **Ativos:** 15.000 BCoins em reserva fria.\n- **Hospedagem:** Custos operacionais cobertos por doações voluntárias.\n\n---\n*Página indexada em banco de dados.*",
            'created_by' => 'admin',
            'admin_id' => 1,
            'category' => 'Produtividade',
            'created_at' => $time,
            'updated_at' => $time
        ]);

        $this->insert('page_crud_pages', [
            'slug' => 'seguranca/chaves-ssh',
            'title' => 'Gerenciamento de Chaves SSH',
            'content' => "# 🔑 Gerenciamento de Chaves SSH\n\nProcesso seguro de revogação e distribuição de chaves públicas para os nós do Docker Swarm da Crom.\n\n### Passos de Adição\n1. Enviar chave pública formatada em RFC4716.\n2. Aguardar aprovação do administrador do módulo.\n\n---\n*Página indexada em banco de dados.*",
            'created_by' => 'admin',
            'admin_id' => 1,
            'category' => 'Segurança',
            'created_at' => $time,
            'updated_at' => $time
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Excluir módulo e reativar a wiki
        $this->delete('core_modules', ['id' => 'page_crud']);
        $this->update('core_modules', ['is_active' => true], ['id' => 'wiki']);

        // Dropar a tabela page_crud_pages
        $this->dropIndex('idx-page_crud_pages-slug', 'page_crud_pages');
        $this->dropTable('page_crud_pages');
    }
}
