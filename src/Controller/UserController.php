<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\User;
use App\Service\UserCheckerService;
use Google\Cloud\Core\Timestamp;
use DateTime;

class UserController
{
    private User $user;
    private string $loggedUserId;
    private string $baseUri;
    public function __construct()
    {
        UserCheckerService::checkUser();
        $this->user = new User();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    public function myProfileShowAction(array $params = []): void
    {
        $theUser = $this->user->fetchUserById($this->loggedUserId);
        $baseUri = $this->baseUri;
        require_once __DIR__ . '/../../templates/user/my-profile.phtml';
    }

    public function updateProfilePictureAction(array $params = []): void
    {
        $realtorId = $params["id"];
        $imagePath = "/public/uploaded-images/profile-pictures/" . md5(uniqid()) . $_FILES["profilePicture"]["name"];
        $imagePath = str_replace(" ", "", $imagePath);
        move_uploaded_file(
            $_FILES["profilePicture"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
        );
        $data = [
            'realtor_photo' => $this->baseUri . $imagePath,
        ];
        $finalData = [];
        foreach ($data as $key => $value)
        {
            if($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->user->update($realtorId, $finalData);
        header("Location: /user/my-profile");
        die();
    }

    public function editProfileAction(array $params = []): void
    {

        $realtorId = $params["id"];
        $finalData = [];
        $data = [
            'realtor_title' => $params["fullName"] ?? "",
            'email' => $params["emailAddress"] ?? "",
            'phone_number' => $params["phoneNumber"] ?? "",
            'realtor_sub_title' => $params["companyName"] ?? "",
            'about_me' => $params["aboutMe"] ?? "",
        ];
        foreach ($data as $key => $value) {

            $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->user->update($realtorId, $finalData);
        header("Location: /user/my-profile");
        die();
    }
}