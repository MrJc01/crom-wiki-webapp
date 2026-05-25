<?php

declare(strict_types=1);

namespace app\modules\tickets\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\modules\tickets\models\SupportTicket;
use app\modules\tickets\models\SupportTicketMessage;
use app\models\User;

class DefaultController extends Controller
{
    // Desabilitar CSRF para requisições rápidas de chat via POST se necessário, mas mantemos ativado
    public $enableCsrfValidation = false;

    /**
     * Interceptador de layout inteligente para HTMX
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->user->isGuest) {
                return $this->redirect(['/site/login'])->send();
            }

            if (Yii::$app->request->headers->has('HX-Request')) {
                $this->layout = false;
            } else {
                $this->layout = '@app/views/layouts/main';
            }
            return true;
        }
        return false;
    }

    /**
     * Listagem dos tickets (SPA Dashboard)
     */
    public function actionIndex(): string
    {
        $userId = (int)Yii::$app->user->id;
        $user = Yii::$app->user->identity;

        // 1. Tickets Disponíveis para pegar:
        // Status 'open', criados por outros membros
        // Onde o usuário atual atende aos requisitos de tags.
        $allOpenTickets = SupportTicket::find()
            ->where(['status' => SupportTicket::STATUS_OPEN])
            ->andWhere(['!=', 'created_by', $userId])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $ticketsAvailable = [];
        foreach ($allOpenTickets as $ticket) {
            if ($ticket->canUserTake($user)) {
                $ticketsAvailable[] = $ticket;
            }
        }

        // 2. Meus Tickets Criados
        $myCreatedTickets = SupportTicket::find()
            ->where(['created_by' => $userId])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        // 3. Tickets Assumidos por Mim
        $myAssignedTickets = SupportTicket::find()
            ->where(['assigned_to' => $userId])
            ->orderBy(['updated_at' => SORT_DESC])
            ->all();

        // 4. Todos os Tickets (Geral)
        $allTickets = SupportTicket::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'ticketsAvailable' => $ticketsAvailable,
            'myCreatedTickets' => $myCreatedTickets,
            'myAssignedTickets' => $myAssignedTickets,
            'allTickets' => $allTickets,
        ]);
    }

    /**
     * Ação de Criar Ticket
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $userId = (int)Yii::$app->user->id;

            $ticket = new SupportTicket();
            $ticket->title = trim((string)$request->post('title'));
            $ticket->description = trim((string)$request->post('description'));
            $ticket->type = $request->post('type', SupportTicket::TYPE_OTHER);
            $ticket->status = SupportTicket::STATUS_OPEN;
            $ticket->created_by = $userId;
            
            $ticket->req_guardiao = $request->post('req_guardiao') ? 1 : 0;
            $ticket->req_pilar = $request->post('req_pilar') ? 1 : 0;
            $ticket->req_forja = $request->post('req_forja') ? 1 : 0;
            
            $ticket->created_at = time();
            $ticket->updated_at = time();

            if ($ticket->save()) {
                // Disparar Notificações de Sistema privadas para usuários elegíveis
                try {
                    $usersToNotify = [];
                    if ($ticket->req_guardiao) {
                        $usersToNotify = array_merge($usersToNotify, User::find()->where(['is_guardiao' => 1])->all());
                    }
                    if ($ticket->req_pilar) {
                        $usersToNotify = array_merge($usersToNotify, User::find()->where(['is_pilar' => 1])->all());
                    }
                    if ($ticket->req_forja) {
                        $usersToNotify = array_merge($usersToNotify, User::find()->where(['is_forja' => 1])->all());
                    }
                    
                    // Se o ticket for livre (acesso a todos os membros)
                    if (!$ticket->req_guardiao && !$ticket->req_pilar && !$ticket->req_forja) {
                        $usersToNotify = User::find()->all();
                    }

                    $notifiedIds = [];
                    foreach ($usersToNotify as $u) {
                        if ($u->id !== $userId && !in_array($u->id, $notifiedIds)) {
                            $notifiedIds[] = $u->id;
                            
                            $reqTagsStr = [];
                            if ($ticket->req_guardiao) $reqTagsStr[] = 'Guardião';
                            if ($ticket->req_pilar) $reqTagsStr[] = 'Pilar';
                            if ($ticket->req_forja) $reqTagsStr[] = 'Forja';
                            $tagsLabel = !empty($reqTagsStr) ? implode(' + ', $reqTagsStr) : 'Livre Acesso';

                            $msgText = "🎟️ **Novo Ticket Disponível:** O ticket **'{$ticket->title}'** (" . $ticket->getTypeLabel() . ") exige competência de [{$tagsLabel}] e está aguardando por um responsável.";
                            \app\modules\chat\Module::sendSystemNotification((int)$u->id, $msgText);
                        }
                    }
                } catch (\Exception $e) {
                    Yii::error("Erro ao enviar notificações de sistema do ticket: " . $e->getMessage(), 'tickets');
                }

                Yii::$app->response->headers->set('HX-Trigger', 'ticketCreated');
                return $this->actionIndex();
            }
        }
        return $this->actionIndex();
    }

    /**
     * Ação de Assumir um Ticket
     */
    public function actionTake($id)
    {
        $ticket = SupportTicket::findOne($id);
        if (!$ticket) {
            throw new NotFoundHttpException('Ticket não encontrado.');
        }

        $userId = (int)Yii::$app->user->id;
        $user = Yii::$app->user->identity;

        if ($ticket->created_by === $userId) {
            Yii::$app->session->setFlash('error', 'Você não pode assumir seu próprio ticket.');
            return $this->actionIndex();
        }

        if ($ticket->status !== SupportTicket::STATUS_OPEN) {
            Yii::$app->session->setFlash('error', 'Este ticket já foi assumido por outro membro.');
            return $this->actionIndex();
        }

        if (!$ticket->canUserTake($user)) {
            throw new ForbiddenHttpException('Você não atende aos requisitos de tags exigidas por este ticket.');
        }

        $ticket->assigned_to = $userId;
        $ticket->status = SupportTicket::STATUS_ASSIGNED;
        $ticket->updated_at = time();
        
        if ($ticket->save(false)) {
            // Cria uma mensagem automática do sistema no chat do ticket
            $msg = new SupportTicketMessage();
            $msg->ticket_id = $ticket->id;
            $msg->user_id = $userId; // O responsável
            $msg->message = "📢 *Mensagem do Sistema:* O ticket foi assumido por @" . $user->username . ". O chat foi aberto para alinhamento.";
            $msg->created_at = time();
            $msg->save();

            // Notificar o criador do ticket de forma privada no chat do sistema dele
            $msgText = "🤝 **Ticket Assumido:** O seu ticket **'{$ticket->title}'** foi assumido por **@{$user->username}**. O chat interno do ticket foi aberto para alinhamento.";
            \app\modules\chat\Module::sendSystemNotification((int)$ticket->created_by, $msgText);

            Yii::$app->response->headers->set('HX-Trigger', 'ticketTaken');
        }

        return $this->actionIndex();
    }

    /**
     * Exibe a tela interna do Ticket (Chat)
     */
    public function actionView($id): string
    {
        $ticket = SupportTicket::findOne($id);
        if (!$ticket) {
            throw new NotFoundHttpException('Ticket não encontrado.');
        }

        $userId = (int)Yii::$app->user->id;
        $user = Yii::$app->user->identity;

        // Validar permissão de visualização: apenas criador, responsável ou qualquer membro apto
        if ($ticket->created_by !== $userId && $ticket->assigned_to !== $userId && !$ticket->canUserTake($user)) {
            throw new ForbiddenHttpException('Você não tem permissão para visualizar este ticket.');
        }

        return $this->render('view', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Envia mensagem no chat do ticket
     */
    public function actionSendMessage($id)
    {
        $ticket = SupportTicket::findOne($id);
        if (!$ticket) {
            throw new NotFoundHttpException('Ticket não encontrado.');
        }

        $request = Yii::$app->request;
        $userId = (int)Yii::$app->user->id;
        $user = Yii::$app->user->identity;

        // Permissão para enviar mensagem: apenas se estiver envolvido
        if ($ticket->created_by !== $userId && $ticket->assigned_to !== $userId && !$ticket->canUserTake($user)) {
            throw new ForbiddenHttpException('Acesso negado ao chat.');
        }

        if ($ticket->status === SupportTicket::STATUS_CLOSED) {
            throw new ForbiddenHttpException('Este ticket está resolvido/fechado. O chat foi encerrado.');
        }

        $messageContent = trim((string)$request->post('message'));
        if (!empty($messageContent)) {
            $msg = new SupportTicketMessage();
            $msg->ticket_id = $ticket->id;
            $msg->user_id = $userId;
            $msg->message = $messageContent;
            $msg->created_at = time();
            if ($msg->save()) {
                // Atualiza o timestamp de atualização do ticket
                $ticket->updated_at = time();
                $ticket->save(false);
            }
        }

        // Se for requisição HTMX, renderiza apenas o fragmento do chat de forma ultra limpa
        if ($request->headers->has('HX-Request')) {
            return $this->renderPartial('_chat', ['ticket' => $ticket]);
        }

        return $this->redirect(['view', 'id' => $ticket->id]);
    }

    /**
     * Ação de Resolver/Fechar Ticket
     */
    public function actionClose($id)
    {
        $ticket = SupportTicket::findOne($id);
        if (!$ticket) {
            throw new NotFoundHttpException('Ticket não encontrado.');
        }

        $userId = (int)Yii::$app->user->id;

        // Apenas o criador ou o responsável podem fechar
        if ($ticket->created_by !== $userId && $ticket->assigned_to !== $userId) {
            throw new ForbiddenHttpException('Apenas os envolvidos no ticket podem fechá-lo.');
        }

        $ticket->status = SupportTicket::STATUS_CLOSED;
        $ticket->updated_at = time();
        if ($ticket->save(false)) {
            // Mensagem automática do sistema no chat
            $user = Yii::$app->user->identity;
            $msg = new SupportTicketMessage();
            $msg->ticket_id = $ticket->id;
            $msg->user_id = $userId;
            $msg->message = "🔒 *Mensagem do Sistema:* O ticket foi encerrado e marcado como resolvido por @" . $user->username . ".";
            $msg->created_at = time();
            $msg->save();

            Yii::$app->response->headers->set('HX-Trigger', 'ticketClosed');
        }

        return $this->actionIndex();
    }
}
