<?php

namespace app\modules\json_store\models;

use yii\db\ActiveRecord;

/**
 * Model para a tabela "json_store_access_logs".
 *
 * @property int $id
 * @property int $endpoint_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property int $accessed_at
 */
class JsonAccessLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'json_store_access_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['endpoint_id', 'accessed_at'], 'required'],
            [['endpoint_id', 'accessed_at'], 'integer'],
            [['ip_address'], 'string', 'max' => 45],
            [['user_agent'], 'string', 'max' => 500],
        ];
    }

    /**
     * Relacionamento com o endpoint
     * @return \yii\db\ActiveQuery
     */
    public function getEndpoint()
    {
        return $this->hasOne(JsonEndpoint::class, ['id' => 'endpoint_id']);
    }
}
