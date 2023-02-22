<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use App\Service\UserChecker;
use App\Router;
use App\Handler\Contact;
use App\Controller\LoginController;
use App\Controller\RegistrationController;
use App\Controller\StoryController;
use App\Controller\ProServiceController;

$router = new Router();

// LOGIN
$router->get('/login', LoginController::class . '::show');
$router->post('/login', LoginController::class . '::loginAction');

// LOGOUT
$router->get('/logout', LoginController::class . '::logoutAction');

// REGISTRATION
$router->get('/registration', RegistrationController::class . '::show');
$router->post('/registration', RegistrationController::class . '::registrationAction');


// STORIES
$storiesBasePath = '/stories';
// list stories
$router->get($storiesBasePath . '/list', StoryController::class . '::listAction');
// create story
$router->get($storiesBasePath . '/new', StoryController::class . '::addForm');
$router->post($storiesBasePath . '/new-save', StoryController::class . '::createAction');
// update story
$router->get($storiesBasePath . '/edit', StoryController::class . '::editForm');
$router->post($storiesBasePath . '/edit-save', StoryController::class . '::editSaveAction');
// show story
$router->get($storiesBasePath . '/show', StoryController::class . '::showAction');
// delete story
$router->get($storiesBasePath . '/delete', StoryController::class . '::deleteAction');


// PRO SERVICES
$proServicesBasePath = '/pro-services';
// list pro services
$router->get($proServicesBasePath . '/list', ProServiceController::class . '::listAction');
// create pro service
$router->get($proServicesBasePath . '/new', ProServiceController::class . '::addForm');
$router->post($proServicesBasePath . '/new-save', ProServiceController::class . '::createAction');
// update pro service
$router->get($proServicesBasePath . '/edit', ProServiceController::class . '::editForm');
$router->post($proServicesBasePath . '/edit-save', ProServiceController::class . '::editSaveAction');
// show pro service
$router->get($proServicesBasePath . '/show', ProServiceController::class . '::showAction');
// delete pro service
$router->get($proServicesBasePath . '/delete', ProServiceController::class . '::deleteAction');
// import pro services via CSV
$router->post($proServicesBasePath . '/import', ProServiceController::class . '::importCsvAction');

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