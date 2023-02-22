<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use App\Service\UserChecker;
use App\Router;
use App\Handler\Contact;
use App\Controller\LoginController;
use App\Controller\RegistrationController;
use App\Controller\StoryController;

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
$mainPath = '/stories';
$router->get($mainPath . '/list', StoryController::class . '::list');


$router->post('/login', LoginController::class . '::loginAction');


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