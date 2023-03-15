<?php

declare(strict_types=1);

namespace App\Controller\Admin;
use App\Entity\Client;
use App\Entity\InvitationEmail;
use App\Service\HelperService;
use App\Service\MailerService;
use App\Service\UserCheckerService;
use App\Entity\Story;
use App\Entity\User;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ClientController
{
    private User $user;
    private Client $client;
    private string $loggedUserId;
    private string $baseUri;
    public function __construct()
    {
        $this->user = new User();
        $this->client = new Client();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $portalClients = $this->client->fetchAllClients();
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/clients/list.phtml';
        die();
    }

    #[NoReturn] public function editForm(array $params = []): void
    {
        $id = $params['id'];
        $client = $this->client->find($id);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/clients/edit.phtml';
        die();
    }

    #[NoReturn] public function editSaveAction(array $params = []): void
    {
        $id = $params["id"];
        $data = [
            'first_name_1' => $_POST["firstName1"],
            'last_name_1' => $_POST["lastName1"],
            'email_1' => $_POST["email1"],
            'phone_1' => $_POST["phoneNumber1"],
            'first_name_2' => $_POST["firstName2"],
            'last_name_2' => $_POST["lastName2"],
            'email_2' => $_POST["email2"],
            'phone_2' => $_POST["phoneNumber2"],
            'address_1' => $_POST["address1"],
            'address_2' => $_POST["address2"],
            'city' => $_POST["city"],
            'state' => $_POST["state"],
            'zip' => $_POST["zipCode"],
            'home_type' => $_POST["homeType"],
            'notes' => $_POST["notes"],
            'realtor_id' => $_SESSION["user"]["realtor_id"]
        ];
        $finalData = [];
        foreach ($data as $key => $value)
        {
            $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->client->update($id, $finalData);
        header("Location: /admin/clients/list");
    }

    #[NoReturn] public function showAction(array $params = []): void
    {
        $id = $params["id"];
        $client = $this->client->find($id);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/clients/show.phtml';
        die();
    }

    #[NoReturn] public function deleteAction(array $params = []): void
    {
        $id = $params["id"];
        $this->client->delete($id);
        header("Location: /admin/clients/list");
        die();
    }
}