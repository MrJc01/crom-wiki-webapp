<?php

namespace app\modules\wiki\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\User;

/**
 * This is the model class for table "wiki_github_auth".
 *
 * @property int $user_id
 * @property int $gh_user_id
 * @property string $gh_username
 * @property string $access_token
 * @property string $refresh_token
 * @property int $expires_at
 *
 * @property User $user
 */
class WikiGithubAuth extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wiki_github_auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'gh_user_id', 'gh_username', 'access_token', 'refresh_token', 'expires_at'], 'required'],
            [['user_id', 'gh_user_id', 'expires_at'], 'integer'],
            [['access_token', 'refresh_token'], 'string'],
            [['gh_username'], 'string', 'max' => 255],
            [['gh_user_id'], 'unique'],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Usuário Local',
            'gh_user_id' => 'ID do Usuário GitHub',
            'gh_username' => 'Username GitHub',
            'access_token' => 'Token de Acesso (Criptografado)',
            'refresh_token' => 'Refresh Token (Criptografado)',
            'expires_at' => 'Data de Expiração',
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

    /**
     * Descriptografa e retorna o Access Token.
     *
     * @param string $secretKey
     * @return string|bool
     */
    public function getDecryptedAccessToken($secretKey)
    {
        return Yii::$app->security->decryptByPassword($this->access_token, $secretKey);
    }

    /**
     * Descriptografa e retorna o Refresh Token.
     *
     * @param string $secretKey
     * @return string|bool
     */
    public function getDecryptedRefreshToken($secretKey)
    {
        return Yii::$app->security->decryptByPassword($this->refresh_token, $secretKey);
    }

    /**
     * Criptografa e define o Access Token.
     *
     * @param string $plainToken
     * @param string $secretKey
     */
    public function setEncryptedAccessToken($plainToken, $secretKey)
    {
        $this->access_token = Yii::$app->security->encryptByPassword($plainToken, $secretKey);
    }

    /**
     * Criptografa e define o Refresh Token.
     *
     * @param string $plainToken
     * @param string $secretKey
     */
    public function setEncryptedRefreshToken($plainToken, $secretKey)
    {
        $this->refresh_token = Yii::$app->security->encryptByPassword($plainToken, $secretKey);
    }
}
