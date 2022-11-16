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
$finalData = [];
$blogId = $_GET["blog_id"];
$title = isset($_POST["blogPostTitle"]) ? $_POST["blogPostTitle"] : "";
$distribution = isset($_POST["blogPostDistribution"]) ? $_POST["blogPostDistribution"] : "";
$imagePath = $_FILES["blogPostImage"]["name"] !== "" ? "/app/blog_posts_images/" . md5(uniqid()) . $_FILES["blogPostImage"]["name"] : "";
$imageDbLink =  $_FILES["blogPostImage"]["name"] !== "" ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $imagePath : "";
if($imagePath !== "")
{
    move_uploaded_file(
        $_FILES["blogPostImage"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
    );
}
$data = [
    'title' => $title,
    'distribution' => $distribution,
    'img' => $imageDbLink,
    'date' => new Timestamp(new DateTime()),
];
foreach ($data as $key => $value)
{
    if($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
}
$database = new Firestore_honeydoo();
$database->updateBlogPost($blogId, $finalData);
header("Location: ../View/blog_posts.php");