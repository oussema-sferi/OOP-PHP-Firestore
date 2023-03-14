<?php
namespace App\Service;
use PHPMailer\PHPMailer\PHPMailer;
class MailerService
{
    const ADMIN_DATA = [
        'from_email' => 'info@honeydoo.io',
        'from_name' => 'Honeydoo',
    ];
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer;
        $this->mailer->isSMTP();
        $this->mailer->SMTPDebug = 2;
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->Encoding = 'base64';
        $this->mailer->Debugoutput = 'error_log';
        $this->mailer->Host = 'smtp.office365.com';
        $this->mailer->Port = 587;
        $this->mailer->SMTPAutoTLS = true;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'info@honeydoo.io';
        $this->mailer->Password = 'Tmvh5282!';
        $this->mailer->setFrom(self::ADMIN_DATA["from_email"], self::ADMIN_DATA["from_name"]);
        $this->mailer->addReplyTo(self::ADMIN_DATA["from_email"], self::ADMIN_DATA["from_name"]);
    }

    public function sendInvitationMail($content, $subject, $emailAddresses)
    {
        $this->mailer->addAddress($emailAddresses[0]);
        $this->mailer->addCC($emailAddresses[1]);
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $content;
        if (!$this->mailer->send()) {
            return 'Mailer Error: ' . !$this->mailer->ErrorInfo;
        } else {
            return true;
        }
    }

    public function sendResetPasswordMail($content, $subject, $emailAddress)
    {
        $this->mailer->addAddress($emailAddress);
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $content;
        if (!$this->mailer->send()) {
            return 'Mailer Error: ' . !$this->mailer->ErrorInfo;
        } else {
            return true;
        }
    }
}