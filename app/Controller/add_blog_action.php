<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;
use DateTime;
use Google\Cloud\Core\Timestamp;

/*$helper = new HelperService();
$helper->sendFCM();*/
    if(!isset($_SESSION["user"]))
    {
        header("Location: login.php");
    } else {
        if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
        {
            header("Location: users_list.php");
        }
    }
    $imagePath =  "/app/blog_posts_images/" . md5(uniqid()) . $_FILES["blogPostImage"]["name"];
    $imagePath = str_replace(" ", "", $imagePath);
    move_uploaded_file(
        $_FILES["blogPostImage"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
    );
    $title = $_POST["blogPostTitle"];
    $distribution = $_POST["blogPostDistribution"];
    $data = [
        'title' => $title,
        'distribution' => $distribution,
        'realtor_id' => $_SESSION["user"]["realtor_id"],
        'img' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $imagePath,
        'date' => new Timestamp(new DateTime()),
    ];
    $database = new Firestore_honeydoo();
    // Create and save new blog post in DB
    $database->createNewBlogPost($data);

    // Here comes the push notifications
    header("Location: ../View/blog_posts.php");

