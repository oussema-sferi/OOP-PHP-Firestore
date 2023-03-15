<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\InvitationEmail;
use App\Entity\ResetPassword;
use JetBrains\PhpStorm\NoReturn;
use Google\Cloud\Core\Timestamp;
use DateTime;

class EmailController
{
    private InvitationEmail $invitationEmail;
    private ResetPassword $resetPassword;
    private string $loggedUserId;
    private string $baseUri;
    public function __construct()
    {
        $this->invitationEmail = new InvitationEmail();
        $this->resetPassword = new ResetPassword();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    #[NoReturn] public function invitationEmailAction(array $params = []): void
    {
        $invitationEmail = $this->invitationEmail->fetchEmail();
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/emails/invitation-email/template.phtml';
        die();
    }

    #[NoReturn] public function saveInvitationEmailAction(array $params = []): void
    {
        $data = [
            'subject' => $params["emailSubject"],
            'content' => $params["emailContent"]
        ];
        $finalData = [];
        foreach ($data as $key => $value) {
            $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->invitationEmail->updateEmail($finalData);
        header("Location: /admin/emails/invitation");
    }

    #[NoReturn] public function resetPasswordEmailAction(array $params = []): void
    {
        $resetPasswordEmail = $this->resetPassword->fetchResetEmail();
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/emails/reset-password-email/template.phtml';
        die();
    }

    #[NoReturn] public function saveResetPasswordEmailAction(array $params = []): void
    {
        $data = [
            'subject' => $params["emailSubject"],
            'content' => $params["emailContent"]
        ];
        $finalData = [];
        foreach ($data as $key => $value) {
            $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->resetPassword->updateResetEmail($finalData);
        header("Location: /admin/emails/reset-password");
    }
}