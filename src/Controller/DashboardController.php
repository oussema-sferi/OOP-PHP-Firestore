<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\User;
use App\Service\HelperService;
use App\Service\UserCheckerService;
use Google\Cloud\Core\Timestamp;
use DateTime;
use JetBrains\PhpStorm\NoReturn;

class DashboardController
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
        $user = $this->user->fetchUserById($this->loggedUserId);
        $baseUri = $this->baseUri;
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/dashboard/my-profile.phtml';
    }

    #[NoReturn] public function updateMyProfilePictureAction(array $params = []): void
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
        $user = $this->user->fetchUserById($this->loggedUserId);
        $_SESSION["user"] = $user;
        header("Location: /dashboard/my-profile");
        die();
    }

    #[NoReturn] public function editMyProfileAction(array $params = []): void
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
        $user = $this->user->fetchUserById($this->loggedUserId);
        $_SESSION["user"] = $user;
        header("Location: /dashboard/my-profile");
        die();
    }

    public function showCheckPasswordAction(array $params = []): void
    {
        $realtor = $this->user->fetchUserById($this->loggedUserId);
        $helper = new HelperService();
        if(isset($_SESSION['validate_password_error_flash_message'])) {
            $errorMessage = $_SESSION['validate_password_error_flash_message'];
            unset($_SESSION['validate_password_error_flash_message']);
        }
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/security/change-password/check-password.phtml';
    }

    public function checkPasswordAction(array $params = []): void
    {
        $password = $params["actualPassword"];
        if($this->user->validateActualPassword($_SESSION["user"]["email"], $password))
        {
            $_SESSION["user_is_authorized"] = true;
            header("Location: /dashboard/change-password");
        } else {
            $_SESSION['validate_password_error_flash_message'] = "Your password is invalid !";
            header("Location: /dashboard/password-verification");
        }
    }

    public function showChangePasswordAction(array $params = []): void
    {
        if(!isset($_SESSION['user_is_authorized']) || !$_SESSION['user_is_authorized'])
        {
            header("Location: /dashboard/password-verification");
        }
        $realtor = $this->user->fetchUserById($this->loggedUserId);
        $helper = new HelperService();
        if(isset($_SESSION['change_password_error_flash_message'])) {
            $errorMessage = $_SESSION['change_password_error_flash_message'];
            unset($_SESSION['change_password_error_flash_message']);
        }
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/security/change-password/change-password.phtml';
    }

    public function changePasswordAction(array $params = []): void
    {
        if(!isset($_SESSION['user_is_authorized']) || !$_SESSION['user_is_authorized'])
        {
            header("Location: /dashboard/password-verification");
        }
        $password = trim($params["newPassword"]);
        $confirmPassword = trim($params["confirmPassword"]);
        if($password !== $confirmPassword)
        {
            $_SESSION['change_password_error_flash_message'] = "Your passwords did not match! Please try again";
            header("Location: /dashboard/change-password");
            die();
        }
        if (!(strlen($password) >= 8 && strpbrk($password, "!#$@.,:;()"))){
            // next code block
            $_SESSION['change_password_error_flash_message'] = "Your password is not strong enough. Please use another one and try again.";
            header("Location: /dashboard/change-password");
            die();
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = $this->user->findByEmail($_SESSION["user"]["email"]);
        $finalData[] = ['path' => 'password', 'value' => $hashedPassword];
        $this->user->update($user["realtor_id"], $finalData);
        unset($_SESSION['user_is_authorized']);
        header("Location: /stories/list");
    }
}