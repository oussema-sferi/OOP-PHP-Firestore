<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\User;
use App\Service\AuthCheckerService;
use Google\Cloud\Core\Timestamp;
use DateTime;

class RegistrationController
{
    public function __construct()
    {
        AuthCheckerService::checkIfAuthenticated();
    }
    public function show(array $params = []): void
    {
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/security/registration.phtml';
    }

    public function registrationAction(array $params = []): void
    {
        $email = $params["emailAddress"];
        $password = $params["password"];
        $confirmPassword = $params["confirmPassword"];
        $user = new User();
        if($user->checkIfUserExists($email))
        {
            $_SESSION['registration_error_flash_message'] = "This email is already used! Please choose another email and try again";
            header("Location: /registration");
            die();
        }

        if($password !== $confirmPassword)
        {
            $_SESSION['registration_error_flash_message'] = "Your passwords did not match! Please try again";
            header("Location: /registration");
            die();
        }

        if (!(strlen($password) >= 8 && strpbrk($password, "!#$@.,:;()"))){
            // next code block
            $_SESSION['registration_error_flash_message'] = "Your password is not strong enough. Please use another one and try again.";
            header("Location: /registration");
            die();
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'email' => $email,
            'password' => $hashedPassword,
            'role' => "ROLE_USER",
            'realtor_title' => $params["fullName"],
            'realtor_sub_title' => $params["companyName"],
            'phone_number' => $params["phoneNumber"],
            'realtor_photo' => "",
            'address_line_1' => "This is a fake address one",
            'address_line_2' => "This is a fake address two",
            'city' => "Fake City",
            'zip_code' => "51426",
            'homePro_type' => "m is simply dummy text of the printing and typesetting industry",
            'realtor_comments' => "is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
            'date' => new Timestamp(new DateTime()),
        ];
        $newUserDocId = $user->createNewUser($data);
        $user->setUserId($newUserDocId);
        $_SESSION['registration_success_flash_message'] = "Congratulations! Your account has been created successfully. You can use your credentials to login";
        header("Location: /login");
    }

}