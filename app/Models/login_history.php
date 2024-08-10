<?php
namespace App\Models;

use App\Services\Database;

class LoginHistory
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::connect();
    }

    public function create($userId, $time)
    {
        $query = $this->_db->query("INSERT INTO login_history (user_id, login_time) VALUES ('{$userId}', '{$time}')");
        return $query;
    }
}
?>