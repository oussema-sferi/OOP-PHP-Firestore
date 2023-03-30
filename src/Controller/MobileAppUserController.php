<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PortalClient;
use JetBrains\PhpStorm\NoReturn;

class MobileAppUserController
{
    private string $loggedUserId;
    private string $baseUri;
    private PortalClient $client;
    public function __construct()
    {
        if(isset($_SESSION["user"]["realtor_id"])) $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $this->client = new PortalClient();
    }

    #[NoReturn] public function emailsUnsubscriptionAction(array $params = []): void
    {
        $clientId = $params["id"];
        $data = [
            ['path' => 'is_subscribed', 'value' => false]
        ];
        $this->client->update($clientId, $data);
        header("Location: /mobile-app/emails-unsubscription-confirmation");
        die();
    }

    #[NoReturn] public function emailsUnsubscriptionConfirmationAction(array $params = []): void
    {
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/mobile-app-user/email-unsubscription/confirmation.phtml';
        die();
    }
}