<?php

namespace app\modules\chat;

/**
 * Módulo de Chat Premium (Estilo WhatsApp) para o Portal CROM.
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\chat\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

    /**
     * Envia uma notificação do sistema privada para um usuário específico.
     * Se o canal privado de notificações não existir, ele é criado automaticamente.
     *
     * @param int $userId ID do usuário destinatário
     * @param string $messageText Texto da notificação
     * @return bool
     */
    public static function sendSystemNotification(int $userId, string $messageText): bool
    {
        try {
            $db = \Yii::$app->db;
            
            // 1. Verificar se o canal privado de notificações do usuário já existe
            $roomId = $db->createCommand("
                SELECT r.id 
                FROM chat_rooms r
                JOIN chat_room_members rm ON r.id = rm.room_id AND rm.user_id = :userId
                WHERE r.is_system = 1
                LIMIT 1
            ", [':userId' => $userId])->queryScalar();

            $time = time();

            // 2. Se não existir, criar a sala privada de sistema e associar o usuário
            if (!$roomId) {
                $db->createCommand()->insert('chat_rooms', [
                    'name' => 'Notificações do Sistema',
                    'is_group' => 0,
                    'is_system' => 1,
                    'created_by' => 1, // Sistema (Admin)
                    'created_at' => $time,
                    'invite_code' => null
                ])->execute();

                $roomId = $db->getLastInsertID();

                $db->createCommand()->insert('chat_room_members', [
                    'room_id' => $roomId,
                    'user_id' => $userId,
                    'joined_at' => $time
                ])->execute();

                // Mensagem de boas-vindas inicial do sistema
                $db->createCommand()->insert('chat_messages', [
                    'room_id' => $roomId,
                    'sender_id' => 1, // Sistema
                    'message' => '📢 Canal de Notificações do Sistema ativado. Você receberá alertas importantes sobre suas tarefas, governança e status de tickets aqui.',
                    'is_system' => 1,
                    'created_at' => $time
                ])->execute();
            }

            // 3. Inserir a mensagem de notificação atual
            $db->createCommand()->insert('chat_messages', [
                'room_id' => $roomId,
                'sender_id' => 1, // Sistema
                'message' => $messageText,
                'is_system' => 1,
                'created_at' => $time
            ])->execute();

            return true;
        } catch (\Exception $e) {
            \Yii::error("Falha ao enviar notificação de sistema para o usuário ID {$userId}: " . $e->getMessage(), 'chat');
            return false;
        }
    }
}
