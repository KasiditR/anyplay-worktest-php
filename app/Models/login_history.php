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

    public function get_all()
    {
        $query = $this->_db->query("SELECT * FROM login_history");
        return $query->fetch_all(MYSQLI_ASSOC);
    }

    public function get_by_id($id)
    {
        $query = $this->_db->query("SELECT id, username FROM login_history WHERE id = '{$id}'");
        return $query->fetch_assoc();
    }

    public function create($userId, $time)
    {
        $query = $this->_db->query("INSERT INTO login_history (user_id, login_time) VALUES ('{$userId}', '{$time}')");
        return $query;
    }
}
?>