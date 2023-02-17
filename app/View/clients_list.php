<?php
namespace App\View;
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;

require_once "../../vendor/autoload.php";

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: users_list.php");
    }
}
$loggedRealtorId = $_SESSION["user"]["realtor_id"];
$database = new Firestore_honeydoo();
$helper = new HelperService();
$realtor = $database->fetchRealtorById($loggedRealtorId);
$profilePic = $helper->setProfilePic($realtor);
$userClients = $database->fetchUserClients($loggedRealtorId);
$allMobileAppClients = $database->fetchAllMobileAppClients();
$helper->clientCheckAndSaveSignUpDate($userClients, $allMobileAppClients, $database);
$userClients = $database->fetchUserClients($loggedRealtorId);
//
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layout/realtor/header.php';?>
    <title>Clients List</title>
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
                                    All Clients
                                </h1>
                            </div>
                            <div class="col mb-3">
                                <form id="emailInvitaionForm" action="<?='../Controller/send_email_invitation_action.php'?>" method="post">
                                    <input type="hidden" id="selected_clients" name="selectedClients">
                                    <button type="submit" id="sendInviteButton" class="btn btn-sm btn-light text-primary" href="#" disabled>
                                        <i class="me-1" data-feather="send"></i>
                                        Send invitation to download App
                                    </button>
                                </form>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <div id="formContainer" style="display: none">
                                    <form action="<?='../Controller/import_clients_csv_action.php'?>" method="post" enctype="multipart/form-data">
                                        <input id="excelcontactsfile" type="file" name="excelclientsfile" class="form-control-sm" style="width: 210px" accept=".csv,.ods, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                        <input class="btn btn-sm btn-success" type="submit" name="submit" value="Confirm">
                                        <a id="backButton" class="btn btn-sm btn-secondary">Back</a>
                                    </form>
                                </div>
                                <div id="buttonsContainer">
                                    <a class="btn btn-sm btn-light text-primary" href="<?='../Controller/template_download.php?template=clients'?>">
                                        <i class="me-1" data-feather="download"></i>
                                        Download Client Template
                                    </a>
                                    <a class="btn btn-sm btn-light text-primary" href="<?='client_add.php'?>">
                                        <i class="me-1" data-feather="plus"></i>
                                        Create New Client
                                    </a>
                                    <a id="importClientButton" class="btn btn-sm btn-light text-primary">
                                        <i class="me-1" data-feather="plus"></i>
                                        Import Clients
                                    </a>
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
                                <th class='text-center' style='vertical-align: middle'><input type='checkbox' class="checkAll"></th>
                                <th class='text-center' style='vertical-align: middle'>Client name<i class="fas fa-sort float-end mt-1"></i></th>
                                <th class='text-center' style='vertical-align: middle'>Address</th>
                                <th class='text-center' style='vertical-align: middle'>City</th>
                                <th class='text-center' style='vertical-align: middle'>State</th>
                                <th class='text-center' style='vertical-align: middle'>Created At</th>
                                <th class='text-center' style='max-width: 70px; vertical-align: middle'>Email Invite Sent At<i class="fas fa-sort float-end mt-1"></i></th>
                                <th class='text-center' style='max-width: 70px; vertical-align: middle'>Client Signed-up At<i class="fas fa-sort float-end mt-1"></i></th>
                                <th class="text-center" style='vertical-align: middle'>Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th><input type='checkbox' class="checkAll"></th>
                                <th>Client name<i class="fas fa-sort float-end mt-1"></i></th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Date Created</th>
                                <th>Date Email Invite Sent</th>
                                <th>Date Client Signed-up</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            foreach ($userClients as $clientCollection)
                                {
                                    $docId = $clientCollection->doc_id;
                                    $clientName = $clientCollection->first_name_1 . " " . $clientCollection->last_name_1;
                                    $address = $clientCollection->address_1;
                                    $city = $clientCollection->city;
                                    $state = $clientCollection->state;
                                    if(isset($clientCollection->created_at)) {
                                        $createdAt = $clientCollection->created_at->get()->format("m-d-Y");
                                    } else {
                                        $createdAt = "";
                                    }
                                    if(isset($clientCollection->email_invite_sent_at)) {
                                        $emailInviteSentDate = $clientCollection->email_invite_sent_at->get()->format("m-d-Y");
                                    } else {
                                        $emailInviteSentDate = "";
                                    }

                                    if(isset($clientCollection->mobile_app_signed_up_at) && trim($clientCollection->mobile_app_signed_up_at) !== "") {
                                        $mobileAppSignedUpDate = $clientCollection->mobile_app_signed_up_at->get()->format("m-d-Y");
                                    } else {
                                        $mobileAppSignedUpDate = "";
                                    }
                                    $clientCollectionId = $clientCollection->doc_id;
                                    $showClientCollectionLink = "client_collection_show.php?client_collection_id=$clientCollectionId";
                                    $editClientCollectionLink = "client_collection_edit.php?client_collection_id=$clientCollectionId";
                                    $deleteClientCollectionLink = "../Controller/delete_client_collection_action.php?client_collection_id=$clientCollectionId";
                                    echo "
                                <tr>
                                    <td class='text-center' style='vertical-align: middle'><input type='checkbox' value=$docId></td>
                                    <td class='text-center' style='min-width: 140px; vertical-align: middle'>$clientName</td>
                                    <td class='text-center' style='max-width: 250px; vertical-align: middle'>$address</td>
                                    <td class='text-center' style='vertical-align: middle'>$city</td>
                                    <td class='text-center' style='vertical-align: middle'>$state</td>
                                    <td class='text-center' style='min-width: 70px; vertical-align: middle'>$createdAt</td>
                                    <td class='text-center' style='vertical-align: middle'>$emailInviteSentDate</td>
                                    <td class='text-center' style='vertical-align: middle'>$mobileAppSignedUpDate</td>
                                                        
                                    <td class='text-center'>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark me-2' href=$showClientCollectionLink><i data-feather='zoom-in'></i></a>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark me-2' href=$editClientCollectionLink><i data-feather='edit'></i></a>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark' data-bs-toggle='modal' data-bs-target='#approveUserModal$clientCollectionId'><i data-feather='trash-2'></i></a>                                                                                                                              
                                    </td>
                                </tr>
                                
                                <div class='modal fade' id='approveUserModal$clientCollectionId' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
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
                                                <a class='btn btn-success' type='button' href='$deleteClientCollectionLink'>Yes</a>
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
<!--<script src="../Ressources/js/datatables/datatables-simple-demo.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../Ressources/js/scripts.js"></script>
<script>
    $( document ).ready(function() {
        const datatablesSimple = document.getElementById('datatablesSimple');
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple, {
                /*sortable: false,*/
                columns: [
                    { select: [1,6,7], sortable: true },
                    { select: [0,2,3,4,5,8], sortable: false },
                ]
            });
        }
        let clientsArray = [];
        $("#importClientButton").click(function () {
            $("#buttonsContainer").hide()
            $("#formContainer").show()
        })
        $("#backButton").click(function () {
            $("#formContainer").hide()
            $("#buttonsContainer").show()

        })
        $(".checkAll").click(function () {
            $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            if($(this).prop('checked') == true)
            {
                $('input[type=checkbox][class!=checkAll]:checked').each(function(){
                    clientsArray.push($(this).val())
                });
            } else {
                clientsArray = [];
            }
        });
        $(document).on('change', 'input[type=checkbox]', function() {
            /*$('th, td').css({"text-align": "center", "vertical-align": "middle"})*/
            if($("input[type=checkbox]").length == 1) return;
            if($("input[type=checkbox]:checked").length > 0)
            {
                $("#sendInviteButton").prop("disabled", false)
            } else {
                $("#sendInviteButton").prop("disabled", true)
            }
            if($(this).prop('checked') == true)
            {
                if($(this).val() != "on")
                {
                    clientsArray.push($(this).val())
                }
            } else if($(this).prop('checked') == false)
            {
                const removeFromArray = function (arr, ...theArgs) {
                    return arr.filter( val => !theArgs.includes(val) )
                };
                clientsArray = removeFromArray(clientsArray, $(this).val());
            }
        });

        $("#emailInvitaionForm").submit(function (e) {
            $("#selected_clients").val(JSON.stringify(clientsArray))
        })
    });
</script>
</body>
</html>
