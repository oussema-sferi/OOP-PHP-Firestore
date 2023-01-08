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
    $data[] = ['path' => 'content', 'value' => $emailContent];
    $database = new Firestore_honeydoo();
    $database->updateEmailContent($data);
    header("Location: ../View/email_content.php");

