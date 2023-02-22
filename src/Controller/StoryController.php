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
    private string $baseUri;
    public function __construct()
    {
        UserChecker::checkUser();
        $this->story = new Story();
        $this->user = new User();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
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
            'img' => $this->baseUri . $imagePath,
            'date' => new Timestamp(new DateTime()),
        ];
        // Create and save new blog post in DB
        $this->story->create($data);
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
    }

    #[NoReturn] public function editForm(array $params = []): void
    {
        $id = $params['id'];
        $story = $this->story->find($id);
        require_once __DIR__ . '/../../templates/stories/edit.phtml';
        die();
    }

    #[NoReturn] public function editSaveAction(array $params = []): void
    {
        $image = $_FILES["image"]["name"];
        if( $image !== "")
        {
            $imagePath =  "/public/uploaded-images/stories/" . md5(uniqid()) . $_FILES["image"]["name"];
            $imagePath = str_replace(" ", "", $imagePath);
            $imageDbLink = $this->baseUri . $imagePath;
            move_uploaded_file(
                $_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
            );
        } else {
            $imageDbLink = "";
        }
        $id = $params["id"];
        $title = $_POST["title"] ?? "";
        $content = $_POST["content"] ?? "";
        $data = [
            'title' => $title,
            'distribution' => $content,
            'img' => $imageDbLink,
            'date' => new Timestamp(new DateTime()),
        ];
        $finalData = [];
        foreach ($data as $key => $value)
        {
            if($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->story->update($id, $finalData);
        header("Location: /stories/list");
    }

    #[NoReturn] public function showAction(array $params = []): void
    {
        $id = $params["id"];
        $story = $this->story->find($id);
        require_once __DIR__ . '/../../templates/stories/show.phtml';
        die();
    }

    #[NoReturn] public function deleteAction(array $params = []): void
    {
        $id = $params["id"];
        $this->story->delete($id);
        header("Location: /stories/list");
        die();
    }
}