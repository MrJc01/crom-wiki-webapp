<?php
use yii\helpers\Url;
?>
<script>
// Handler 1: Controle de Usuários (Membros)
window.adminUsersHandler = function(initialUsers, allPermissions) {
    return {
        users: initialUsers,
        allPermissions: allPermissions,
        showUserModal: false,
        showPermModal: false,
        
        // Formulário do Usuário
        userId: '',
        username: '',
        password: '',
        isEditing: false,
        isGuardiao: false,
        isPilar: false,
        isForja: false,
        isMembro: true,
        
        // Formulário de Permissões
        permUserId: '',
        permUsername: '',
        permSelected: [],
        
        // Mensagens de feedback
        successMsg: '',
        errorMsg: '',

        init() {
            // Listener para atualizar a lista se houver modificações externas
            document.body.addEventListener('adminUsersUpdated', () => {
                this.successMsg = 'Lista de usuários atualizada.';
            });
        },
        
        openCreateModal() {
            this.userId = '';
            this.username = '';
            this.password = '';
            this.isEditing = false;
            this.isGuardiao = false;
            this.isPilar = false;
            this.isForja = false;
            this.isMembro = true;
            this.successMsg = '';
            this.errorMsg = '';
            this.showUserModal = true;
        },
        
        openEditModal(user) {
            this.userId = user.id;
            this.username = user.username;
            this.password = '';
            this.isEditing = true;
            this.isGuardiao = !!user.is_guardiao;
            this.isPilar = !!user.is_pilar;
            this.isForja = !!user.is_forja;
            this.isMembro = !!user.is_membro;
            this.successMsg = '';
            this.errorMsg = '';
            this.showUserModal = true;
        },
        
        openPermModal(user) {
            this.permUserId = user.id;
            this.permUsername = user.username;
            this.permSelected = [...user.permissions];
            this.successMsg = '';
            this.errorMsg = '';
            this.showPermModal = true;
        },
        
        saveUser() {
            this.successMsg = '';
            this.errorMsg = '';
            const formData = new FormData();
            formData.append('id', this.userId);
            formData.append('username', this.username);
            formData.append('password', this.password);
            formData.append('is_guardiao', this.isGuardiao ? '1' : '0');
            formData.append('is_pilar', this.isPilar ? '1' : '0');
            formData.append('is_forja', this.isForja ? '1' : '0');
            formData.append('is_membro', this.isMembro ? '1' : '0');
            formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
            
            fetch('<?= Url::to(['/admin/default/save-user']) ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.successMsg = data.message;
                    this.showUserModal = false;
                    // Recarrega o painel administrativo de forma isomórfica após 1s
                    setTimeout(() => { this.reloadAdminPanel(); }, 1000);
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.errorMsg = 'Erro de rede ao salvar usuário.';
            });
        },
        
        toggleStatus(user) {
            this.successMsg = '';
            this.errorMsg = '';
            
            fetch('<?= Url::to(['/admin/default/toggle-user-status']) ?>?id=' + user.id)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    user.status = data.status;
                    this.successMsg = data.message;
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.errorMsg = 'Erro de rede ao alterar status do usuário.';
            });
        },
        
        savePermissions() {
            this.successMsg = '';
            this.errorMsg = '';
            
            const formData = new FormData();
            formData.append('user_id', this.permUserId);
            this.permSelected.forEach(p => formData.append('permissions[]', p));
            formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
            
            fetch('<?= Url::to(['/admin/default/update-user-permissions']) ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.successMsg = data.message;
                    this.showPermModal = false;
                    // Atualiza as permissões na lista local
                    const user = this.users.find(u => u.id === this.permUserId);
                    if (user) {
                        user.permissions = [...this.permSelected];
                    }
                    setTimeout(() => { this.reloadAdminPanel(); }, 1000);
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.errorMsg = 'Erro de rede ao salvar permissões.';
            });
        },
        
        reloadAdminPanel() {
            const btn = document.getElementById('btn-nav-admin');
            if (btn) {
                btn.removeAttribute('hx-loaded');
                btn.click();
            }
        }
    };
};

