<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use DateTime;
use Google\Cloud\Core\Timestamp;

    if(!isset($_SESSION["user"]))
    {
        header("Location: login.php");
    }
    $proServiceId = $_GET["pro_service_id"];
    $database = new Firestore_honeydoo();
    $database->deleteProService($proServiceId);
    header("Location: ../View/pro_services.php");

