<?php
namespace App\Service;
use PHPMailer\PHPMailer\PHPMailer;

class MailerService
{
    private $mailer;
    public function __construct()
    {
        $this->mailer = new PHPMailer;
        $this->mailer->isSMTP();
        $this->mailer->SMTPDebug = 2;
        $this->mailer->Debugoutput = 'error_log';
        $this->mailer->Host = 'smtp.office365.com';
        $this->mailer->Port = 587;
        $this->mailer->SMTPAutoTLS = true;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'info@honeydoo.io';
        $this->mailer->Password = 'Tmvh5282!';
    }

    public function sendInvitationMail($content, $emailAddresses)
    {
        $this->mailer->setFrom("info@honeydoo.io", "Honeydoo");
        $this->mailer->addReplyTo("info@honeydoo.io", "Honeydoo");
        foreach ($emailAddresses as $emailRecipient)
        {
            $this->mailer->AddAddress(trim($emailRecipient));
        }
        $this->mailer->isHTML(true);
        $this->mailer->Subject = "App Download Invitation";
        $this->mailer->Body = $content;
        if (!$this->mailer->send()) {
            return 'Mailer Error: ' . !$this->mailer->ErrorInfo;
        } else {
            return true;
        }
    }
}