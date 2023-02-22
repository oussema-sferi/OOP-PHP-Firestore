<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\Client;
use App\Service\HelperService;
use App\Service\UserChecker;
use App\Entity\Story;
use App\Entity\User;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;

class StoryController
{
    private Story $story;
    private User $user;
    private string $loggedUserId;
    public function __construct()
    {
        UserChecker::checkUser();
        $this->story = new Story();
        $this->user = new User();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $stories = $this->story->findAllByUser($this->loggedUserId);
        $realtor = $this->user->fetchUserById($this->loggedUserId);
        require_once __DIR__ . '/../../templates/stories/list.phtml';
        die();
    }

    #[NoReturn] public function addForm(array $params = []): void
    {
        require_once __DIR__ . '/../../templates/stories/new.phtml';
        die();
    }

    #[NoReturn] public function createAction(array $params = []): void
    {
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $imagePath =  "/public/uploaded-images/stories/" . md5(uniqid()) . $_FILES["image"]["name"];
        $imagePath = str_replace(" ", "", $imagePath);
        move_uploaded_file(
            $_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
        );
        $title = $params["title"];
        $content = $params["content"];
        $data = [
            'title' => $title,
            'distribution' => $content,
            'realtor_id' => $this->loggedUserId,
            'img' => $baseUrl. $imagePath,
            'date' => new Timestamp(new DateTime()),
        ];
        // Create and save new blog post in DB
        $story = new Story();
        $story->create($data);
        $client = new Client();
        $helper = new HelperService();
        $realtorLinkedPortalClients = $client->fetchPortalClients($this->loggedUserId);
        $allMobileAppClients = $client->fetchMobileAppClients();
        $helper->clientCheckAndSaveSignUpDate($realtorLinkedPortalClients, $allMobileAppClients, $client);
        // Here comes the push notifications
        $realtorLinkedMobileClientsTokens = [];
        foreach ($realtorLinkedPortalClients as $portalClient)
        {
            if(isset($portalClient->notification_token) && trim($portalClient->notification_token) !== "")
            {
                $realtorLinkedMobileClientsTokens[] = $portalClient->notification_token;
            }
        }
        $notificationParameters = [
            "title" => "HoneyDoo Alert",
            "body" => "Your realtor has added a new story. Click here to read it."
        ];
        if(count($realtorLinkedMobileClientsTokens) > 0) {
            $helper->sendFCM($realtorLinkedMobileClientsTokens, $notificationParameters, "/stories/list");
        } else {
            header("Location: /stories/list.php");
        }


        /*require_once __DIR__ . '/../../templates/stories/new.phtml';
        die();*/
    }
}