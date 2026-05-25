<?php

namespace app\modules\tickets\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\User;

/**
 * Class SupportTicket
 * @package app\modules\tickets\models
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $type
 * @property string $status
 * @property int $created_by
 * @property int|null $assigned_to
 * @property int $req_guardiao
 * @property int $req_pilar
 * @property int $req_forja
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $creator
 * @property User|null $assignee
 * @property SupportTicketMessage[] $messages
 */
class SupportTicket extends ActiveRecord
{
    const STATUS_OPEN = 'open';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_CLOSED = 'closed';

    const TYPE_IDEA = 'idea';
    const TYPE_BUG_FIX = 'bug_fix';
    const TYPE_PROJECT = 'project';
    const TYPE_OTHER = 'other';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'support_tickets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'type', 'created_by', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
            [['created_by', 'assigned_to', 'req_guardiao', 'req_pilar', 'req_forja', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['type', 'status'], 'string', 'max' => 32],
            [['status'], 'default', 'value' => self::STATUS_OPEN],
            [['req_guardiao', 'req_pilar', 'req_forja'], 'default', 'value' => 0],
            [['assigned_to'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['assigned_to' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignee()
    {
        return $this->hasOne(User::class, ['id' => 'assigned_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(SupportTicketMessage::class, ['ticket_id' => 'id'])->orderBy(['created_at' => SORT_ASC]);
    }

    /**
     * Verifica se o usuário tem as tags exigidas para assumir o ticket
     * @param User $user
     * @return bool
     */
    public function canUserTake($user)
    {
        if (!$user) {
            return false;
        }

        // Se o ticket for de acesso livre
        if (!$this->req_guardiao && !$this->req_pilar && !$this->req_forja) {
            return true;
        }

        // Se exigir Guardião e o usuário NÃO for Guardião
        if ($this->req_guardiao && !(bool)$user->is_guardiao) {
            return false;
        }

        // Se exigir Pilar e o usuário NÃO for Pilar
        if ($this->req_pilar && !(bool)$user->is_pilar) {
            return false;
        }

        // Se exigir Forja e o usuário NÃO for Forja
        if ($this->req_forja && !(bool)$user->is_forja) {
            return false;
        }

        return true;
    }

    /**
     * Retorna a etiqueta legível para o tipo de ticket
     * @return string
     */
    public function getTypeLabel()
    {
        return match ($this->type) {
            self::TYPE_IDEA => 'Ideia',
            self::TYPE_BUG_FIX => 'Correção',
            self::TYPE_PROJECT => 'Projeto',
            default => 'Outro',
        };
    }

    /**
     * Retorna a etiqueta de status formatada em HTML com cores premium
     * @return string
     */
    public function getStatusBadge()
    {
        return match ($this->status) {
            self::STATUS_OPEN => '<span class="px-2 py-0.5 bg-sky-500/10 border border-sky-500/20 text-sky-400 text-[10px] font-bold rounded-full uppercase tracking-wider">Aberto</span>',
            self::STATUS_ASSIGNED => '<span class="px-2 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[10px] font-bold rounded-full uppercase tracking-wider animate-pulse">Em Progresso</span>',
            self::STATUS_CLOSED => '<span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold rounded-full uppercase tracking-wider">Resolvido</span>',
            default => '<span class="px-2 py-0.5 bg-slate-500/10 border border-slate-500/20 text-slate-400 text-[10px] font-bold rounded-full uppercase tracking-wider">Desconhecido</span>',
        };
    }
}
