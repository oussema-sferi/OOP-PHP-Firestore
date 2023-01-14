<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use App\Service\MailerService;

if(!isset($_SESSION["user"]))
    {
        header("Location: login.php");
    } else {
        if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
        {
            header("Location: ../View/users_list.php");
        }
    }

    $database = new Firestore_honeydoo();
    $email = $database->fetchEmailContent();
    $emailContent = $email["content"];
/*var_dump($emailContent);
die();*/
    $mailer = new MailerService();
    $mailer->sendInvitationMail($emailContent);
    /*var_dump(json_decode($_POST["selectedClients"]));
    die();*/
    header("Location: ../View/clients_list.php");

