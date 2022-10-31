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
    $imagePath =  "/app/blog_posts_images/" . $_FILES["blogPostImage"]["name"];
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
    $database->createNewBlogPost($data);
    header("Location: ../View/blog_posts.php");

