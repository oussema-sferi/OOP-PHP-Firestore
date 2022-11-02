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
    $imagePath =  "/app/pro_services_images/" . md5(uniqid()) . $_FILES["proServiceImage"]["name"];
    move_uploaded_file(
        $_FILES["proServiceImage"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
    );
    $title = $_POST["proServiceTitle"];
    $subTitle = $_POST["proServiceSubTitle"];
    $data = [
        'title' => $title,
        'sub_title' => $subTitle,
        'realtor_id' => $_SESSION["user"]["realtor_id"],
        'img' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $imagePath,
        'date' => new Timestamp(new DateTime()),
    ];
    $database = new Firestore_honeydoo();
    $database->createNewProService($data);
    header("Location: ../View/pro_services.php");

