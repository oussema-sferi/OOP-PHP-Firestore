<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use DateTime;
use Google\Cloud\Core\Timestamp;

    if(!isset($_SESSION["user"]))
    {
        header("Location: login.php");
    } else {
        if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
        {
            header("Location: users_list.php");
        }
    }

    if($_GET["template"] == "clients")
    {
        $filename = '../import_clients_template.xlsx';
    } elseif ($_GET["template"] == "pro_services") {
        $filename = '../import_homepros_template.xlsx';
    }

    if (file_exists($filename)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
    }
    header("Location: ../View/clients/list.php");

