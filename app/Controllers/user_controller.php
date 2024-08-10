<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserData;

class UserController
{
    private $_user;
    private $_user_data;

    public function __construct()
    {
        $this->_user = new User();
        $this->_user_data = new UserData();
    }

    public function get_all_users()
    {
        $users = $this->_user->get_all();
        $user_datas = $this->_user_data->get_all();

        $combined_datas = [];

        foreach ($users as $user) {
            $id = $user['id'];
            $user_data = array_filter($user_datas, function ($data) use ($id) {
                return $data['user_id'] == $id;
            });

            $user_data = reset($user_data);
            unset($user['password']);
            $combined_datas[] = array_merge(
                $user,
                [
                    'diamonds' => $user_data['diamonds'],
                    'hearts' => $user_data['hearts']
                ]
            );
        }

        $response = [
            'data' => array_values($combined_datas)
        ];

        send_success_response('Get users successfully.', $response);
    }

    public function get_user_by_id($userId)
    {
        $user = $this->_user->get_by_id($userId);
        $user_data = $this->_user_data->get_by_id($userId);

        send_success_response(
            'Get user successfully.',
            [
                'username' => $user['username'],
                'diamonds' => $user_data['diamonds'],
                'hearts' => $user_data['hearts']
            ]
        );
    }

    public function delete_user_by_id($id)
    {
        $user = $this->_user->get_by_id($id);
        if (!$user) {
            send_error_response('User not found.', 404);
        }

        $result = $this->_user->delete($id);
        if ($result) {
            send_success_response('User deleted successfully.');
        } else {
            send_error_response('User not found.', 500);
        }
    }

    public function add_diamond_by_id($amount)
    {
        $data = get_request_data();
        if (!isset($data['id'])) {
            send_error_response('userId undefined.', 401);
            return;
        }

        $userData = $this->_user_data->get_by_id($data['id']);
        if (!isset($userData)) {
            send_error_response('User not found.', 404);
            return;
        }

        if (isset($userData["diamonds"])) {
            $userData["diamonds"] += $amount;
            $this->_user_data->update_diamond($data['id'], $userData["diamonds"]);
            send_success_response(
                'add diamond successfully.',
                [
                    'diamonds' => $userData["diamonds"]
                ]
            );
        } else {
            send_error_response('add diamond failure.', 401);
        }
    }
}
?>