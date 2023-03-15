<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use App\Controller\DashboardController;
use App\Service\UserCheckerService;
use App\Router;
use App\Controller\LoginController;
use App\Controller\RegistrationController;
use App\Controller\Realtor\StoryController;
use App\Controller\Realtor\ProServiceController;
use App\Controller\Realtor\PortalClientController;
use App\Controller\Admin\PortalClientController as AdminPortalClientController;
use App\Controller\Admin\RealtorController as AdminRealtorController;
use App\Controller\Admin\MobileAppClientController as AdminMobileAppClientController;
use App\Controller\Admin\EmailController as AdminEmailController;
use App\Controller\ResetPasswordController;

$router = new Router();

// LOGIN
$router->get('/login', LoginController::class . '::show');
$router->post('/login', LoginController::class . '::loginAction');

// LOGOUT
$router->get('/logout', LoginController::class . '::logoutAction');

// REGISTRATION
$router->get('/registration', RegistrationController::class . '::show');
$router->post('/registration', RegistrationController::class . '::registrationAction');

// RESET PASSWORD STEPS
$resetPasswordBasePath = '/reset-password';
// STEP 1 Enter email & send password request
$router->get($resetPasswordBasePath . '/request', ResetPasswordController::class . '::showRequestPasswordAction');
$router->post($resetPasswordBasePath . '/request', ResetPasswordController::class . '::requestPasswordAction');
// STEP 2 check email
$router->get($resetPasswordBasePath . '/check-email', ResetPasswordController::class . '::checkEmailAction');
// STEP 3 token validation
$router->get($resetPasswordBasePath . '/validate-token', ResetPasswordController::class . '::validateTokenAction');
// STEP 4 change password
$router->get($resetPasswordBasePath . '/reset', ResetPasswordController::class . '::showChangePasswordAction');
$router->post($resetPasswordBasePath . '/reset', ResetPasswordController::class . '::changePasswordAction');
// STEP 5 Change password confirmation
$router->get($resetPasswordBasePath . '/confirmation', ResetPasswordController::class . '::changePasswordConfirmationAction');

// My Profile page
$router->get('/dashboard/my-profile', DashboardController::class . '::myProfileShowAction');

// Update My profile picture
$router->post('/dashboard/update-profile-picture', DashboardController::class . '::updateMyProfilePictureAction');

// Edit My profile action
$router->post('/dashboard/edit-profile', DashboardController::class . '::editMyProfileAction');

// Change my password
// check current password page
$router->get('/dashboard/password-verification', DashboardController::class . '::showCheckPasswordAction');
// check current password action
$router->post('/dashboard/password-verification', DashboardController::class . '::checkPasswordAction');
// change password page
$router->get('/dashboard/change-password', DashboardController::class . '::showChangePasswordAction');
// change password action
$router->post('/dashboard/change-password', DashboardController::class . '::changePasswordAction');

// Realtor STORIES
$storiesBasePath = '/stories';
// list stories
$router->get($storiesBasePath . '/list', StoryController::class . '::listAction');
// create new story
$router->get($storiesBasePath . '/new', StoryController::class . '::addForm');
$router->post($storiesBasePath . '/new-save', StoryController::class . '::createAction');
// update story
$router->get($storiesBasePath . '/edit', StoryController::class . '::editForm');
$router->post($storiesBasePath . '/edit-save', StoryController::class . '::editSaveAction');
// show story
$router->get($storiesBasePath . '/show', StoryController::class . '::showAction');
// delete story
$router->get($storiesBasePath . '/delete', StoryController::class . '::deleteAction');
// publish story
$router->post($storiesBasePath . '/publish', StoryController::class . '::publishStoryAction');


// Realtor PRO SERVICES
$proServicesBasePath = '/pro-services';
// list pro services
$router->get($proServicesBasePath . '/list', ProServiceController::class . '::listAction');
// create new pro service
$router->get($proServicesBasePath . '/new', ProServiceController::class . '::addForm');
$router->post($proServicesBasePath . '/new-save', ProServiceController::class . '::createAction');
// update pro service
$router->get($proServicesBasePath . '/edit', ProServiceController::class . '::editForm');
$router->post($proServicesBasePath . '/edit-save', ProServiceController::class . '::editSaveAction');
// show pro service
$router->get($proServicesBasePath . '/show', ProServiceController::class . '::showAction');
// delete pro service
$router->get($proServicesBasePath . '/delete', ProServiceController::class . '::deleteAction');
// download import pro services template
$router->get($proServicesBasePath . '/template-download', ProServiceController::class . '::templateDownloadAction');
// import pro services via CSV
$router->post($proServicesBasePath . '/import', ProServiceController::class . '::importFromFileAction');

