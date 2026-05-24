<?php

use yii\db\Migration;

class m260524_000000_add_wiki_meta_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Adicionar colunas criador e administrador de página na wiki_pages_cache
        $this->addColumn('wiki_pages_cache', 'created_by', $this->string(255)->null());
        $this->addColumn('wiki_pages_cache', 'admin_id', $this->integer()->null());
        
        // Registrar as páginas da Wiki iniciais para organização básica
        $time = time();
        
        // Página 1: membros/organizacao.md
        $this->insert('wiki_pages_cache', [
            'path' => 'membros/organizacao.md',
            'sha' => md5('# 👥 Organização de Membros CROM\n\nEste é o guia de membros.'),
            'title' => 'Organização de Membros CROM',
            'content' => "# 👥 Organização de Membros CROM\n\nBem-vindo à área de governança e organização de membros da comunidade CROM.\n\n### Diretrizes Básicas\n- **Colaboração:** Todo membro tem direito a propor melhorias nas ferramentas.\n- **Soberania:** Buscamos sempre a descentralização tecnológica.\n- **Transparência:** Decisões e finanças são expostas na Wiki.\n\n---\n*Página organizada pelo Administrador do Sistema.*",
            'last_synced_at' => $time,
            'created_by' => 'admin',
            'admin_id' => 1
        ]);

        // Página 2: governanca/processos.md
        $this->insert('wiki_pages_cache', [
            'path' => 'governanca/processos.md',
            'sha' => md5('# ⚙️ Processos de Governança CROM\n\nNossos processos de governança.'),
            'title' => 'Processos de Governança CROM',
            'content' => "# ⚙️ Processos de Governança CROM\n\nRegras de coordenação, votação e aceitação de novos módulos no Portal.\n\n### Ciclo de Módulos\n1. **Proposta:** Criação de um rascunho de ideia na Wiki.\n2. **Aprovação:** Consenso entre os administradores das páginas.\n3. **Deploy:** Ativação dinâmica via `ModuleLoader` no SQLite.\n\n---\n*Página organizada pelo Administrador do Sistema.*",
            'last_synced_at' => $time,
            'created_by' => 'admin',
            'admin_id' => 1
        ]);
        
        // Criar arquivos físicos iniciais no disco também para manter a paridade GitOps local
        $docsDir = Yii::getAlias('@app/docs');
        if (!is_dir($docsDir . '/membros')) {
            @mkdir($docsDir . '/membros', 0777, true);
        }
        if (!is_dir($docsDir . '/governanca')) {
            @mkdir($docsDir . '/governanca', 0777, true);
        }
        
        @file_put_contents($docsDir . '/membros/organizacao.md', "# 👥 Organização de Membros CROM\n\nBem-vindo à área de governança e organização de membros da comunidade CROM.\n\n### Diretrizes Básicas\n- **Colaboração:** Todo membro tem direito a propor melhorias nas ferramentas.\n- **Soberania:** Buscamos sempre a descentralização tecnológica.\n- **Transparência:** Decisões e finanças são expostas na Wiki.\n\n---\n*Página organizada pelo Administrador do Sistema.*");
        @file_put_contents($docsDir . '/governanca/processos.md', "# ⚙️ Processos de Governança CROM\n\nRegras de coordenação, votação e aceitação de novos módulos no Portal.\n\n### Ciclo de Módulos\n1. **Proposta:** Criação de um rascunho de ideia na Wiki.\n2. **Aprovação:** Consenso entre os administradores das páginas.\n3. **Deploy:** Ativação dinâmica via `ModuleLoader` no SQLite.\n\n---\n*Página organizada pelo Administrador do Sistema.*");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wiki_pages_cache', 'created_by');
        $this->dropColumn('wiki_pages_cache', 'admin_id');
        
        // Deleta os registros criados
        $this->delete('wiki_pages_cache', ['path' => 'membros/organizacao.md']);
        $this->delete('wiki_pages_cache', ['path' => 'governanca/processos.md']);
    }
}
