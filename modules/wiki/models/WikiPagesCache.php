<?php

namespace app\modules\wiki\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "wiki_pages_cache".
 *
 * @property string $path
 * @property string $sha
 * @property string $title
 * @property string $content
 * @property int $last_synced_at
 */
class WikiPagesCache extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wiki_pages_cache';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['path', 'sha', 'title', 'content', 'last_synced_at'], 'required'],
            [['content'], 'string'],
            [['last_synced_at', 'admin_id'], 'integer'],
            [['path', 'title', 'created_by'], 'string', 'max' => 255],
            [['sha'], 'string', 'max' => 64],
            [['path'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'path' => 'Caminho do Arquivo',
            'sha' => 'SHA Commit',
            'title' => 'Título da Página',
            'content' => 'Conteúdo Markdown',
            'last_synced_at' => 'Última Sincronização',
            'created_by' => 'Criador',
            'admin_id' => 'Administrador da Página',
        ];
    }

    /**
     * Relacionamento com o usuário administrador da página
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUser()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'admin_id']);
    }
}
