<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;

/**
 * Comando de console para gerenciar usuários e atribuições RBAC.
 */
class UserController extends Controller
{
    /**
     * Cria um novo usuário no banco de dados.
     * 
     * Exemplo: ./php yii user/create membro membro123
     * 
     * @param string $username Nome do usuário
     * @param string $password Senha em texto puro
     * @param int $is_guardiao Indica se o usuário é guardião (0 ou 1)
     * @param int $is_pilar Indica se o usuário é pilar (0 ou 1)
     * @param int $is_forja Indica se o usuário é forja (0 ou 1)
     * @return int Código de saída
     */
    public function actionCreate(string $username, string $password, int $is_guardiao = 0, int $is_pilar = 0, int $is_forja = 0): int
    {
        $existing = User::findOne(['username' => $username]);
        if ($existing) {
            $this->stdout("O usuário '{$username}' já existe.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $user = new User();
        $user->username = $username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->is_guardiao = $is_guardiao;
        $user->is_pilar = $is_pilar;
        $user->is_forja = $is_forja;
        $user->created_at = time();
        $user->updated_at = time();
        $user->status = 1; // Ativo

        if ($user->save(false)) {
            $this->stdout("Usuário '{$username}' criado com sucesso!\n");
            return ExitCode::OK;
        } else {
            $this->stdout("Falha ao criar o usuário '{$username}'.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    /**
     * Lista todos os usuários cadastrados no sistema.
     * 
     * Exemplo: ./php yii user/list
     * 
     * @return int Código de saída
     */
    public function actionList(): int
    {
        $users = User::find()->all();
        $this->stdout(sprintf("%-5s | %-20s | %-10s | %-10s | %-10s\n", "ID", "Username", "Guardião", "Pilar", "Forja"));
        $this->stdout(str_repeat("-", 65) . "\n");
        foreach ($users as $user) {
            $this->stdout(sprintf(
                "%-5d | %-20s | %-10s | %-10s | %-10s\n",
                $user->id,
                $user->username,
                $user->is_guardiao ? 'Sim' : 'Não',
                $user->is_pilar ? 'Sim' : 'Não',
                $user->is_forja ? 'Sim' : 'Não'
            ));
        }
        return ExitCode::OK;
    }

    /**
     * Atribui uma permissão RBAC a um usuário existente.
     * 
     * Exemplo: ./php yii user/assign membro access-terminal
     * 
     * @param string $username Nome do usuário
     * @param string $permissionName Nome da permissão ou regra RBAC
     * @return int Código de saída
     */
    public function actionAssign(string $username, string $permissionName): int
    {
        $user = User::findOne(['username' => $username]);
        if (!$user) {
            $this->stdout("Usuário '{$username}' não encontrado.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $db = Yii::$app->db;
        $permissionExists = $db->createCommand("SELECT COUNT(*) FROM auth_item WHERE name = :name", [':name' => $permissionName])->queryScalar();
        if (!$permissionExists) {
            $this->stdout("A permissão/regra '{$permissionName}' não existe em auth_item.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $assigned = $db->createCommand("SELECT COUNT(*) FROM auth_assignment WHERE item_name = :item AND user_id = :userId", [
            ':item' => $permissionName,
            ':userId' => (string)$user->id
        ])->queryScalar();

        if ($assigned) {
            $this->stdout("A permissão '{$permissionName}' já está atribuída ao usuário '{$username}'.\n");
            return ExitCode::OK;
        }

        $db->createCommand()->insert('auth_assignment', [
            'item_name' => $permissionName,
            'user_id' => (string)$user->id,
            'created_at' => time()
        ])->execute();

        $this->stdout("Permissão '{$permissionName}' atribuída ao usuário '{$username}' com sucesso!\n");
        return ExitCode::OK;
    }
}
