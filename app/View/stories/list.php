<?php
namespace App\View;
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;

require_once "../../../vendor/autoload.php";
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/app/View/";
if(!isset($_SESSION["user"]))
{
    $loginPage = $baseUrl . 'common/security/login.php';
    header("Location: $loginPage");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        $adminUsersPage = $baseUrl . 'admin/users/list.php';
        header("Location: $adminUsersPage");
    }
}
$database = new Firestore_honeydoo();
$loggedUserBlogPosts = $database->fetchBlogPosts($_SESSION["user"]["realtor_id"]);

//
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../layout/realtor/header.php';?>
    <title>My Stories</title>
</head>
<body class="nav-fixed">
<?php include '../layout/realtor/navbar.php';?>
<div id="layoutSidenav">
    <?php include '../layout/realtor/sidebar.php';?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-fluid px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="list"></i></div>
                                    Stories List
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='new.php'?>">
                                    <i class="me-1" data-feather="plus"></i>
                                    Create New Story
                                </a>
                            <div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4">
                <div class="card">
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                            <tr>
                                <th class='text-center' style='vertical-align: middle; max-width: 10px'><input type='checkbox' class="checkAll"></th>
                                <th class='text-center' style='vertical-align: middle'>Story Title</th>
                                <th class='text-center' style='vertical-align: middle'>Created At</th>
                                <th class='text-center' style='vertical-align: middle'>Published At</th>
                                <th class='text-center' style='vertical-align: middle'>Views</th>
                                <th class='text-center' style='vertical-align: middle'>Likes</th>
                                <th class='text-center' style='vertical-align: middle'>Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th><input type='checkbox' class="checkAll"></th>
                                <th>Story Title</th>
                                <th>Created At</th>
                                <th>Published At</th>
                                <th>Views</th>
                                <th>Likes</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            foreach ($loggedUserBlogPosts as $blogPost)
                            {
                                $docId = $blogPost->doc_id;
                                $title = $blogPost->title;
                                $createdAt = $blogPost->date->get()->format("m-d-Y");
                                if(isset($blogPost->views)) {
                                    $views = $blogPost->views;
                                } else {
                                    $views = 0;
                                }

                                if(isset($blogPost->user_liked)) {
                                    $likes = count($blogPost->user_liked);
                                } else {
                                    $likes = 0;
                                }
                                /*if(isset($blogPost->is_published) && trim($blogPost->is_published) !== "") {
                                    $isPublished = "Yes";
                                } else {
                                    $isPublished = "No";
                                }*/
                                if(isset($blogPost->published_at) && trim($blogPost->published_at) !== "") {
                                    $publishedAt = $blogPost->published_at->get()->format("m-d-Y");
                                } else {
                                    $publishedAt = "";
                                }
                                $blogPostId = $blogPost->doc_id;
                                $showBlogPostLink = "blog_show.php?blog_id=$blogPostId";
                                $editBlogPostLink = "blog_edit.php?blog_id=$blogPostId";
                                $deleteBlogPostLink = "../Controller/delete_blog_action.php?blog_id=$blogPostId";
                                echo "
                                <tr>
                                    <td class='text-center' style='vertical-align: middle'><input type='checkbox' value=$docId></td>
                                    <td class='text-center' style='vertical-align: middle'>$title</td>
                                    <td class='text-center' style='min-width: 70px; vertical-align: middle'>$createdAt</td>
                                    <td class='text-center' style='min-width: 70px; vertical-align: middle'>$publishedAt</td>
                                    <td class='text-center' style='vertical-align: middle'>$views</td>
                                    <td class='text-center' style='vertical-align: middle'>$likes</td>
                                    <td class='text-center'>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark me-2' href=$showBlogPostLink><i data-feather='zoom-in'></i></a>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark me-2' href=$editBlogPostLink><i data-feather='edit'></i></a>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark' data-bs-toggle='modal' data-bs-target='#approveUserModal$blogPostId'><i data-feather='trash-2'></i></a>                                                                                                                              
                                    </td>                                                      
                                </tr>
                                
                                <div class='modal fade' id='approveUserModal$blogPostId' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                                    <div class='modal-dialog modal-dialog-centered' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header d-block'>
                                                <button class='btn-close float-end' type='button' data-bs-dismiss='modal' aria-label='Close'></button>
                                                <h5 class='modal-title text-center' id='exampleModalCenterTitle'>Removal Confirmation</h5>
                                            </div>
                                            <div class='modal-body text-center'>
                                                Do you really want to delete this story ?
                                            </div>
                                            <div class='modal-footer justify-content-center'>
                                                <a class='btn btn-secondary' type='button' data-bs-dismiss='modal'>No</a>
                                                <a class='btn btn-success' type='button' href='$deleteBlogPostLink'>Yes</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ";
                            };
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../layout/realtor/footer.php';?>
    </div>
</div>
<?php include '../layout/realtor/scripts.php';?>
<script>
    $( document ).ready(function() {
        let storiesArray = [];
        $(".checkAll").click(function () {
            $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            if($(this).prop('checked') == true)
            {
                $('input[type=checkbox][class!=checkAll]:checked').each(function(){
                    storiesArray.push($(this).val())
                });
            } else {
                storiesArray = [];
            }
        });
        $(document).on('change', 'input[type=checkbox]', function() {
            if($("input[type=checkbox]").length == 1) return;
            if($("input[type=checkbox]:checked").length > 0)
            {
                $("#publishStoryButton").prop("disabled", false)
            } else {
                $("#publishStoryButton").prop("disabled", true)
            }
            if($(this).prop('checked') == true)
            {
                if($(this).val() != "on")
                {
                    storiesArray.push($(this).val())
                }
            } else if($(this).prop('checked') == false)
            {
                const removeFromArray = function (arr, ...theArgs) {
                    return arr.filter( val => !theArgs.includes(val) )
                };
                storiesArray = removeFromArray(storiesArray, $(this).val());
            }
        });

        $("#publishStoryForm").submit(function (e) {
            $("#selected_stories").val(JSON.stringify(storiesArray))
        })
    });
</script>
</body>
</html>
