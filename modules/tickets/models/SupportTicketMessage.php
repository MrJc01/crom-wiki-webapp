<?php

namespace app\modules\tickets\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\User;

/**
 * Class SupportTicketMessage
 * @package app\modules\tickets\models
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $message
 * @property int $created_at
 *
 * @property SupportTicket $ticket
 * @property User $user
 */
class SupportTicketMessage extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'support_ticket_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'user_id', 'message', 'created_at'], 'required'],
            [['ticket_id', 'user_id', 'created_at'], 'integer'],
            [['message'], 'string'],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupportTicket::class, 'targetAttribute' => ['ticket_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(SupportTicket::class, ['id' => 'ticket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
