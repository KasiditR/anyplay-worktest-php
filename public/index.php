<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Services/Database.php';
require __DIR__ . '/../app/Models/User.php';
require __DIR__ . '/../app/Models/UserData.php';
require __DIR__ . '/../app/Models/LoginHistory.php';
require __DIR__ . '/../app/Controllers/UserController.php';
require __DIR__ . '/../app/Controllers/AuthenticationController.php';
require __DIR__ . '/../app/Helpers/function.php';

use App\Controllers\AuthenticationController;
use App\Controllers\UserController;
use Bramus\Router\Router;

// Create Router instance
$router = new Router();
$userController = new UserController();
$authenticationController = new AuthenticationController();

$router->post('/login', function () use ($authenticationController) {
    $authenticationController->login();
});

$router->post('/signup', function () use ($authenticationController) {
    $authenticationController->signUp();
});

$router->put('/updateDiamond', function () use ($userController) {
    $userController->addDiamondById(100);
});

// test

// $router->get('/users', function () use ($userController) {
//     $userController->getAllUsers();
// });

// $router->get('/user/(\w+)', function ($id) use ($userController) {
//     $userController->getUserById($id);
// });

// $router->post('/user', function () use ($userController) {
//     $userController->createUser();
// });

// $router->put('/user/(\w+)', function ($id) use ($userController) {
//     $userController->updateUser($id);
// });

// $router->delete('/user/(\w+)', function ($id) use ($userController) {
//     $userController->deleteUserById($id);
// });

// Run it!
$router->run();
?>