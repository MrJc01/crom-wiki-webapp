<?php

namespace app\modules\json_store\models;

use yii\db\ActiveRecord;

/**
 * Model para a tabela "json_store_tokens".
 *
 * @property int $id
 * @property int $endpoint_id
 * @property string $token_hash
 * @property string $label
 * @property int $created_at
 */
class JsonStoreToken extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'json_store_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['endpoint_id', 'token_hash'], 'required'],
            [['endpoint_id', 'created_at'], 'integer'],
            [['token_hash'], 'string', 'max' => 64],
            [['label'], 'string', 'max' => 100],
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
