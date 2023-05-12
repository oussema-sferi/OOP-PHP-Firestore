<?php

declare(strict_types=1);

namespace App\Controller\Admin;
use App\Service\AuthCheckerService;
use App\Entity\User;
use App\Entity\PortalClient;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;

class TrashController
{
    private string $loggedUserId;
    private string $baseUri;
    private readonly User $user;
    private readonly PortalClient $client;
    public function __construct()
    {
        AuthCheckerService::checkIfAdmin();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $this->user = new User();
        $this->client = new PortalClient();
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $realtors = $this->user->fetchDeletedRealtors();
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/trash/realtors/list.phtml';
        die();
    }

    #[NoReturn] public function restoreAction(array $params = []): void
    {
        $realtorId = $params["id"];
        // set Realtor as deleted
        $this->user->restoreRealtor($realtorId);
        // get Realtor added clients
        $realtorClients = $this->user->fetchUserAddedClients($realtorId);
        // delete Realtor associated clients
        foreach ($realtorClients as $client)
        {
            $this->client->restoreClient($client->doc_id);
        }
        header("Location: /admin/trash/realtors/list");
        die();
    }
}