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
        $data = getRequestData();
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
        $data = getRequestData();
        $this->user->update($id, $data['username'], $data['password']);
        echo json_encode(['message' => 'User updated successfully']);
    }

    public function deleteUserById($id)
    {
        $user = $this->user->getById($id);
        if (!$user) {
            sendErrorResponse('User not found.', 404);
        }

        $result = $this->user->delete($id);
        if ($result) {
            sendSuccessResponse('User deleted successfully.');
        } else {
            sendErrorResponse('User not found.', 500);
        }
    }

    public function addDiamondById($count)
    {
        $data = getRequestData();
        if (!isset($data['id'])) {
            sendErrorResponse('userId undefined.', 401);
            return;
        }

        $userData = $this->userData->getById($data['id']);
        if (!isset($userData)) {
            sendErrorResponse('User not found.', 404);
            return;
        }

        if (isset($userData["diamonds"])) {
            $userData["diamonds"] += $count;
            $this->userData->updateDiamond($data['id'], $userData["diamonds"]);
            sendSuccessResponse(
                'add diamond successfully.',
                [
                    'diamonds' => $userData["diamonds"]
                ]
            );
        } else {
            sendErrorResponse('add diamond failure.', 401);
        }
    }
}
?>