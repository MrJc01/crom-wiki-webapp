<?php

namespace app\modules\json_store\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\models\User;

/**
 * Model para a tabela "json_store_endpoints".
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $json_content
 * @property int $is_public
 * @property string $created_by
 * @property string $category
 * @property int $created_at
 * @property int $updated_at
 */
class JsonEndpoint extends ActiveRecord
{
    /**
     * Propriedade virtual para múltiplos administradores (N:N)
     * @var array
     */
    public $adminIds = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'json_store_endpoints';
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
            [['slug', 'name', 'created_by'], 'required'],
            [['json_content'], 'string'],
            [['is_public', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'name', 'created_by'], 'string', 'max' => 255],
            [['category'], 'string', 'max' => 100],
            [['slug'], 'unique'],
            [['adminIds'], 'safe'],
            // Sanitização de slugs
            ['slug', 'filter', 'filter' => function ($value) {
                $val = strtolower($value);
                $val = preg_replace('/[^a-z0-9\-_]/', '-', $val);
                $val = preg_replace('/-+/', '-', $val);
                return trim($val, '-');
            }],
            // Validação de JSON
            ['json_content', 'validateJson'],
        ];
    }

    /**
     * Validador customizado para garantir que o conteúdo seja JSON válido
     */
    public function validateJson($attribute, $params)
    {
        if (!empty($this->$attribute)) {
            json_decode($this->$attribute);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError($attribute, 'O conteúdo deve ser um JSON válido. Erro: ' . json_last_error_msg());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug (URL)',
            'name' => 'Nome do Endpoint',
            'json_content' => 'Conteúdo JSON',
            'is_public' => 'Acesso Público',
            'created_by' => 'Criador',
            'category' => 'Categoria',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * Relacionamento N:N com administradores via tabela associativa
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('json_store_admins', ['endpoint_id' => 'id']);
    }

    /**
     * Relacionamento 1:N com tokens de acesso
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(JsonStoreToken::class, ['endpoint_id' => 'id']);
    }

    /**
     * Relacionamento 1:N com logs de acesso
     * @return \yii\db\ActiveQuery
     */
    public function getAccessLogs()
    {
        return $this->hasMany(JsonAccessLog::class, ['endpoint_id' => 'id']);
    }

    /**
     * Conta o total de requisições para este endpoint
     * @return int
     */
    public function getTotalRequests()
    {
        return (int) JsonAccessLog::find()
            ->where(['endpoint_id' => $this->id])
            ->count();
    }

    /**
     * Conta as requisições nas últimas 24 horas
     * @return int
     */
    public function getRequests24h()
    {
        $threshold = time() - 86400;
        return (int) JsonAccessLog::find()
            ->where(['endpoint_id' => $this->id])
            ->andWhere(['>=', 'accessed_at', $threshold])
            ->count();
    }

    /**
     * Sincroniza administradores na tabela associativa após gravação
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Limpa as associações anteriores
        Yii::$app->db->createCommand()
            ->delete('json_store_admins', ['endpoint_id' => $this->id])
            ->execute();

        // Insere as novas chaves N:N
        if (is_array($this->adminIds)) {
            foreach ($this->adminIds as $userId) {
                if (!empty($userId)) {
                    Yii::$app->db->createCommand()
                        ->insert('json_store_admins', [
                            'endpoint_id' => $this->id,
                            'user_id' => (int)$userId
                        ])->execute();
                }
            }
        }
    }

    /**
     * Popula a propriedade virtual adminIds após busca
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
