<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RegisterForm handles the user registration logic.
 */
class RegisterForm extends Model
{
    public string $username = '';
    public string $password = '';
    public string $password_confirm = '';
    public string $email = '';
    public string $whatsapp = '';
    public string $discord = '';
    public string $github = '';

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [['username', 'password', 'password_confirm', 'email'], 'required', 'message' => '{attribute} não pode ficar em branco.'],
            [['username', 'password', 'password_confirm', 'email', 'whatsapp', 'discord', 'github'], 'trim'],
            [['username', 'email', 'whatsapp', 'discord', 'github'], 'string', 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Este nome de usuário já está sendo utilizado.'],
            ['email', 'email', 'message' => 'Por favor, insira um e-mail válido.'],
            ['password', 'string', 'min' => 6, 'tooShort' => 'A senha deve conter no mínimo {min} caracteres.'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => 'As senhas digitadas não coincidem.'],
            ['username', 'validateIp'],
        ];
    }

    /**
     * Valida o limite de cadastros por IP (máximo 3)
     */
    public function validateIp(string $attribute, array|null $params): void
    {
        $ip = Yii::$app->request->userIP;
        if ($ip) {
            try {
                $count = (int)User::find()->where(['registration_ip' => $ip])->count();
                if ($count >= 3) {
                    $this->addError($attribute, 'Limite de cadastros excedido para este endereço IP. Se houver erro ou precisar de mais contas, entre em contato com mrj.crom@gmail.com.');
                }
            } catch (\Exception $e) {
                // Silencia caso a coluna não exista ou haja algum erro
            }
        }
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels(): array
    {
        return [
            'username' => 'Nome de Usuário',
            'password' => 'Senha de Acesso',
            'password_confirm' => 'Confirmar Senha',
            'email' => 'E-mail Principal',
            'whatsapp' => 'WhatsApp (Opcional)',
            'discord' => 'Discord (Opcional)',
            'github' => 'GitHub (Opcional)',
        ];
    }

    /**
     * Registers a new user.
     * @return bool whether the user is registered and logged in successfully
     */
    public function register(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->username = $this->username;
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $user->access_token = Yii::$app->security->generateRandomString(32);
            $user->email = $this->email;
            $user->whatsapp = $this->whatsapp ?: null;
            $user->discord = $this->discord ?: null;
            $user->github = $this->github ?: null;
            $user->registration_ip = Yii::$app->request->userIP;
            $user->is_membro = 1;
            $user->is_guardiao = 0;
            $user->is_pilar = 0;
            $user->is_forja = 0;
            $user->created_at = time();
            $user->updated_at = time();
            $user->status = 1;

            if ($user->save(false)) {
                $transaction->commit();
                // Login automático após cadastro
                return Yii::$app->user->login($user, 3600 * 24 * 30);
            }
            
            $transaction->rollBack();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('username', 'Ocorreu um erro no cadastro: ' . $e->getMessage() . '. Por favor, tente novamente ou entre em contato com mrj.crom@gmail.com.');
        }

        return false;
    }
}
