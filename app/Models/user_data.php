<?php
namespace App\Models;

use App\Services\Database;

class UserData
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::connect();
    }

    public function get_all()
    {
        $query = $this->_db->query("SELECT * FROM user_data");
        return $query->fetch_all(MYSQLI_ASSOC);
    }

    public function get_by_id($userId)
    {
        $query = $this->_db->query("SELECT diamonds, hearts FROM user_data WHERE user_id = '{$userId}'");
        return $query->fetch_assoc();
    }

    public function create($userId)
    {
        $query = $this->_db->query("INSERT INTO user_data (user_id) VALUES ('{$userId}')");
        return $query;
    }

    public function update_diamond($userId, $diamonds)
    {
        $query = $this->_db->query("UPDATE user_data SET diamonds = '{$diamonds}' WHERE user_id = '{$userId}'");
        return $query;
    }

}
?>