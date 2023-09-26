<?php

declare(strict_types=1);

namespace App\Controller\Realtor;
use App\Entity\MobileAppClient;
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
    private MobileAppClient $mobileAppClient;
    const API_KEY = "50f679feb5dc9414338baff5ef48b364";
    public function __construct()
    {
        AuthCheckerService::checkIfRealtor();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $this->noImagePath = $this->baseUri . "/public/uploaded-images/stories/no-image/no-image-available.jpg";
        $this->story = new Story();
        $this->client = new PortalClient();
        $this->mobileAppClient = new MobileAppClient();
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

    #[NoReturn] public function shareLinkForm(array $params = []): void
    {
        if (isset($_SESSION['story_fail_flash_message']))
        {
            $failFlashMessage = $_SESSION['story_fail_flash_message'];
            unset($_SESSION['story_fail_flash_message']);
        }
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/stories/link-share-form.phtml';
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
        $category = $params["category"];
        $content = $params["content"];
        $data = [
            'title' => $title,
            'category' => $category,
            'distribution' => $content,
            'realtor_id' => $this->loggedUserId,
            'img' => $this->baseUri . $imageDbLink,
            'date' => new Timestamp(new DateTime()),
            'is_published' => false
        ];
        // Create and save new blog post in DB
        $this->story->create($data);
        // Create Articles Links Previews
        /*if(isset($params["articles"])) $this->fetchArticlesData($params["articles"], $newStoryDocId);*/
        //
        $_SESSION['story_success_flash_message'] = "Your story has just been created successfully!";
        header("Location: /stories/list");
    }

    #[NoReturn] public function shareLinkAction(array $params = []): void
    {
        /*var_dump($params);
        die;*/
        if(isset($params["link"]) && $params["link"] !== "")
        {
            if (filter_var($params["link"], FILTER_VALIDATE_URL) === FALSE) {
                $_SESSION['story_fail_flash_message'] = "The provided link is invalid!";
                header("Location: /stories/share-link");
            } else {
                $this->fetchLinkData($params);
                $_SESSION['story_success_flash_message'] = "Your story has just been created successfully!";
                header("Location: /stories/list");
            }
        }
    }

    #[NoReturn] public function editForm(array $params = []): void
    {
        $id = $params['id'];
        $story = $this->story->find($id);
        $image = isset($story["img"]) && trim($story["img"]) !== '' ? $story["img"] : $this->noImagePath;
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
        $category = $_POST["category"] ?? "";
        $data = [
            'title' => $title,
            'category' => $category,
            'distribution' => $content,
            'img' => $this->baseUri . $imageDbLink,
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
        if(isset($story["url"]))
        {
            require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/stories/story-link-show.phtml';
        } else {
            $image = isset($story["img"]) && trim($story["img"]) !== '' ? $story["img"] : $this->noImagePath;
            require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/stories/show.phtml';
        }
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
        $text = count($storiesIds) === 1 ? "Story has" : "Stories have";
        /*$_SESSION['story_success_flash_message'] = "$text just been published successfully !";*/
        /*header("Location: /stories/list");*/
        //
        $redirectUri = "/stories/list";
        $notificationParameters = [
            "title" => "HoneyDoo Alert",
            "body" => "Your Realtor just posted a new story! Check it out!"
        ];
        // Here comes the push notifications
        $helper = new HelperService();
        $helper->clientCheckAndSaveSignUpDate($this->client, $this->loggedUserId, $notificationParameters, $redirectUri, "story", true, $this->mobileAppClient, $text);
    }
    private function fetchLinkData(array $linkParams)
    {
        $apiKey = self::API_KEY;
        $link = $linkParams["link"];
        $category = $linkParams["category"];
        $parsedUrl = parse_url($link);
        $siteName = $parsedUrl["scheme"] . "://" . $parsedUrl["host"];
        // GET Request to LINK PREVIEW API
        $target = urlencode($link);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.linkpreview.net?key={$apiKey}&q={$target}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($ch), true);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // Error
        if ($status != 200) {
            // something went wrong
            $shareLinkData = [
                'title' => 'No title available',
                'category' => $category,
                'description' => 'No description available',
                'realtor_id' => $this->loggedUserId,
                'date' => new Timestamp(new DateTime()),
                'is_published' => false,
                'img' => 'https://upload.wikimedia.org/wikipedia/commons/d/dc/No_Preview_image_2.png',
                'url' => $link,
                'site_name' => $siteName,
            ];
        } else {
            $title = $output['title'] !== "" ? $output['title'] : 'No title available';
            $description = $output['description'] !== "" ? $output['description'] : 'No description available';
            $image = $output['image'] !== "" ? $output['image'] : 'https://upload.wikimedia.org/wikipedia/commons/d/dc/No_Preview_image_2.png';
            $shareLinkData = [
                'title' => $title,
                'category' => $category,
                'description' => $description,
                'realtor_id' => $this->loggedUserId,
                'date' => new Timestamp(new DateTime()),
                'is_published' => false,
                'img' => $image,
                'url' => $link,
                'site_name' => $siteName,
            ];
        }
        $this->story->create($shareLinkData);
    }
}