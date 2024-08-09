<?php
namespace App\Controllers;

use App\Models\LoginHistory;
use App\Models\User;
use App\Models\UserData;

class AuthenticationController
{
    private $_user;
    private $_userData;
    private $_loginHistory;

    public function __construct()
    {
        $this->_user = new User();
        $this->_userData = new UserData();
        $this->_loginHistory = new LoginHistory();
    }

    public function login()
    {
        $data = get_request_data();

        if (!isset($data['username']) || !isset($data['password'])) {
            send_error_response('Username and password are required.', 400);
            return;
        }

        $user = $this->_user->find_by_username($data['username']);

        if ($user) {
            if (verify_password($data['password'], $user['password'])) {
                $userData = $this->_userData->get_by_id($user['id']);
                $this->_loginHistory->create($user['id'], date('Y-m-d H:i:s'));
                send_success_response('Login successful', [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'diamonds' => $userData['diamonds'],
                    'hearts' => $userData['hearts']
                ]);
            } else {
                send_error_response('Invalid username or password.', 401);
            }
        } else {
            send_error_response('Invalid username or password.', 401);
        }
    }

    public function signup()
    {
        $data = get_request_data();

        if (!isset($data['username']) || !isset($data['password']) || !isset($data['confirmPassword'])) {
            send_error_response('Username and password are required.', 400);
            return;
        }

        if ($data['password'] != $data['confirmPassword']) {
            send_error_response('Passwords do not match. Please try again.', 401);
            return;
        }

        if ($this->_user->username_exists($data['username'])) {
            send_error_response('Username already exists.', 401);
            return;
        }

        $userId = md5(microtime(true) . rand());
        $hashedPassword = hash_password($data['password']);
        $isCreate = $this->_user->create($userId, $data['username'], $hashedPassword);
        if ($isCreate) {
            $this->_userData->create($userId);
            send_success_response('Sign Up successfully.');
        } else {
            send_error_response('Failed to create user.', 500);
        }

    }
}
?>