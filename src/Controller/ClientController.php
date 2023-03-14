<?php

declare(strict_types=1);

namespace App\Controller;
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

class ClientController
{
    private User $user;
    private Client $client;
    private InvitationEmail $invitationEmail;
    private string $loggedUserId;
    private string $baseUri;
    public function __construct()
    {
        UserCheckerService::checkUser();
        $this->user = new User();
        $this->client = new Client();
        $this->invitationEmail = new InvitationEmail();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $realtorClients = $this->client->fetchPortalClients($this->loggedUserId);
        $allMobileAppClients = $this->client->fetchMobileAppClients();
        $helper = new HelperService();
        $helper->clientCheckAndSaveSignUpDate($this->client, $this->loggedUserId, [], "", false);
        $clients = $this->client->fetchPortalClients($this->loggedUserId);
        $realtor = $this->user->fetchUserById($this->loggedUserId);
        require_once __DIR__ . '/../../templates/clients/list.phtml';
        die();
    }

    #[NoReturn] public function addForm(array $params = []): void
    {
        require_once __DIR__ . '/../../templates/clients/new.phtml';
        die();
    }

    #[NoReturn] public function createAction(array $params = []): void
    {
        $data = [
            'first_name_1' => $params["firstName1"],
            'last_name_1' => $params["lastName1"],
            'email_1' => $params["email1"],
            'phone_1' => $params["phoneNumber1"],
            'first_name_2' => $params["firstName2"],
            'last_name_2' => $params["lastName2"],
            'email_2' => $params["email2"],
            'phone_2' => $params["phoneNumber2"],
            'address_1' => $params["address1"],
            'address_2' => $params["address2"],
            'city' => $params["city"],
            'state' => $params["state"],
            'zip' => $params["zipCode"],
            'home_type' => $params["homeType"],
            'notes' => $params["notes"],
            'is_subscribed' => true,
            'is_deleted' => false,
            'realtor_id' => $this->loggedUserId,
            'created_at' => new Timestamp(new DateTime()),
        ];
        // Create and save new client in DB
        $this->client->create($data);
        header("Location: /clients/list");
    }

    #[NoReturn] public function editForm(array $params = []): void
    {
        $id = $params['id'];
        $client = $this->client->find($id);
        require_once __DIR__ . '/../../templates/clients/edit.phtml';
        die();
    }

    #[NoReturn] public function editSaveAction(array $params = []): void
    {
        $id = $params["id"];
        $data = [
            'first_name_1' => $params["firstName1"],
            'last_name_1' => $params["lastName1"],
            'email_1' => $params["email1"],
            'phone_1' => $params["phoneNumber1"],
            'first_name_2' => $params["firstName2"],
            'last_name_2' => $params["lastName2"],
            'email_2' => $params["email2"],
            'phone_2' => $params["phoneNumber2"],
            'address_1' => $params["address1"],
            'address_2' => $params["address2"],
            'city' => $params["city"],
            'state' => $params["state"],
            'zip' => $params["zipCode"],
            'home_type' => $params["homeType"],
            'notes' => $params["notes"],
            'realtor_id' => $_SESSION["user"]["realtor_id"]
        ];
        $finalData = [];
        foreach ($data as $key => $value)
        {
            $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->client->update($id, $finalData);
        header("Location: /clients/list");
    }

    #[NoReturn] public function showAction(array $params = []): void
    {
        $id = $params["id"];
        $client = $this->client->find($id);
        require_once __DIR__ . '/../../templates/clients/show.phtml';
        die();
    }

    #[NoReturn] public function deleteAction(array $params = []): void
    {
        $id = $params["id"];
        $this->client->delete($id);
        header("Location: /clients/list");
        die();
    }

    #[NoReturn] public function emailsUnsubscriptionAction(array $params = []): void
    {
        $clientId = $params["id"];
        $data = [
            ['path' => 'is_subscribed', 'value' => false]
        ];
        $this->client->update($clientId, $data);
        header("Location: /clients/emails-unsubscription-confirmation");
        die();
    }

    #[NoReturn] public function emailsUnsubscriptionConfirmationAction(array $params = []): void
    {
        require_once __DIR__ . '/../../templates/emails/emails-unsubscription-confirmation.phtml';
        die();
    }

    #[NoReturn] public function sendEmailInvitationToClientAction(array $params = []): void
    {
        $clientsIds = json_decode($params["selectedClients"]);
        $clientsArray = [];
        foreach ($clientsIds as $clientId)
        {
            $clientsArray[] = $this->client->find($clientId);
        }
        $subscribedClients = [];
        foreach ($clientsArray as $client)
        {
            if($client["is_subscribed"]) $subscribedClients[] = $client;
        }
        $unsubscriptionLink = $this->baseUri . "/clients/emails-unsubscription?id=";
        $email = $this->invitationEmail->fetchEmail();
        $emailContent = $email["content"];
        $emailSubject = $email["subject"];
        $realtor = $this->user->fetchUserById($this->loggedUserId);
        $realtorInfo = [
            "{{realtor_name}}" => $realtor["realtor_title"],
            "{{realtor_photo}}" => "'" . $realtor["realtor_photo"] . "'",
            "{{current_year}}" => date("Y"),
        ];
        foreach ($realtorInfo as $key => $value)
        {
            $emailContent = str_replace($key, $value, $emailContent);
        }
        $emailSubject = str_replace("{{realtor_name}}", $_SESSION["user"]["realtor_title"], $emailSubject);
        foreach ($subscribedClients as $subscribedClient)
        {
            $mailer = new MailerService();
            $emailContent = str_replace("{{unsubscribe}}", $unsubscriptionLink . $subscribedClient->id(), $emailContent);
            $mailer->sendInvitationMail($emailContent, $emailSubject, [$subscribedClient["email_1"], $subscribedClient["email_2"]]);
            $data = [['path' => 'email_invite_sent_at', 'value' => new Timestamp(new DateTime())]];
            $this->client->update($subscribedClient->id(), $data);
        }
        header("Location: /clients/list");
        die();
    }
}