// Realtor CLIENTS
$clientsBasePath = '/clients';
// list clients
$router->get($clientsBasePath . '/list', PortalClientController::class . '::listAction');
// create new client
$router->get($clientsBasePath . '/new', PortalClientController::class . '::addForm');
$router->post($clientsBasePath . '/new-save', PortalClientController::class . '::createAction');
// update client
$router->get($clientsBasePath . '/edit', PortalClientController::class . '::editForm');
$router->post($clientsBasePath . '/edit-save', PortalClientController::class . '::editSaveAction');
// show client
$router->get($clientsBasePath . '/show', PortalClientController::class . '::showAction');
// delete client
$router->get($clientsBasePath . '/delete', PortalClientController::class . '::deleteAction');
// client email invitations unsubscription action
$router->get($clientsBasePath . '/emails-unsubscription', PortalClientController::class . '::emailsUnsubscriptionAction');
// client unsubscription confirmation
$router->get($clientsBasePath . '/emails-unsubscription-confirmation', PortalClientController::class . '::emailsUnsubscriptionConfirmationAction');
// send email invitation to client
$router->post($clientsBasePath . '/send-invitation-to-client', PortalClientController::class . '::sendEmailInvitationToClientAction');
// CSV template download
$router->get($clientsBasePath . '/template-download', PortalClientController::class . '::templateDownloadAction');
// clients import from CSV action
$router->post($clientsBasePath . '/import-from-file', PortalClientController::class . '::importFromFileAction');


// ADMIN ROUTES
$adminBasePath = '/admin';
// REALTORS MENU
// list realtors
$realtorsBasePath = $adminBasePath . '/realtors';
$router->get($realtorsBasePath . '/list', AdminRealtorController::class . '::listAction');
// create new realtor
$router->get($realtorsBasePath . '/new', AdminRealtorController::class . '::addForm');
$router->post($realtorsBasePath . '/new', AdminRealtorController::class . '::createAction');
// update realtor
$router->get($realtorsBasePath . '/edit', AdminRealtorController::class . '::editForm');
$router->post($realtorsBasePath . '/edit', AdminRealtorController::class . '::editSaveAction');
// show realtor
$router->get($realtorsBasePath . '/show', AdminRealtorController::class . '::showAction');
// delete realtor
$router->get($realtorsBasePath . '/delete', AdminRealtorController::class . '::deleteAction');

// PORTAL CLIENTS MENU
// list clients
$portalClientsBasePath = $adminBasePath . '/portal-clients';
$router->get($portalClientsBasePath . '/list', AdminPortalClientController::class . '::listAction');
// edit client
$router->get($portalClientsBasePath . '/edit', AdminPortalClientController::class . '::editForm');
$router->post($portalClientsBasePath . '/edit', AdminPortalClientController::class . '::editSaveAction');
// show client
$router->get($portalClientsBasePath . '/show', AdminPortalClientController::class . '::showAction');
// delete client
$router->get($portalClientsBasePath . '/delete', AdminPortalClientController::class . '::deleteAction');

// MOBILE APP CLIENTS MENU
// list clients
$mobileAppClientsBasePath = $adminBasePath . '/mobile-app-clients';
$router->get($mobileAppClientsBasePath . '/list', AdminMobileAppClientController::class . '::listAction');
// edit client
$router->get($mobileAppClientsBasePath . '/edit', AdminMobileAppClientController::class . '::editForm');
$router->post($mobileAppClientsBasePath . '/edit', AdminMobileAppClientController::class . '::editSaveAction');
// show client
$router->get($mobileAppClientsBasePath . '/show', AdminMobileAppClientController::class . '::showAction');
// delete client
$router->get($mobileAppClientsBasePath . '/delete', AdminMobileAppClientController::class . '::deleteAction');

// EMAILS MENU
$emailsBasePath = $adminBasePath . '/emails';
// Invitation email
$router->get($emailsBasePath . '/invitation', AdminEmailController::class . '::invitationEmailAction');
$router->post($emailsBasePath . '/invitation', AdminEmailController::class . '::saveInvitationEmailAction');
// Invitation email
$router->get($emailsBasePath . '/reset-password', AdminEmailController::class . '::resetPasswordEmailAction');
$router->post($emailsBasePath . '/reset-password', AdminEmailController::class . '::saveResetPasswordEmailAction');


$router->get('/', function () {
    echo 'Home Page';
});

/*$router->post('/contact', function ($params) {
    var_dump($params);
});*/
$router->run();