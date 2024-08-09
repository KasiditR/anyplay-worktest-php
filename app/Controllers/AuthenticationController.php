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
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Username and password are required.']);
            return;
        }

        $username = $data['username'];
        $password = $data['password'];

        $user = $this->user->findByUsername($username);

        if ($user) {
            if ($password == $user['password']) {
                $userData = $this->userData->getById($user['id']);
                $this->loginHistory->create($user['id'], date('Y-m-d H:i:s'));
                echo json_encode([
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'diamonds' => $userData['diamonds'],
                        'hearts' => $userData['hearts']
                    ]
                ]);
            } else {
                http_response_code(401);
                echo json_encode(['message' => 'Invalid username or password.']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid username or password.']);
        }
    }

    public function signUp()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['username']) || !isset($data['password']) || !isset($data['confirmPassword'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Username and password are required.']);
            return;
        }
        
        if ($data['password'] != $data['confirmPassword']) {
            http_response_code(401);
            echo json_encode(['message' => 'Passwords do not match. Please try again.']);
            return;
        }
        
        if ($this->user->usernameExists($data['username'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Username already exists.']);
            return;
        }

        $userId = md5(microtime(true) . rand());
        $isCreate = $this->user->create($userId, $data['username'], $data['password']);
        if ($isCreate) {
            $this->userData->create($userId);
        }

        echo json_encode(
            [
                'message' => 'Sign Up successfully.',
            ]
        );
    }
}
?>