<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;

    if(!isset($_SESSION["user"]))
    {
        header("Location: login.php");
    } else {
        if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_USER")
        {
            header("Location: blog_posts.php");
        }
    }
    $emailContent = $_POST["emailContent"];
    /*$emailSubject = $_POST["emailSubject"];*/
    $data = [
        'content' => $emailContent,
        /*'subject' => $emailSubject*/
    ];
    $finalData = [];
    foreach ($data as $key => $value) {

        if ($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
    }
    $database = new Firestore_honeydoo();
    $database->updateEmailContent($finalData);
    header("Location: ../View/email_content.php");

