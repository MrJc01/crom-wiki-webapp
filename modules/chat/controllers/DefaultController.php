<?php

declare(strict_types=1);

namespace app\modules\chat\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;
use yii\helpers\Url;

class DefaultController extends Controller
{
    /**
     * Desativa layouts em requisições HTMX.
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Se for requisição HTMX (AJAX), desativa o layout
            if (Yii::$app->request->headers->has('HX-Request')) {
                $this->layout = false;
            } else {
                $this->layout = '@app/views/layouts/main';
            }
            return true;
        }
        return false;
    }

    /**
     * Dashboard do Chat (Estilo WhatsApp)
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $userId = Yii::$app->user->id;
        $db = Yii::$app->db;

        // Query otimizada para pegar salas de chat em que o usuário participa
        $rooms = $db->createCommand("
            SELECT r.id, r.name, r.is_group, r.invite_code, r.is_system,
                   (SELECT m.message FROM chat_messages m WHERE m.room_id = r.id ORDER BY m.created_at DESC, m.id DESC LIMIT 1) as last_message,
                   (SELECT m.created_at FROM chat_messages m WHERE m.room_id = r.id ORDER BY m.created_at DESC, m.id DESC LIMIT 1) as last_message_time,
                   (SELECT u.username FROM chat_room_members rm2 JOIN core_users u ON rm2.user_id = u.id WHERE rm2.room_id = r.id AND rm2.user_id != :userId LIMIT 1) as direct_username
            FROM chat_rooms r
            JOIN chat_room_members rm ON r.id = rm.room_id
            WHERE rm.user_id = :userId
            ORDER BY COALESCE(last_message_time, r.created_at) DESC
        ", [':userId' => $userId])->queryAll();

        // Lista de outros membros do sistema para iniciar novas conversas diretas
        $contacts = $db->createCommand("
            SELECT id, username 
            FROM core_users 
            WHERE id != :userId
            ORDER BY username ASC
        ", [':userId' => $userId])->queryAll();

        return $this->render('index', [
            'rooms' => $rooms,
            'contacts' => $contacts,
            'activeRoomId' => null
        ]);
    }

    /**
     * Carrega a área ativa de um chat selecionado
     */
    public function actionChatArea($roomId)
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }

        $roomId = (int)$roomId;
        $userId = Yii::$app->user->id;
        $db = Yii::$app->db;

        // Validar se o usuário é membro do chat room
        $isMember = $db->createCommand("
            SELECT COUNT(*) FROM chat_room_members WHERE room_id = :roomId AND user_id = :userId
        ", [':roomId' => $roomId, ':userId' => $userId])->queryScalar();

        if (!$isMember) {
            return '<div class="p-6 text-slate-500 text-xs font-semibold">Você não tem permissão para acessar esta sala.</div>';
        }

        // Resgatar os dados do chat room
        $room = $db->createCommand("
            SELECT r.*,
                   (SELECT u.username FROM chat_room_members rm JOIN core_users u ON rm.user_id = u.id WHERE rm.room_id = r.id AND rm.user_id != :userId LIMIT 1) as direct_username
            FROM chat_rooms r
            WHERE r.id = :roomId
        ", [':roomId' => $roomId, ':userId' => $userId])->queryOne();

        if (!$room) {
            return '<div class="p-6 text-slate-500 text-xs font-semibold">Sala não encontrada.</div>';
        }

        // Pegar todos os membros da sala (para a listagem lateral se for grupo/convites)
        $members = $db->createCommand("
            SELECT u.id, u.username 
            FROM chat_room_members rm
            JOIN core_users u ON rm.user_id = u.id
            WHERE rm.room_id = :roomId
            ORDER BY u.username ASC
        ", [':roomId' => $roomId])->queryAll();

        // Pegar todos os outros contatos (que não participam deste grupo) para poder convidá-los
        $nonMembers = $db->createCommand("
            SELECT id, username 
            FROM core_users 
            WHERE id NOT IN (SELECT user_id FROM chat_room_members WHERE room_id = :roomId)
            ORDER BY username ASC
        ", [':roomId' => $roomId])->queryAll();

        return $this->renderPartial('_chat_area', [
            'room' => $room,
            'members' => $members,
            'nonMembers' => $nonMembers
        ]);
    }

    /**
     * Lista de mensagens de uma sala (Polling de Sincronização)
     */
    public function actionMessages($roomId)
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }

        $roomId = (int)$roomId;
        $userId = Yii::$app->user->id;
        $db = Yii::$app->db;

        // Validar participação
        $isMember = $db->createCommand("
            SELECT COUNT(*) FROM chat_room_members WHERE room_id = :roomId AND user_id = :userId
        ", [':roomId' => $roomId, ':userId' => $userId])->queryScalar();

        if (!$isMember) {
            return '';
        }

        // Resgata as mensagens da sala
        $messages = $db->createCommand("
            SELECT m.*, u.username as sender_username
            FROM chat_messages m
            JOIN core_users u ON m.sender_id = u.id
            WHERE m.room_id = :roomId
            ORDER BY m.created_at ASC, m.id ASC
        ", [':roomId' => $roomId])->queryAll();

        return $this->renderPartial('_messages_list', [
            'messages' => $messages,
            'userId' => $userId
        ]);
    }

    /**
     * Envio de mensagem
     */
    public function actionSendMessage($roomId)
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }

        $roomId = (int)$roomId;
        $userId = Yii::$app->user->id;
        $request = Yii::$app->request;
        $db = Yii::$app->db;

        if ($request->isPost) {
            $messageText = trim((string)$request->post('message'));
            if ($messageText !== '') {
                // Validar participação
                $isMember = $db->createCommand("
                    SELECT COUNT(*) FROM chat_room_members WHERE room_id = :roomId AND user_id = :userId
                ", [':roomId' => $roomId, ':userId' => $userId])->queryScalar();

                if ($isMember) {
                    // Validar se a sala é de sistema
                    $isSystem = $db->createCommand("
                        SELECT is_system FROM chat_rooms WHERE id = :roomId
                    ", [':roomId' => $roomId])->queryScalar();

                    if ($isSystem) {
                        throw new ForbiddenHttpException('Você não tem permissão para enviar mensagens em canais de notificações do sistema.');
                    }

                    $db->createCommand()->insert('chat_messages', [
                        'room_id' => $roomId,
                        'sender_id' => $userId,
                        'message' => $messageText,
                        'is_system' => 0,
                        'created_at' => time()
                    ])->execute();
                }
            }
        }

        // Retorna a lista de mensagens atualizada para o HTMX injetar
        return $this->actionMessages($roomId);
    }

    /**
     * Criação de Grupo
     */
    public function actionCreateGroup()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $userId = Yii::$app->user->id;
        $request = Yii::$app->request;
        $db = Yii::$app->db;

        if ($request->isPost) {
            $groupName = trim((string)$request->post('group_name'));
            $memberIds = $request->post('members', []);

            if ($groupName !== '') {
                $inviteCode = Yii::$app->security->generateRandomString(8);
                $time = time();

                // Criar a sala
                $db->createCommand()->insert('chat_rooms', [
                    'name' => $groupName,
                    'is_group' => 1,
                    'created_by' => $userId,
                    'created_at' => $time,
                    'invite_code' => $inviteCode
                ])->execute();

                $roomId = $db->getLastInsertID();

                // Adicionar o criador como membro
                $db->createCommand()->insert('chat_room_members', [
                    'room_id' => $roomId,
                    'user_id' => $userId,
                    'joined_at' => $time
                ])->execute();

                // Adicionar os demais membros selecionados
                foreach ($memberIds as $mId) {
                    $mId = (int)$mId;
                    if ($mId !== $userId) {
                        $db->createCommand()->insert('chat_room_members', [
                            'room_id' => $roomId,
                            'user_id' => $mId,
                            'joined_at' => $time
                        ])->execute();
                    }
                }

                // Mensagem de sistema no grupo
                $db->createCommand()->insert('chat_messages', [
                    'room_id' => $roomId,
                    'sender_id' => $userId,
                    'message' => 'Grupo criado com sucesso.',
                    'is_system' => 1,
                    'created_at' => $time
                ])->execute();

                // Retorna um cabeçalho HTMX Trigger para forçar a SPA a recarregar a lista lateral de chats!
                Yii::$app->response->headers->set('HX-Trigger', 'chatRoomsUpdated');
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Inicia ou abre uma conversa privada 1to1 com outro membro
     */
    public function actionOpenPrivateChat($contactId)
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }

        $userId = Yii::$app->user->id;
        $contactId = (int)$contactId;
        $db = Yii::$app->db;

        // 1. Verificar se o contato existe
        $contactExists = $db->createCommand("SELECT COUNT(*) FROM core_users WHERE id = :cId", [':cId' => $contactId])->queryScalar();
        if (!$contactExists) {
            return '<div class="p-6 text-slate-500 text-xs font-semibold">Contato não encontrado.</div>';
        }

        // 2. Verificar se já existe uma sala 1to1 entre eles
        $roomId = $db->createCommand("
            SELECT r.id 
            FROM chat_rooms r
            JOIN chat_room_members rm1 ON r.id = rm1.room_id AND rm1.user_id = :userId
            JOIN chat_room_members rm2 ON r.id = rm2.room_id AND rm2.user_id = :contactId
            WHERE r.is_group = 0
            LIMIT 1
        ", [':userId' => $userId, ':contactId' => $contactId])->queryScalar();

        // 3. Se não existir, criar a sala 1to1
        if (!$roomId) {
            $time = time();
            $db->createCommand()->insert('chat_rooms', [
                'name' => null,
                'is_group' => 0,
                'created_by' => $userId,
                'created_at' => $time,
                'invite_code' => null
            ])->execute();

            $roomId = $db->getLastInsertID();

            // Adicionar os dois como membros
            $db->createCommand()->insert('chat_room_members', [
                'room_id' => $roomId,
                'user_id' => $userId,
                'joined_at' => $time
            ])->execute();

            $db->createCommand()->insert('chat_room_members', [
                'room_id' => $roomId,
                'user_id' => $contactId,
                'joined_at' => $time
            ])->execute();

            // Envia cabeçalho trigger para atualizar a lista lateral de chats ativos
            Yii::$app->response->headers->set('HX-Trigger', 'chatRoomsUpdated');
        }

        // Retorna a chat area correspondente para o HTMX carregar na direita da janela
        return $this->actionChatArea($roomId);
    }

    /**
     * Envia um convite de grupo no chat privado de outro membro
     */
    public function actionSendInvite()
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }

        $userId = Yii::$app->user->id;
        $request = Yii::$app->request;
        $db = Yii::$app->db;

        if ($request->isPost) {
            $groupId = (int)$request->post('group_id');
            $targetUserId = (int)$request->post('target_user_id');

            // 1. Validar se o remetente pertence ao grupo
            $isMember = $db->createCommand("
                SELECT COUNT(*) FROM chat_room_members WHERE room_id = :gId AND user_id = :userId
            ", [':gId' => $groupId, ':userId' => $userId])->queryScalar();

            if (!$isMember) {
                return '<span class="text-xs text-rose-400 font-bold">Sem permissão</span>';
            }

            // 2. Resgatar dados do grupo
            $group = $db->createCommand("
                SELECT id, name, invite_code FROM chat_rooms WHERE id = :gId AND is_group = 1
            ", [':gId' => $groupId])->queryOne();

            if (!$group) {
                return '<span class="text-xs text-rose-400 font-bold">Grupo inválido</span>';
            }

            // 3. Encontrar ou criar o chat privado (1to1) entre remetente e destinatário
            $privateRoomId = $db->createCommand("
                SELECT r.id 
                FROM chat_rooms r
                JOIN chat_room_members rm1 ON r.id = rm1.room_id AND rm1.user_id = :userId
                JOIN chat_room_members rm2 ON r.id = rm2.room_id AND rm2.user_id = :targetId
                WHERE r.is_group = 0
                LIMIT 1
            ", [':userId' => $userId, ':targetId' => $targetUserId])->queryScalar();

            $time = time();
            if (!$privateRoomId) {
                $db->createCommand()->insert('chat_rooms', [
                    'name' => null,
                    'is_group' => 0,
                    'created_by' => $userId,
                    'created_at' => $time,
                    'invite_code' => null
                ])->execute();

                $privateRoomId = $db->getLastInsertID();

                $db->createCommand()->insert('chat_room_members', [
                    'room_id' => $privateRoomId,
                    'user_id' => $userId,
                    'joined_at' => $time
                ])->execute();

                $db->createCommand()->insert('chat_room_members', [
                    'room_id' => $privateRoomId,
                    'user_id' => $targetUserId,
                    'joined_at' => $time
                ])->execute();
            }

            // 4. Inserir a mensagem estruturada do convite
            $inviteMessage = "[GROUP_INVITE]::" . $group['id'] . "::" . $group['invite_code'] . "::" . $group['name'];
            $db->createCommand()->insert('chat_messages', [
                'room_id' => $privateRoomId,
                'sender_id' => $userId,
                'message' => $inviteMessage,
                'is_system' => 0,
                'created_at' => $time
            ])->execute();

            // Avisa o frontend com trigger de atualização
            Yii::$app->response->headers->set('HX-Trigger', 'chatRoomsUpdated');

            return '<span class="text-xs text-emerald-400 font-bold">Convite Enviado!</span>';
        }
        return '';
    }

    /**
     * Permite a adesão de um membro a um grupo por meio de um código de convite
     */
    public function actionJoinGroup($code)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        $code = trim((string)$code);
        $userId = Yii::$app->user->id;
        $db = Yii::$app->db;

        // 1. Buscar o grupo pelo invite_code
        $group = $db->createCommand("
            SELECT id, name FROM chat_rooms WHERE invite_code = :code AND is_group = 1
        ", [':code' => $code])->queryOne();

        if (!$group) {
            throw new NotFoundHttpException('Código de convite inválido ou expirado.');
        }

        $groupId = (int)$group['id'];

        // 2. Verificar se o usuário já é membro do grupo
        $isMember = $db->createCommand("
            SELECT COUNT(*) FROM chat_room_members WHERE room_id = :gId AND user_id = :userId
        ", [':gId' => $groupId, ':userId' => $userId])->queryScalar();

        if (!$isMember) {
            $time = time();
            $username = Yii::$app->user->identity->username;

            // Inserir o membro no grupo
            $db->createCommand()->insert('chat_room_members', [
                'room_id' => $groupId,
                'user_id' => $userId,
                'joined_at' => $time
            ])->execute();

            // Mensagem de sistema notificando a adesão
            $db->createCommand()->insert('chat_messages', [
                'room_id' => $groupId,
                'sender_id' => $userId,
                'message' => "O usuário {$username} entrou no grupo via link de convite.",
                'is_system' => 1,
                'created_at' => $time
            ])->execute();

            Yii::$app->response->headers->set('HX-Trigger', 'chatRoomsUpdated');
        }

        // Redireciona a SPA principal para o chat index
        // No frontend, podemos carregar a chat_area correspondente
        return $this->redirect(['index', 'room_id' => $groupId]);
    }
}
