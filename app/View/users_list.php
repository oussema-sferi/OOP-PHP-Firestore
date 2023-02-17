<?php
namespace App\View\admin;
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;

require_once "../../vendor/autoload.php";

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
}
if(isset($_SESSION["user"]["role"]) && ($_SESSION["user"]["role"] != "ROLE_ADMIN"))
{
    header("Location: blog_posts.php");
}
$database = new Firestore_honeydoo();
$users = $database->fetchUsers();
//
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layout/admin/header.php';?>
    <title>Users List</title>
</head>
<body class="nav-fixed">
<?php include 'layout/admin/navbar.php';?>
<div id="layoutSidenav">
    <?php include 'layout/admin/sidebar.php';?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-fluid px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="list"></i></div>
                                    All Users
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
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
                                <th>Email</th>
                                <th>Company Name</th>
                                <th>Phone Number</th>
                                <!--<th class="text-center">Actions</th>-->
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Email</th>
                                <th>Company Name</th>
                                <th>Phone Number</th>
                                <!--<th class="text-center">Actions</th>-->
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            foreach ($users as $user)
                                {
                                    $email = $user->email;
                                    $companyName = $user->realtor_sub_title;
                                    $phoneNumber = $user->phone_number;
                                    $userId = $user->doc_id;
                                    $showUserLink = "client_collection_show.php?client_collection_id=$userId";
                                    $editUserLink = "client_collection_edit.php?client_collection_id=$userId";
                                    $deleteUserLink = "../Controller/delete_client_collection_action.php?client_collection_id=$userId";
                                    echo "
                                <tr>                              
                                    <td>$email</td>
                                    <td>$companyName</td>
                                    <td>$phoneNumber</td>                                                                                                                                
                                </tr>
                                
                                <div class='modal fade' id='approveUserModal$userId' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                                    <div class='modal-dialog modal-dialog-centered' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header d-block'>
                                                <button class='btn-close float-end' type='button' data-bs-dismiss='modal' aria-label='Close'></button>
                                                <h5 class='modal-title text-center' id='exampleModalCenterTitle'>Removal Confirmation</h5>
                                            </div>
                                            <div class='modal-body text-center'>
                                                Do you really want to delete this client ?
                                            </div>
                                            <div class='modal-footer justify-content-center'>
                                                <a class='btn btn-secondary' type='button' data-bs-dismiss='modal'>No</a>
                                                <a class='btn btn-success' type='button' href='$deleteUserLink'>Yes</a>
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
        <?php include 'layout/admin/footer.php';?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@5" type="text/javascript"></script>
<script src="../Ressources/js/datatables/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../Ressources/js/scripts.js"></script>
<script>
    $( document ).ready(function() {
        /*$("#importClientButton").click(function () {
            $("#buttonsContainer").hide()
            $("#formContainer").show()
        })
        $("#backButton").click(function () {
            $("#formContainer").hide()
            $("#buttonsContainer").show()

        })
        $(".checkAll").click(function () {
            $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
        });
        $("input[type=checkbox]").change(function () {
            if($("input[type=checkbox]:checked").length > 0)
            {
                $("#sendInviteButton").prop("disabled", false)
            } else {
                $("#sendInviteButton").prop("disabled", true)
            }
        })*/
    });
</script>
</body>
</html>
