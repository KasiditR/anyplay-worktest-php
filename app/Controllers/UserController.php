<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserData;

class UserController
{
    private $user;
    private $userData;

    public function __construct()
    {
        $this->user = new User();
        $this->userData = new UserData();
    }

    public function getAllUsers()
    {
        $users = $this->user->getAll();
        $userDatas = $this->userData->getAll();

        $combinedData = [];

        foreach ($users as $user) {
            $userId = $user['id'];
            $userData = array_filter($userDatas, function ($data) use ($userId) {
                return $data['user_id'] == $userId;
            });

            $userData = reset($userData);

            $combinedData[] = array_merge(
                $user,
                [
                    'diamonds' => $userData['diamonds'],
                    'hearts' => $userData['hearts']
                ]
            );
        }

        echo json_encode($combinedData);
    }

    public function getUserById($userId)
    {
        $user = $this->user->getById($userId);
        $userData = $this->userData->getById($userId);
        echo json_encode(
            [
                'username' => $user['username'],
                'diamonds' => $userData['diamonds'],
                'hearts' => $userData['hearts']
            ]
        );
    }

    public function createUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($this->user->usernameExists($data['username'])) {
            echo json_encode(['message' => 'Username already exists']);
        } else {
            $userId = md5(microtime(true) . rand());
            $isCreate = $this->user->create($userId, $data['username'], $data['password']);
            if ($isCreate) {
                $this->userData->create($userId);
            }
            echo json_encode(
                [
                    'message' => 'User created successfully',
                    'userId' => $userId,
                    'username' => $data['username'],
                ]
            );
        }
    }

    public function updateUser($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->user->update($id, $data['username'], $data['password']);
        echo json_encode(['message' => 'User updated successfully']);
    }

    public function deleteUserById($id)
    {
        $this->user->delete($id);
        echo json_encode(['message' => 'User deleted successfully']);
    }

    public function addDiamondById($count)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id'])) {
            echo json_encode(
                [
                    'message' => 'userId undefined',
                ]
            );
            return;
        }

        $userData = $this->userData->getById($data['id']);
        if (!isset($userData)) {
            echo json_encode(
                [
                    'message' => 'User not found',
                ]
            );
            return;
        }

        if (isset($userData["diamonds"])) {
            $userData["diamonds"] += $count;
            $this->userData->updateDiamond($data['id'], $userData["diamonds"]);
        }

        echo json_encode(
            [
                'message' => 'User updated diamond successfully',
                'diamonds' => $userData["diamonds"]
            ]
        );
    }
}
?>