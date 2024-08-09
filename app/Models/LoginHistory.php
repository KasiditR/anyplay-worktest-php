<?php
namespace App\Models;

use App\Services\Database;

class LoginHistory
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM login_history");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $query = $this->db->query("SELECT id, username FROM login_history WHERE id = '{$id}'");
        return $query->fetch_assoc();
    }

    public function create($userId, $time)
    {
        $query = $this->db->query("INSERT INTO login_history (user_id, login_time) VALUES ('{$userId}', '{$time}')");
        return $query;
    }
}
?>