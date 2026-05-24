<?php

namespace app\modules\page_crud\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\models\User;

/**
 * This is the model class for table "page_crud_pages".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property string $created_by
 * @property int|null $admin_id
 * @property string $category
 * @property int $created_at
 * @property int $updated_at
 */
class PageDocumented extends ActiveRecord
{
    /**
     * Propriedade virtual para múltiplos administradores
     * @var array
     */
    public $adminIds = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_crud_pages';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'content', 'created_by'], 'required'],
            [['content'], 'string'],
            [['admin_id', 'is_public', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'title', 'created_by'], 'string', 'max' => 255],
            [['category'], 'string', 'max' => 100],
            [['slug'], 'unique'],
            [['adminIds'], 'safe'],
            // Sanitização de slugs
            ['slug', 'filter', 'filter' => function ($value) {
                $val = strtolower($value);
                $val = str_replace(['..', '\\', ' '], ['', '/', '-'], $val);
                return ltrim($val, '/');
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug da Página',
            'title' => 'Título',
            'content' => 'Conteúdo Markdown',
            'created_by' => 'Criador',
            'admin_id' => 'Administrador Legado',
            'category' => 'Categoria',
            'is_public' => 'Acesso Público (Sem Login)',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * Relacionamento com múltiplos administradores via tabela associativa N:N
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('page_crud_page_admins', ['page_id' => 'id']);
    }

    /**
     * Sincroniza administradores na tabela associativa após gravação bem-sucedida
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Limpa as associações anteriores
        Yii::$app->db->createCommand()
            ->delete('page_crud_page_admins', ['page_id' => $this->id])
            ->execute();

        // Insere as novas chaves N:N de administradores se houver
        if (is_array($this->adminIds)) {
            foreach ($this->adminIds as $userId) {
                if (!empty($userId)) {
                    Yii::$app->db->createCommand()
                        ->insert('page_crud_page_admins', [
                            'page_id' => $this->id,
                            'user_id' => (int)$userId
                        ])->execute();
                }
            }
        }
    }

    /**
     * Coleta os IDs dos administradores da relação para popular a propriedade virtual
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->adminIds = [];
        foreach ($this->adminUsers as $user) {
            $this->adminIds[] = $user->id;
        }
    }
}
