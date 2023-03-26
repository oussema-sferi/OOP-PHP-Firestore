<?php

declare(strict_types=1);

namespace App\Controller\Realtor;
use App\Entity\PortalClient;
use App\Service\AuthCheckerService;
use App\Service\HelperService;
use App\Entity\Story;
use App\Entity\User;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;

class StoryController
{
    private string $loggedUserId;
    private string $baseUri;
    private string $noImagePath;
    private readonly Story $story;
    private User $user;
    private PortalClient $client;
    public function __construct()
    {
        AuthCheckerService::checkIfRealtor();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $this->noImagePath = $this->baseUri . "/public/uploaded-images/stories/no-image/no-image-available.jpg";
        $this->story = new Story();
        $this->client = new PortalClient();
        $this->user = new User();
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $stories = $this->story->findAllByUser($this->loggedUserId);
        $realtor = $this->user->fetchUserById($this->loggedUserId);
        if(isset($_SESSION['story_success_flash_message'])) {
            $successFlashMessage = $_SESSION['story_success_flash_message'];
            unset($_SESSION['story_success_flash_message']);
        }
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/stories/list.phtml';
        die();
    }

    #[NoReturn] public function addForm(array $params = []): void
    {
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/stories/new.phtml';
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
            $imageDbLink = $imagePath;
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
        $_SESSION['story_success_flash_message'] = "Your story has just been created successfully !";
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
        $image = isset($story["img"]) && trim($story["img"]) !== '' && file_exists($_SERVER["DOCUMENT_ROOT"] . $story["img"]) ? $this->baseUri . $story["img"] : $this->noImagePath;
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/stories/edit.phtml';
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
            $imageDbLink = $imagePath;
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
        $_SESSION['story_success_flash_message'] = "Your story has just been updated successfully !";
        header("Location: /stories/list");
    }

    #[NoReturn] public function showAction(array $params = []): void
    {
        $id = $params["id"];
        $story = $this->story->find($id);
        $image = isset($story["img"]) && trim($story["img"]) !== '' && file_exists($_SERVER["DOCUMENT_ROOT"] . $story["img"]) ? $this->baseUri . $story["img"] : $this->noImagePath;
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/stories/show.phtml';
        die();
    }

    #[NoReturn] public function deleteAction(array $params = []): void
    {
        $id = $params["id"];
        $this->story->delete($id);
        $_SESSION['story_success_flash_message'] = "Your story has just been deleted successfully !";
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