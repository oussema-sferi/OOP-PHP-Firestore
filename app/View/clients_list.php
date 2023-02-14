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
$database = new Firestore_honeydoo();
$userClients = $database->fetchUserClients($_SESSION["user"]["realtor_id"]);
/*var_dump($userClients[0]->doc_id);
die();*/

//
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Honeydoo" />
    <meta name="author" content="Honeydoo" />
    <title>Clients List</title>
    <link href="../Ressources/css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../Ressources/assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="nav-fixed">
<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" style="background-color: #262144!important" id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle" style="background-color: #00B2A0!important;"><i data-feather="menu"></i></button>
    <!-- Navbar Brand-->
    <!--<a class="navbar-brand pe-3 ps-4 ps-lg-2" href="#">Honeydoo</a>-->
    <img class="navbar-brand pe-3 ps-4 ps-lg-2" src="../Ressources/assets/img/HoneyDoo-logo.png">
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" src=<?= $profilePic ?> /></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src=<?=$profilePic?> />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?=$_SESSION["user"]["realtor_title"]?></div>
                        <div class="dropdown-user-details-email"><?=$_SESSION["user"]["email"]?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo 'my_profile.php' ?>">
                    <div class="dropdown-item-icon"><i data-feather="user"></i></div>
                    My Profile
                </a>
                <a class="dropdown-item" href="<?php echo 'logout.php' ?>">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sidenav shadow-right sidenav-light">
            <div class="sidenav-menu">
                <div class="nav accordion" id="accordionSidenav">
                    <!-- Sidenav Heading (Custom)-->
                    <div class="sidenav-menu-heading">Dashboard</div>
                    <!-- Sidenav Link (Blog Posts)-->
                    <a class="nav-link" href="<?='blog_posts.php'?>">
                        <div class="nav-link-icon"><i data-feather="list"></i></div>
                        My Stories
                    </a>
                    <!-- Sidenav Link (Pro Services)-->
                    <a class="nav-link" href="<?='pro_services.php'?>">
                        <div class="nav-link-icon"><i data-feather="list"></i></div>
                        My Pro Services
                    </a>
                    <!-- Sidenav Link (Client)-->
                    <a class="nav-link" href="<?='clients_list.php'?>">
                        <div class="nav-link-icon"><i data-feather="list"></i></div>
                        Clients List
                    </a>
                </div>
            </div>
        </nav>
    </div>
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
                                <th><input type='checkbox' class="checkAll"></th>
                                <th>Client name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Date Created</th>
                                <th>Date Email Invite Sent</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th><input type='checkbox' class="checkAll"></th>
                                <th>Client name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Date Created</th>
                                <th>Date Email Invite Sent</th>
                                <th class="text-center">Actions</th>
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
                                    $clientCollectionId = $clientCollection->doc_id;
                                    $showClientCollectionLink = "client_collection_show.php?client_collection_id=$clientCollectionId";
                                    $editClientCollectionLink = "client_collection_edit.php?client_collection_id=$clientCollectionId";
                                    $deleteClientCollectionLink = "../Controller/delete_client_collection_action.php?client_collection_id=$clientCollectionId";
                                    echo "
                                <tr>
                                    <td><input type='checkbox' value=$docId></td>
                                    <td>$clientName</td>
                                    <td>$address</td>
                                    <td>$city</td>
                                    <td>$state</td>
                                    <td>$createdAt</td>
                                    <td>$emailInviteSentDate</td>
                                                        
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
        <footer class="footer-admin mt-auto footer-light">
            <div class="container-xl px-4">
                <div class="row">
                    <div class="small text-center">Copyright &copy; Honeydoo</div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@5" type="text/javascript"></script>
<script src="../Ressources/js/datatables/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../Ressources/js/scripts.js"></script>
<script>
    $( document ).ready(function() {
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
        $("input[type=checkbox]").change(function () {
            /*console.log($(this))*/
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
        })
        $("#emailInvitaionForm").submit(function (e) {
            $("#selected_clients").val(JSON.stringify(clientsArray))
        })
    });
</script>
</body>
</html>
