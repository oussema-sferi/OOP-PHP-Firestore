<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Service\MailerService;
use Google\Cloud\Core\Timestamp;
use DateTime;

class ResetPasswordController
{
    private User $user;
    private ResetPassword $resetPassword;
    private string $baseUri;
    public function __construct()
    {
        $this->user = new User();
        $this->resetPassword = new ResetPassword();
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    public function showRequestPasswordAction(array $params = []): void
    {
        require_once __DIR__ . '/../../templates/security/reset-password/request.phtml';
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
        require_once __DIR__ . '/../../templates/security/reset-password/check-email.phtml';
    }

}