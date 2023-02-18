<?php
namespace App\View;
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;

require_once "../../../vendor/autoload.php";

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
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../layout/realtor/header.php';?>
    <title>Add Client</title>
</head>
<body class="nav-fixed">
<?php include '../layout/realtor/navbar.php';?>
<div id="layoutSidenav">
    <?php include '../layout/realtor/sidebar.php';?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white">
                <div class="container-fluid">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="list"></i></div>
                                    Create New Client
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='list.php'?>">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to All Clients
                                </a>
                            <div>

                        </div>
                    </div>
                </div>
            </header>
            <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-4">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="plus"></i></div>
                                    Add Client (Homeowner)
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-n10">
                <!-- Wizard card example with navigation-->
                <div class="card">
                    <div class="card-header border-bottom">
                        <!-- Progress bar navigation-->
                        <div id="progressbar1" class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="progressbar2" class="progress" style="display: none">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="progressbar3" class="progress" style="display: none">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="progressbar4" class="progress" style="display: none">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="cardTabContent">
                            <!-- Wizard Form Field sets-->
                            <div class="py-5 py-xl-10" id="wizard1">
                                <div class="row justify-content-center">
                                    <div class="col-xxl-6 col-xl-8">
                                        <form action="<?='../../Controller/add_client_collection_action.php'?>" method="post">
                                            <fieldset id="step1Form">
                                                <div class="row gx-3">
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h3 class="text-primary">Client 1</h3>

                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="firstName1">First name</label>
                                                            <input class="form-control" id="firstName1" type="text" placeholder="Enter your first name" name="firstName1"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="lastName1">Last name</label>
                                                            <input class="form-control" id="lastName1" type="text" placeholder="Enter your last name" name="lastName1"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="email1">Email</label>
                                                            <input class="form-control" id="email1" type="email" placeholder="Enter your email address" name="email1"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="phoneNumber1">Phone number</label>
                                                            <input class="form-control" id="phoneNumber1" type="tel" placeholder="Enter your phone number" name="phoneNumber1"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">

                                                </div>
                                                <hr class="my-4" />
                                                <div class="text-center">
                                                    <button class="btn btn-primary" type="button" id="step1button">Next</button>
                                                </div>
                                            </fieldset>

                                            <fieldset id="step2Form" style="display: none">
                                                <div class="row gx-3">
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h3 class="text-primary">Client 2</h3>

                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="firstName2">First name</label>
                                                            <input class="form-control" id="firstName2" type="text" placeholder="Enter your first name" name="firstName2"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="lastName2">Last name</label>
                                                            <input class="form-control" id="lastName2" type="text" placeholder="Enter your last name" name="lastName2"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="email2">Email</label>
                                                            <input class="form-control" id="email2" type="email" placeholder="Enter your email address" name="email2"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="phoneNumber2">Phone number</label>
                                                            <input class="form-control" id="phoneNumber2" type="tel" placeholder="Enter your phone number" name="phoneNumber2"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">

                                                </div>
                                                <hr class="my-4" />
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-light" type="button" id="step2prev">Previous</button>
                                                    <button class="btn btn-primary" type="button"  id="step2button">Next</button>
                                                </div>
                                            </fieldset>

                                            <fieldset id="step3Form" style="display: none">
                                                <div class="row gx-3">
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h3 class="text-primary">Home Detail</h3>

                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="address1">Address Line 1</label>
                                                            <input class="form-control" id="address1" type="text" placeholder="Address Line 1" name="address1"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="address2">Address Line 2</label>
                                                            <input class="form-control" id="address2" type="text" placeholder="Address Line 2" name="address2"/>
                                                        </div>
                                                        <div class="row gx-3">
                                                            <div class="mb-3 col-md-6">
                                                                <label class="small mb-1" for="city">City</label>
                                                                <input class="form-control" id="city" type="text" placeholder="City" name="city"/>
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label class="small mb-1" for="state">State</label>
                                                                <input class="form-control" id="state" placeholder="State" name="state"/>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="zipCode">Zip Code</label>
                                                            <input class="form-control" id="zipCode" placeholder="Zip Code" name="zipCode"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="homeType">Home Type</label>
                                                            <select class="form-control" id="homeType" name="homeType">
                                                                <option>Select Home Type</option>
                                                                <option value="Single Family Home">Single Family Home</option>
                                                                <option value="Townhome">Townhome</option>
                                                                <option value="Condo">Condo</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <hr class="my-4" />
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-light" type="button" id="step3prev">Previous</button>
                                                    <button class="btn btn-primary" type="button"  id="step3button">Next</button>
                                                </div>
                                            </fieldset>

                                            <fieldset id="step4Form" style="display: none">
                                                <div class="row gx-3">
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h3 class="text-primary">Personal Notes/Reminders</h3>
                                                        <div class="mb-3">
                                                            <textarea id="notes" class="lh-base form-control" type="text" name="notes" placeholder="Enter notes..." rows="4" style="resize: none"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">

                                                </div>
                                                <hr class="my-4" />
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-light" type="button" id="step4prev">Previous</button>
                                                    <button class="btn btn-primary" type="submit">Add Client</button>
                                                </div>
                                            </fieldset>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
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
        // Next Button
        // From Step 1 to Step 2
        $("#step1button").click(function () {
            $("#step1Form").hide()
            $("#progressbar1").hide()
            $("#step2Form").show()
            $("#progressbar2").show()
        })
        // From Step 2 to Step 3
        $("#step2button").click(function () {
            $("#step2Form").hide()
            $("#progressbar2").hide()
            $("#step3Form").show()
            $("#progressbar3").show()
        })
        // From Step 3 to Step 4
        $("#step3button").click(function () {
            $("#step3Form").hide()
            $("#progressbar3").hide()
            $("#step4Form").show()
            $("#progressbar4").show()
        })

        // Previous Button
        // From Step 1 to Step 2
        $("#step2prev").click(function () {
            $("#step2Form").hide()
            $("#progressbar2").hide()
            $("#step1Form").show()
            $("#progressbar1").show()
        })
        // From Step 2 to Step 3
        $("#step3prev").click(function () {
            $("#step3Form").hide()
            $("#progressbar3").hide()
            $("#step2Form").show()
            $("#progressbar2").show()
        })
        // From Step 3 to Step 4
        $("#step4prev").click(function () {
            $("#step4Form").hide()
            $("#progressbar4").hide()
            $("#step3Form").show()
            $("#progressbar3").show()
        })
    });
</script>
</body>
</html>
