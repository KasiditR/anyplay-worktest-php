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