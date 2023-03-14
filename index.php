<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use App\Service\UserCheckerService;
use App\Router;
use App\Handler\Contact;
use App\Controller\LoginController;
use App\Controller\RegistrationController;
use App\Controller\StoryController;
use App\Controller\ProServiceController;
use App\Controller\ClientController;

$router = new Router();

// LOGIN
$router->get('/login', LoginController::class . '::show');
$router->post('/login', LoginController::class . '::loginAction');

// LOGOUT
$router->get('/logout', LoginController::class . '::logoutAction');

// REGISTRATION
$router->get('/registration', RegistrationController::class . '::show');
$router->post('/registration', RegistrationController::class . '::registrationAction');


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
$router->get($proServicesBasePath . '/template-download', ProServiceController::class . '::downloadImportTemplate');
// import pro services via CSV
$router->post($proServicesBasePath . '/import', ProServiceController::class . '::importCsvAction');

// Realtor CLIENTS
$clientsBasePath = '/clients';
// list clients
$router->get($clientsBasePath . '/list', ClientController::class . '::listAction');
// create new client
$router->get($clientsBasePath . '/new', ClientController::class . '::addForm');
$router->post($clientsBasePath . '/new-save', ClientController::class . '::createAction');
// update client
$router->get($clientsBasePath . '/edit', ClientController::class . '::editForm');
$router->post($clientsBasePath . '/edit-save', ClientController::class . '::editSaveAction');
// show client
$router->get($clientsBasePath . '/show', ClientController::class . '::showAction');
// delete client
$router->get($clientsBasePath . '/delete', ClientController::class . '::deleteAction');
// client email invitations unsubscription action
$router->get($clientsBasePath . '/emails-unsubscription', ClientController::class . '::emailsUnsubscriptionAction');
// client unsubscription confirmation
$router->get($clientsBasePath . '/emails-unsubscription-confirmation', ClientController::class . '::emailsUnsubscriptionConfirmationAction');
// send email invitation to client
$router->post($clientsBasePath . '/send-invitation-to-client', ClientController::class . '::sendEmailInvitationToClientAction');

$router->get('/', function () {
    echo 'Home Page';
});

$router->get('/about', function (array $params = []) {
    echo 'About Page';
    if(!empty($params['username']))
    {
        echo '<h1> Hello ' . $params['username'] . '</h1>';
    }
});

$router->get('/contact', Contact::class . '::execute');

$router->post('/contact', function ($params) {
    var_dump($params);
});

$router->run();