// Handler 2: Portal Editor (Settings)
window.adminSettingsHandler = function(initialSettings) {
    return {
        settings: initialSettings,
        successMsg: '',
        errorMsg: '',
        isSaving: false,
        
        ecosystemCards: [],
        alignmentCards: [],
        bannerCards: [],
        
        init() {
            try {
                this.ecosystemCards = JSON.parse(this.settings.ecosystem_cards_json || '[]');
            } catch(e) {
                this.ecosystemCards = [];
            }
            try {
                this.alignmentCards = JSON.parse(this.settings.alignment_cards_json || '[]');
            } catch(e) {
                this.alignmentCards = [];
            }
            try {
                this.bannerCards = JSON.parse(this.settings.dashboard_banners_json || '[]');
            } catch(e) {
                this.bannerCards = [];
            }
        },
        
        addEcosystemCard() {
            this.ecosystemCards.push({
                nome: '',
                tag: '',
                tag_style: 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                bg_style: 'bg-gradient-to-br from-purple-950/40 to-slate-900 border-purple-900/60 hover:border-purple-500/40',
                icone: '📦',
                descricao: '',
                btn_texto: 'Acessar',
                btn_style: 'bg-purple-600 hover:bg-purple-500 text-white',
                disabled: false,
                tab: 'paravoce'
            });
        },
        
        removeEcosystemCard(index) {
            this.ecosystemCards.splice(index, 1);
        },
        
        moveEcosystemCard(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= this.ecosystemCards.length) return;
            const item = this.ecosystemCards.splice(index, 1)[0];
            this.ecosystemCards.splice(newIndex, 0, item);
        },
        
        addAlignmentCard() {
            this.alignmentCards.push({
                titulo: '',
                tag: '',
                descricao: '',
                btn_texto: 'Acessar',
                tab: 'paravoce'
            });
        },
        
        removeAlignmentCard(index) {
            this.alignmentCards.splice(index, 1);
        },
        
        moveAlignmentCard(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= this.alignmentCards.length) return;
            const item = this.alignmentCards.splice(index, 1)[0];
            this.alignmentCards.splice(newIndex, 0, item);
        },

        addBannerCard() {
            this.bannerCards.push({
                badge: '',
                titulo_principal: '',
                titulo_accent: '',
                descricao: '',
                btn_texto: 'Ver',
                btn_tab: 'page_crud',
                gradiente: 'from-sky-400/20 to-indigo-500/0'
            });
        },
        
        removeBannerCard(index) {
            this.bannerCards.splice(index, 1);
        },
        
        moveBannerCard(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= this.bannerCards.length) return;
            const item = this.bannerCards.splice(index, 1)[0];
            this.bannerCards.splice(newIndex, 0, item);
        },
        
        saveSettings() {
            this.successMsg = '';
            this.errorMsg = '';
            this.isSaving = true;
            
            // Serializa os cartões de volta para JSON para envio
            this.settings.ecosystem_cards_json = JSON.stringify(this.ecosystemCards);
            this.settings.alignment_cards_json = JSON.stringify(this.alignmentCards);
            this.settings.dashboard_banners_json = JSON.stringify(this.bannerCards);
            
            const formData = new FormData();
            for (const [key, val] of Object.entries(this.settings)) {
                formData.append('settings[' + key + ']', val);
            }
            formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
            
            fetch('<?= Url::to(['/admin/default/save-settings']) ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.isSaving = false;
                if (data.success) {
                    this.successMsg = data.message;
                    // Dispara evento global para o portal saber que as configuracoes foram salvas
                    document.body.dispatchEvent(new CustomEvent('portalSettingsUpdated'));
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.isSaving = false;
                this.errorMsg = 'Erro de rede ao salvar as configurações.';
            });
        }
    };
};

