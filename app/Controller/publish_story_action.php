<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use App\Service\MailerService;
use DateTime;
use Google\Cloud\Core\Timestamp;

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: ../View/users_list.php");
    }
}
$storiesDocsIds = json_decode($_POST["selectedStories"]);
$finalData = [];
$data = [
    'is_published' => true,
    'published_at' => new Timestamp(new DateTime()),
];
foreach ($data as $key => $value)
{
    if($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
}
$database = new Firestore_honeydoo();
foreach ($storiesDocsIds as $storyId)
{
    $database->updateBlogPost($storyId, $finalData);
}
header("Location: ../View/blog_posts.php");


