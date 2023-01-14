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
        $this->mailer->Host = '127.0.0.1';
        $this->mailer->Port = 1025;
        $this->mailer->SMTPAuth = false;
        /*$this->mailer->Username = 'amessuo@huzokujapan.v-info.info';
        $this->mailer->Password = 'AZ123456az@';*/
    }

    public function sendInvitationMail($content)
    {
        $this->mailer->setFrom("user@honeydoo.dev", "Honeydoo");
        $this->mailer->addReplyTo("user@honeydoo.dev", "Honeydoo");
        $this->mailer->addAddress("oussema.sferi@gmail.com", "Oussema Sferi");
        $this->mailer->isHTML(true);
        $this->mailer->Subject = "Invitation Email";
        $this->mailer->Body = $content;
        if (!$this->mailer->send()) {
            return 'Mailer Error: ' . !$this->mailer->ErrorInfo;
        } else {
            return true;
        }
    }
}