// Handler 3: Gerenciador de Módulos
window.adminModulesHandler = function(initialModules) {
    return {
        modules: initialModules,
        successMsg: '',
        errorMsg: '',
        isToggling: false,
        
        showConfigModal: false,
        configModuleId: '',
        configModuleName: '',
        configRaw: '',
        configHasSchema: false,
        configSchema: [],
        configValues: {},
        isSavingConfig: false,
        
        openConfigModal(module) {
            this.configModuleId = module.id;
            this.configModuleName = module.name;
            this.configRaw = '';
            this.configHasSchema = false;
            this.configSchema = [];
            this.configValues = {};
            this.successMsg = '';
            this.errorMsg = '';
            this.showConfigModal = true;
            
            fetch('<?= Url::to(['/admin/default/get-module-config']) ?>?id=' + module.id)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.configHasSchema = !!data.has_schema;
                    this.configSchema = data.schema || [];
                    this.configValues = data.values || {};
                    this.configRaw = data.raw_config || '{}';
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.errorMsg = 'Erro de rede ao buscar configuração do módulo.';
            });
        },
        
        saveConfig() {
            this.successMsg = '';
            this.errorMsg = '';
            this.isSavingConfig = true;
            
            const formData = new FormData();
            formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
            
            if (this.configHasSchema) {
                // Envia como string JSON do objeto values para manter tipos boolean/number de forma segura
                formData.append('values', JSON.stringify(this.configValues));
            } else {
                // Valida JSON localmente antes do envio
                try {
                    JSON.parse(this.configRaw);
                } catch(e) {
                    this.errorMsg = 'JSON inválido no editor: ' + e.message;
                    this.isSavingConfig = false;
                    return;
                }
                formData.append('config', this.configRaw);
            }
            
            fetch('<?= Url::to(['/admin/default/save-module-config']) ?>?id=' + this.configModuleId, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.isSavingConfig = false;
                if (data.success) {
                    this.successMsg = data.message;
                    this.showConfigModal = false;
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.isSavingConfig = false;
                this.errorMsg = 'Erro de rede ao salvar configuração do módulo.';
            });
        },
        
        toggleModule(module) {
            if (module.id === 'admin') {
                this.errorMsg = 'Não é permitido desativar o módulo de administração.';
                return;
            }
            
            this.successMsg = '';
            this.errorMsg = '';
            this.isToggling = true;
            
            fetch('<?= Url::to(['/admin/default/toggle-module']) ?>?id=' + module.id)
            .then(res => res.json())
            .then(data => {
                this.isToggling = false;
                if (data.success) {
                    module.is_active = data.is_active;
                    this.successMsg = data.message;
                    // Dispara evento global para o portal saber que os modulos foram alterados
                    document.body.dispatchEvent(new CustomEvent('portalModulesUpdated'));
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.isToggling = false;
                this.errorMsg = 'Erro de rede ao alterar status do módulo.';
            });
        }
    };
};

// Handler 4: Auditoria de Logs SRE
window.adminLogsHandler = function() {
    return {
        isLoading: false,
        logsContent: '',
        
        loadLogs() {
            this.isLoading = true;
            fetch('<?= Url::to(['/admin/default/get-logs']) ?>')
            .then(res => res.text())
            .then(text => {
                this.logsContent = text;
                this.isLoading = false;
                // Scroll do terminal para o fim após o carregamento
                this.$nextTick(() => {
                    const terminal = this.$refs.terminalContainer;
                    if (terminal) {
                        terminal.scrollTop = terminal.scrollHeight;
                    }
                });
            })
            .catch(err => {
                this.logsContent = '<div class="p-4 text-rose-400 font-mono text-xs">Erro de rede ao carregar os logs do sistema.</div>';
                this.isLoading = false;
            });
        },
        
        clearTerminal() {
            this.logsContent = '<div class="p-4 text-slate-500 font-mono text-xs">Terminal limpo localmente. Clique em Atualizar para buscar novos logs.</div>';
        },
        
        init() {
            this.loadLogs();
        }
    };
};
</script>
