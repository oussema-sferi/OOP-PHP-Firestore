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
    $realtorId = $_GET["realtor_id"];

    $imagePath =  "/app/profile_pics_images/" . md5(uniqid()) . $_FILES["profilePicture"]["name"];
    $imagePath = str_replace(" ", "", $imagePath);
    move_uploaded_file(
        $_FILES["profilePicture"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
    );
    $data = [
        'realtor_photo' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $imagePath,
    ];
    $finalData = [];
    foreach ($data as $key => $value)
    {
        if($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
    }
    $database = new Firestore_honeydoo();
    $database->updateRealtorInfo($realtorId, $finalData);
    header("Location: ../View/my_profile.php");

