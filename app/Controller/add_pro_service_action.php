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
    $imagePath =  "/app/pro_services_images/" . md5(uniqid()) . $_FILES["proServiceImage"]["name"];
    move_uploaded_file(
        $_FILES["proServiceImage"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
    );
    $data = [
        'company_name' => $_POST["companyName"],
        'company_email' => $_POST["companyEmail"],
        'company_phone_number' => $_POST["companyPhoneNumber"],
        'company_website_link' => $_POST["companyWebsiteLink"],
        'homePro_type' => $_POST["homeProType"],
        'comments' => $_POST["comments"],
        'my_notes' => $_POST["myNotes"],
        'realtor_id' => $_SESSION["user"]["realtor_id"],
        'img' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $imagePath,
        'date' => new Timestamp(new DateTime()),
    ];
    $database = new Firestore_honeydoo();
    $database->createNewProService($data);
    header("Location: ../View/pro_services.php");

