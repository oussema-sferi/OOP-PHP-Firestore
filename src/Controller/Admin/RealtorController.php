<?php

declare(strict_types=1);

namespace App\Controller\Admin;
use App\Service\AuthCheckerService;
use App\Entity\User;
use App\Entity\PortalClient;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;

class RealtorController
{
    private User $user;
    private PortalClient $client;
    private string $loggedUserId;
    private string $baseUri;
    public function __construct()
    {
        AuthCheckerService::checkIfAdmin();
        $this->user = new User();
        $this->client = new PortalClient();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $realtors = $this->user->fetchUsersByRole("ROLE_USER");
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/realtors/list.phtml';
        die();
    }

    #[NoReturn] public function addForm(array $params = []): void
    {
        if(isset($_SESSION['email_error_flash_message'])) {
            $emailErrorMessage = $_SESSION['email_error_flash_message'];
            unset($_SESSION['email_error_flash_message']);
        }
        if(isset($_SESSION['password_error_flash_message'])) {
            $passwordErrorMessage = $_SESSION['password_error_flash_message'];
            unset($_SESSION['password_error_flash_message']);
        }
        if(isset($_SESSION['confirm_password_error_flash_message'])) {
            $confirmPasswordErrorMessage = $_SESSION['confirm_password_error_flash_message'];
            unset($_SESSION['confirm_password_error_flash_message']);
        }
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/realtors/new.phtml';
        die();
    }

    #[NoReturn] public function createAction(array $params = []): void
    {
        $email = $params["email"];
        $password = $params["password"];
        $confirmPassword = $params["confirmPassword"];
        if($this->user->checkIfUserExists($email))
        {
            $_SESSION['email_error_flash_message'] = "This email is already used! Please choose another email and try again";
            header("Location: /admin/realtors/new");
            die();
        }
        if (!(strlen($password) >= 8 && strpbrk($password, "!#$@.,:;()"))){
            // next code block
            $_SESSION['password_error_flash_message'] = "The password is not strong enough. Please use another one and try again.";
            header("Location: /admin/realtors/new");
            die();
        }
        if($password !== $confirmPassword)
        {
            $_SESSION['confirm_password_error_flash_message'] = "The passwords did not match! Please try again";
            header("Location: /admin/realtors/new");
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
            'address_line_1' => $params["address1"],
            'address_line_2' => $params["address2"],
            'city' => $params["city"],
            'zip_code' => $params["zipCode"],
            'is_deleted' => false,
            'date' => new Timestamp(new DateTime()),
        ];

        $realtorId = $this->user->createNewUser($data);
        $this->user->setUserId($realtorId);
        header("Location: /admin/realtors/list");
    }

    #[NoReturn] public function editForm(array $params = []): void
    {
        $id = $params['id'];
        $realtor = $this->user->fetchUserById($id);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/realtors/edit.phtml';
        die();
    }

    #[NoReturn] public function editSaveAction(array $params = []): void
    {
        $finalData = [];
        $realtorId = $params["id"];
        $data = [
            'realtor_title' => $params["fullName"] ?? "",
            'email' => $params["email"] ?? "",
            'realtor_sub_title' => $params["companyName"] ?? "",
            'phone_number' => $params["phoneNumber"] ?? "",
            'address_line_1' => $params["address1"] ?? "",
            'address_line_2' => $params["address2"] ?? "",
            'city' => $params["city"] ?? "",
            'zip_code' => $params["zipCode"] ?? "",
        ];
        foreach ($data as $key => $value) {

            $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->user->update($realtorId, $finalData);
        header("Location: /admin/realtors/list");
    }

    #[NoReturn] public function showAction(array $params = []): void
    {
        $id = $params["id"];
        $realtor = $this->user->fetchUserById($id);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/realtors/show.phtml';
        die();
    }

    #[NoReturn] public function deleteAction(array $params = []): void
    {
        $realtorId = $params["id"];
        // set Realtor as deleted
        $this->user->markRealtorAsDeleted($realtorId);
        // get Realtor added clients
        $realtorClients = $this->user->fetchUserAddedClients($realtorId);
        // delete Realtor associated clients
        foreach ($realtorClients as $client)
        {
            $this->client->markClientAsDeleted($client->doc_id);
        }
        header("Location: /admin/realtors/list");
        die();
    }
}