<?php

declare(strict_types=1);

namespace App\Controller\Admin;
use App\Entity\MobileAppClient;
use App\Service\AuthCheckerService;
use App\Entity\User;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;

class MobileAppClientController
{
    private string $loggedUserId;
    private string $baseUri;
    private User $user;
    private MobileAppClient $mobileAppClient;
    public function __construct()
    {
        AuthCheckerService::checkIfAdmin();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $this->user = new User();
        $this->mobileAppClient = new MobileAppClient();
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $mobileAppClients = $this->mobileAppClient->fetchAllMobileAppClients();
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/mobile-app-clients/list.phtml';
        die();
    }

    #[NoReturn] public function editForm(array $params = []): void
    {
        $id = $params['id'];
        $client = $this->mobileAppClient->find($id);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/mobile-app-clients/edit.phtml';
        die();
    }

    #[NoReturn] public function editSaveAction(array $params = []): void
    {
        $id = $params["id"];
        $data = [
            'full_name' => $params["fullName"],
            'email' => $params["email"],
            'phone' => $params["phoneNumber"]
        ];
        $finalData = [];
        foreach ($data as $key => $value)
        {
            $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->mobileAppClient->update($id, $finalData);
        header("Location: /admin/mobile-app-clients/list");
    }

    #[NoReturn] public function showAction(array $params = []): void
    {
        $id = $params["id"];
        $client = $this->mobileAppClient->find($id);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/mobile-app-clients/show.phtml';
        die();
    }

    #[NoReturn] public function deleteAction(array $params = []): void
    {
        $id = $params["id"];
        $this->mobileAppClient->markAsDeleted($id);
        header("Location: /admin/mobile-app-clients/list");
        die();
    }
}