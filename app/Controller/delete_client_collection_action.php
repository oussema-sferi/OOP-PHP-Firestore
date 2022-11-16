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
    $blogId = $_GET["blog_id"];
    $database = new Firestore_honeydoo();
    $database->deleteBlogPost($blogId);
    header("Location: ../View/blog_posts.php");

