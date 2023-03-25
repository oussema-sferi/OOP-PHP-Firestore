<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Service\AuthCheckerService;
use App\Service\MailerService;
use Google\Cloud\Core\Timestamp;
use DateTime;

class ResetPasswordController
{
    private string $baseUri;
    private User $user;
    private ResetPassword $resetPassword;
    public function __construct()
    {
        AuthCheckerService::checkIfAuthenticated();
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $this->user = new User();
        $this->resetPassword = new ResetPassword();
    }

    public function showRequestPasswordAction(array $params = []): void
    {
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/security/reset-password/request.phtml';
    }

    public function requestPasswordAction(array $params = []): void
    {
        $email = $params["email"];
        $user = $this->user->findByEmail($email);
        if(!$user)
        {
            header("Location: /reset-password/check-email");
            die();
        }
        try {
            $token = bin2hex(random_bytes(50));
            $data = [
                'realtor_email' => $user["email"],
                'token' => $token,
                'requested_at' => new Timestamp(new DateTime()),
            ];
            $this->resetPassword->saveResetPasswordToken($data);
            $resetPasswordEmail = $this->resetPassword->fetchResetEmail();
            $emailContent = $resetPasswordEmail["content"];
            $emailSubject = $resetPasswordEmail["subject"];
            $mailer = new MailerService();
            $resetPasswordLink = $this->baseUri . "/reset-password/validate-token?token=" . $token;
            $emailContent = str_replace("{{reset_password_link}}", $resetPasswordLink, $emailContent);
            $mailer->sendResetPasswordMail($emailContent, $emailSubject, $user["email"]);
            header("Location: /reset-password/check-email");
        } catch (\Exception $e) {
            header("Location: /reset-password/check-email");
        }
    }

    public function checkEmailAction(array $params = []): void
    {
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/security/reset-password/check-email.phtml';
    }

    public function validateTokenAction(array $params = []): void
    {
        $token = trim($params["token"]);
        if($token == "")
        {
            header("Location: /reset-password/request");
            die();
        }
        $resetRequest = $this->resetPassword->fetchTokenFromDb($token);
        if(!$resetRequest)
        {
            header("Location: /reset-password/request");
            die();
        }
        $_SESSION["token"] = $token;
        header("Location: /reset-password/reset");
    }

    public function showChangePasswordAction(array $params = []): void
    {
        if(!isset($_SESSION['token']))
        {
            header("Location: /reset-password/request");
            die();
        }
        $tokenFromSession = $_SESSION['token'];
        $token = $this->resetPassword->fetchTokenFromDb($tokenFromSession);
        if(!$token)
        {
            header("Location: /reset-password/request");
            die();
        }
        if(isset($_SESSION['change_password_error_flash_message'])) {
            $errorMessage = $_SESSION['change_password_error_flash_message'];
            unset($_SESSION['change_password_error_flash_message']);
        }
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/security/reset-password/reset.phtml';
    }

    public function changePasswordAction(array $params = []): void
    {
        if(!isset($_SESSION['token']))
        {
            header("Location: /reset-password/request");
            die();
        }
        $tokenFromSession = $_SESSION['token'];
        $resetRequest = $this->resetPassword->fetchTokenFromDb($tokenFromSession);
        if(!$resetRequest)
        {
            header("Location: /reset-password/request");
            die();
        }
        $password = trim($params["password"]);
        $confirmPassword = trim($params["confirmPassword"]);
        if($password !== $confirmPassword)
        {
            $_SESSION['change_password_error_flash_message'] = "Your passwords did not match! Please try again";
            header("Location: /reset-password/reset");
            die();
        }
        if (!(strlen($password) >= 8 && strpbrk($password, "!#$@.,:;()"))){
            // next code block
            $_SESSION['change_password_error_flash_message'] = "Your password is not strong enough. Please use another one and try again.";
            header("Location: /reset-password/reset");
            die();
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = $this->user->findByEmail($resetRequest["realtor_email"]);
        $finalData[] = ['path' => 'password', 'value' => $hashedPassword];
        $this->user->update($user["realtor_id"], $finalData);
        $this->resetPassword->deleteResetRequest($resetRequest["token"]);
        unset($_SESSION['token']);
        header("Location: /reset-password/confirmation");
        die();
    }

    public function changePasswordConfirmationAction(array $params = []): void
    {

        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/security/reset-password/confirmation.phtml';
    }
}