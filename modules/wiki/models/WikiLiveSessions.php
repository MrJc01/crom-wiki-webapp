<?php

namespace app\modules\wiki\models;

use yii\db\ActiveRecord;
use app\models\User;

/**
 * This is the model class for table "wiki_live_sessions".
 *
 * @property string $id
 * @property string $path
 * @property int $user_id
 * @property string $status
 * @property int $updated_at
 *
 * @property User $user
 */
class WikiLiveSessions extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wiki_live_sessions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'path', 'user_id', 'status', 'updated_at'], 'required'],
            [['user_id', 'updated_at'], 'integer'],
            [['id'], 'string', 'max' => 64],
            [['path'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 64],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID da Sessão',
            'path' => 'Caminho do Arquivo',
            'user_id' => 'Usuário',
            'status' => 'Status da Sessão (VIEWING/EDITING)',
            'updated_at' => 'Data de Atualização (Heartbeat)',
        ];
    }

    /**
     * Relacionamento lógico com o usuário cadastrado.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
