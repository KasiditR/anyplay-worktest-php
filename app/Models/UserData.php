<?php
namespace App\Models;

use App\Services\Database;

class UserData
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM user_data");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($userId)
    {
        $query = $this->db->query("SELECT diamonds, hearts FROM user_data WHERE user_id = '{$userId}'");
        return $query->fetch_assoc();
    }

    public function create($userId)
    {
        $query = $this->db->query("INSERT INTO user_data (user_id) VALUES ('{$userId}')");
        return $query;
    }

    public function update($userId, $diamonds, $hearts)
    {
        $query = $this->db->query("UPDATE user_data SET diamonds = '{$diamonds}', hearts = '{$hearts}' WHERE user_id = '{$userId}'");
        return $query;
    }

    public function updateDiamond($userId, $diamonds)
    {
        $query = $this->db->query("UPDATE user_data SET diamonds = '{$diamonds}' WHERE user_id = '{$userId}'");
        return $query;
    }

    public function delete($userId)
    {
        $query = $this->db->query("DELETE FROM user_data WHERE user_id = '{$userId}'");
        return $query;
    }
}
?>