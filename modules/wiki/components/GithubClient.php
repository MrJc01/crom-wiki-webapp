<?php

namespace app\modules\wiki\components;

use Yii;
use yii\base\Component;

/**
 * Cliente auxiliar para integração com a API do GitHub.
 */
class GithubClient extends Component
{
    private const API_BASE_URL = 'https://api.github.com';
    private const OAUTH_BASE_URL = 'https://github.com/login/oauth';

    /**
     * Realiza a troca do authorization code pelo access token e refresh token.
     * 
     * @param string $code
     * @return array|bool
     */
    public static function exchangeCodeForToken(string $code)
    {
        $clientId = getenv('GITHUB_CLIENT_ID');
        $clientSecret = getenv('GITHUB_CLIENT_SECRET');
        $redirectUri = getenv('GITHUB_REDIRECT_URI');

        if (empty($clientId) || empty($clientSecret)) {
            Yii::error('GITHUB_CLIENT_ID ou GITHUB_CLIENT_SECRET não configurados no ambiente.', __METHOD__);
            return false;
        }

        $url = self::OAUTH_BASE_URL . '/access_token';
        $payload = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ];

        $response = self::sendRequest($url, 'POST', $payload, [], true);
        if ($response && isset($response['access_token'])) {
            return $response;
        }

        Yii::error('Falha ao trocar código pelo token: ' . json_encode($response), __METHOD__);
        return false;
    }

    /**
     * Busca dados do usuário autenticado no GitHub.
     * 
     * @param string $token
     * @return array|bool
     */
    public static function getUserInfo(string $token)
    {
        $url = self::API_BASE_URL . '/user';
        $headers = [
            'Authorization: Bearer ' . $token,
        ];

        return self::sendRequest($url, 'GET', null, $headers);
    }

    /**
     * Busca o SHA atual de um arquivo no repositório do GitHub (retorna null se não existir).
     * 
     * @param string $token
     * @param string $owner
     * @param string $repo
     * @param string $path
     * @param string $branch
     * @return string|null
     */
    public static function getFileSha(string $token, string $owner, string $repo, string $path, string $branch = 'main'): ?string
    {
        $url = self::API_BASE_URL . "/repos/{$owner}/{$repo}/contents/{$path}?ref=" . urlencode($branch);
        $headers = [
            'Authorization: Bearer ' . $token,
        ];

        $response = self::sendRequest($url, 'GET', null, $headers);
        if ($response && isset($response['sha'])) {
            return $response['sha'];
        }

        return null;
    }

    /**
     * Cria ou atualiza um arquivo no repositório do GitHub.
     * 
     * @param string $token Token de acesso pessoal do usuário
     * @param string $owner Proprietário do repositório
     * @param string $repo Nome do repositório
     * @param string $path Caminho relativo do arquivo (ex: docs/guia.md)
     * @param string $content Conteúdo textual em Markdown
     * @param string $message Mensagem do commit
     * @param string $branch Branch de destino
     * @return array|bool
     */
    public static function createOrUpdateFile(
        string $token, 
        string $owner, 
        string $repo, 
        string $path, 
        string $content, 
        string $message, 
        string $branch = 'main'
    ) {
        // 1. Tenta obter o SHA atual do arquivo caso ele já exista
        $sha = self::getFileSha($token, $owner, $repo, $path, $branch);

        // 2. Prepara o payload para atualizar o arquivo via API
        $url = self::API_BASE_URL . "/repos/{$owner}/{$repo}/contents/{$path}";
        $payload = [
            'message' => $message,
            'content' => base64_encode($content),
            'branch' => $branch,
        ];

        if ($sha !== null) {
            $payload['sha'] = $sha;
        }

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];

        $response = self::sendRequest($url, 'PUT', $payload, $headers);
        if ($response && (isset($response['content']) || isset($response['commit']))) {
            return $response;
        }

        Yii::error('Erro ao salvar arquivo no GitHub: ' . json_encode($response), __METHOD__);
        return false;
    }

    /**
     * Helper genérico para realizar requisições curl de baixo nível.
     * 
     * @param string $url
     * @param string $method
     * @param mixed $payload
     * @param array $headers
     * @param bool $isAuthRequest
     * @return array|bool
     */
    private static function sendRequest(
        string $url, 
        string $method = 'GET', 
        $payload = null, 
        array $headers = [], 
        bool $isAuthRequest = false
    ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        // O GitHub exige o cabeçalho User-Agent em todas as requisições à API
        $defaultHeaders = [
            'User-Agent: CROM-Wiki-WebApp/1.0',
        ];

        if ($isAuthRequest) {
            // Requisições para o fluxo de OAuth preferem JSON de volta
            $defaultHeaders[] = 'Accept: application/json';
        } else {
            $defaultHeaders[] = 'Accept: application/vnd.github+json';
        }

        $mergedHeaders = array_merge($defaultHeaders, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $mergedHeaders);

        if ($payload !== null) {
            if ($isAuthRequest) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            }
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            return false;
        }

        $data = json_decode($response, true);
        if ($httpCode >= 200 && $httpCode < 300) {
            return $data;
        }

        return $data ?: ['error' => 'HTTP code ' . $httpCode];
    }
}
