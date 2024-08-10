<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Services/database.php';
require __DIR__ . '/../app/Models/user.php';
require __DIR__ . '/../app/Models/user_data.php';
require __DIR__ . '/../app/Models/login_history.php';
require __DIR__ . '/../app/Controllers/user_controller.php';
require __DIR__ . '/../app/Controllers/authentication_controller.php';
require __DIR__ . '/../app/Helpers/function.php';

use App\Controllers\AuthenticationController;
use App\Controllers\UserController;
use Bramus\Router\Router;

$router = new Router();
$user_controller = new UserController();
$authentication_controller = new AuthenticationController();

$router->post('/login', function () use ($authentication_controller) {
    $authentication_controller->login();
});

$router->post('/signup', function () use ($authentication_controller) {
    $authentication_controller->signup();
});

$router->put('/updateDiamond', function () use ($user_controller) {
    $user_controller->add_diamond_by_id(100);
});

$router->run();
?>