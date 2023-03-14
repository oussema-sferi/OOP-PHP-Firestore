<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\Client;
use App\Service\HelperService;
use App\Service\UserCheckerService;
use App\Entity\Story;
use App\Entity\User;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;

class StoryController
{
    private Story $story;
    private User $user;
    private Client $client;
    private string $loggedUserId;
    private string $baseUri;
    public function __construct()
    {
        UserCheckerService::checkUser();
        $this->story = new Story();
        $this->user = new User();
        $this->client = new Client();
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
        $image = $_FILES["image"]["name"];
        if(trim($image) !== "")
        {
            $imagePath =  "/public/uploaded-images/stories/" . md5(uniqid()) . $image;
            $imagePath = str_replace(" ", "", $imagePath);
            move_uploaded_file(
                $_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
            );
            $imageDbLink = $this->baseUri . $imagePath;
        } else {
            $imageDbLink = "";
        }

        $title = $params["title"];
        $content = $params["content"];
        $data = [
            'title' => $title,
            'distribution' => $content,
            'realtor_id' => $this->loggedUserId,
            'img' => $imageDbLink,
            'date' => new Timestamp(new DateTime()),
        ];
        // Create and save new blog post in DB
        $this->story->create($data);
        $redirectUri = "/stories/list";
        $notificationParameters = [
            "title" => "HoneyDoo Alert",
            "body" => "Your realtor has added a new story. Click here to read it."
        ];
        // Here comes the push notifications
        $helper = new HelperService();
        $helper->clientCheckAndSaveSignUpDate($this->client, $this->loggedUserId, $notificationParameters, $redirectUri);
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
            move_uploaded_file(
                $_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
            );
            $imageDbLink = $this->baseUri . $imagePath;
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

    #[NoReturn] public function publishStoryAction(array $params = []): void
    {
        $storiesIds = json_decode($params["selectedStories"]);
        $finalData = [];
        $data = [
            'is_published' => true,
            'published_at' => new Timestamp(new DateTime()),
        ];
        foreach ($data as $key => $value)
        {
            $finalData[] = ['path' => $key, 'value' => $value];
        }
        foreach ($storiesIds as $storyId)
        {
            $this->story->update($storyId, $finalData);
        }
        header("Location: /stories/list");
    }
}