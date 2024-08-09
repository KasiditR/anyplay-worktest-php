<?php
namespace App\Controllers;

use App\Models\LoginHistory;
use App\Models\User;
use App\Models\UserData;

class AuthenticationController
{
    private $user;
    private $userData;
    private $loginHistory;

    public function __construct()
    {
        $this->user = new User();
        $this->userData = new UserData();
        $this->loginHistory = new LoginHistory();
    }

    public function login()
    {
        $data = getRequestData();

        if (!isset($data['username']) || !isset($data['password'])) {
            sendErrorResponse('Username and password are required.', 400);
            return;
        }

        $user = $this->user->findByUsername($data['username']);

        if ($user) {
            if (verifyPassword($data['password'], $user['password'])) {
                $userData = $this->userData->getById($user['id']);
                $this->loginHistory->create($user['id'], date('Y-m-d H:i:s'));
                sendSuccessResponse('Login successful', [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'diamonds' => $userData['diamonds'],
                    'hearts' => $userData['hearts']
                ]);
            } else {
                sendErrorResponse('Invalid username or password.', 401);
            }
        } else {
            sendErrorResponse('Invalid username or password.', 401);
        }
    }

    public function signUp()
    {
        $data = getRequestData();

        if (!isset($data['username']) || !isset($data['password']) || !isset($data['confirmPassword'])) {
            sendErrorResponse('Username and password are required.', 400);
            return;
        }

        if ($data['password'] != $data['confirmPassword']) {
            sendErrorResponse('Passwords do not match. Please try again.', 401);
            return;
        }

        if ($this->user->usernameExists($data['username'])) {
            sendErrorResponse('Username already exists.', 401);
            return;
        }

        $userId = md5(microtime(true) . rand());
        $hashedPassword = hashPassword($data['password']);
        $isCreate = $this->user->create($userId, $data['username'], $hashedPassword);
        if ($isCreate) {
            $this->userData->create($userId);
            sendSuccessResponse('Sign Up successfully.');
        } else {
            sendErrorResponse('Failed to create user.', 500);
        }

    }
}
?>