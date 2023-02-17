<?php
namespace App\View;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;
use Google\Cloud\Core\Timestamp;
use Monolog\DateTimeImmutable;


if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: users_list.php");
    }
}
$database = new Firestore_honeydoo();
$loggedUserProServices = $database->fetchProServices($_SESSION["user"]["realtor_id"]);

//
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layout/realtor/header.php';?>
    <title>My Pro Services</title>
</head>
<body class="nav-fixed">
<?php include 'layout/realtor/navbar.php';?>
<div id="layoutSidenav">
    <?php include 'layout/realtor/sidebar.php';?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-fluid px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="list"></i></div>
                                    All My Pro Services
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <div id="formContainer" style="display: none">
                                    <form action="<?='../Controller/import_pro_services_csv_action.php'?>" method="post" enctype="multipart/form-data">
                                        <input id="excelHomeProsfile" type="file" name="excelHomeProsfile" class="form-control-sm" style="width: 210px" accept=".csv,.ods, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                        <input class="btn btn-sm btn-success" type="submit" name="submit" value="Confirm">
                                        <a id="backButton" class="btn btn-sm btn-secondary">Back</a>
                                    </form>
                                </div>
                                <div id="buttonsContainer">
                                    <a class="btn btn-sm btn-light text-primary" href="<?='../Controller/template_download.php?template=pro_services'?>">
                                        <i class="me-1" data-feather="download"></i>
                                        Download Pro Services Template
                                    </a>
                                    <a class="btn btn-sm btn-light text-primary" href="<?='pro_service_add.php'?>">
                                        <i class="me-1" data-feather="plus"></i>
                                        Create New Pro Service
                                    </a>
                                    <a id="importProServicesButton" class="btn btn-sm btn-light text-primary">
                                        <i class="me-1" data-feather="plus"></i>
                                        Import Pro Services
                                    </a>
                                    <!--<a data-bs-toggle=tooltip data-bs-placement=bottom title='Download Template' href="<?/*='../Controller/template_download.php?template=pro_services'*/?>"><i class="fa-solid fa-download"></i></a>-->
                                </div>
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
                                <th>Company Name</th>
                                <th>Date Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Company Name</th>
                                <th>Date Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            foreach ($loggedUserProServices as $proService)
                            {
                                $companyName = $proService->company_name;
                                $createdAt = $loggedUserProServices[0]->date;
                                $createdAtFormatted = $createdAt->get()->format("m-d-Y");
                                $proServiceId = $proService->doc_id;
                                $showProServiceLink = "pro_service_show.php?pro_service_id=$proServiceId";
                                $editProServiceLink = "pro_service_edit.php?pro_service_id=$proServiceId";
                                $deleteProServiceLink = "../Controller/delete_pro_service_action.php?pro_service_id=$proServiceId";
                                echo "
                                <tr>
                                    <td>$companyName</td>
                                    <td>$createdAtFormatted</td>
                                    <td class='text-center'>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark me-2' href=$showProServiceLink><i data-feather='zoom-in'></i></a>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark me-2' href=$editProServiceLink><i data-feather='edit'></i></a>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark' data-bs-toggle='modal' data-bs-target='#approveUserModal$proServiceId'><i data-feather='trash-2'></i></a>                                                                                                                              
                                    </td>                                                                                          
                                </tr>
                                
                                <div class='modal fade' id='approveUserModal$proServiceId' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                                    <div class='modal-dialog modal-dialog-centered' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header d-block'>
                                                <button class='btn-close float-end' type='button' data-bs-dismiss='modal' aria-label='Close'></button>
                                                <h5 class='modal-title text-center' id='exampleModalCenterTitle'>Removal Confirmation</h5>
                                            </div>
                                            <div class='modal-body text-center'>
                                                Do you really want to delete this pro service ?
                                            </div>
                                            <div class='modal-footer justify-content-center'>
                                                <a class='btn btn-secondary' type='button' data-bs-dismiss='modal'>No</a>
                                                <a class='btn btn-success' type='button' href='$deleteProServiceLink'>Yes</a>
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
        <?php include 'layout/realtor/footer.php';?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@5" type="text/javascript"></script>
<script src="../Ressources/js/datatables/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../Ressources/js/scripts.js"></script>
<script>
    $( document ).ready(function() {
        $("#importProServicesButton").click(function () {
            $("#buttonsContainer").hide()
            $("#formContainer").show()
        })
        $("#backButton").click(function () {
            $("#formContainer").hide()
            $("#buttonsContainer").show()
        })
    });
</script>
</body>
</html>
