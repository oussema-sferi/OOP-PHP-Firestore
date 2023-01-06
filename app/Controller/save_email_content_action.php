<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;

    if(!isset($_SESSION["user"]))
    {
        header("Location: login.php");
    }
    $emailContent = $_POST["emailContent"];
    /*$data = [
        'content' => $emailContent
    ];*/
    $finalData[] = ['path' => 'content', 'value' => $emailContent];
    $database = new Firestore_honeydoo();
    $database->updateEmailContent($finalData);
    header("Location: ../View/email_content.php");

