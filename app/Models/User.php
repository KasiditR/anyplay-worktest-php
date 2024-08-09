<?php
namespace App\Models;

use App\Services\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM users");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $query = $this->db->query("SELECT id, username FROM users WHERE id = '{$id}'");
        return $query->fetch_assoc();
    }

    public function create($userId, $name, $password)
    {
        $query = $this->db->query("INSERT INTO users (id,username, password) VALUES ('{$userId}','{$name}', '{$password}')");
        return $query;
    }

    public function update($id, $username, $password)
    {
        $query = $this->db->query("UPDATE users SET username = '{$username}', password = '{$password}' WHERE id = '{$id}'");
        return $query;
    }

    public function delete($id)
    {
        $query = $this->db->query("DELETE FROM users WHERE id = '{$id}'");
        return $query;
    }

    public function usernameExists($username)
    {
        $stmt = $this->db->query("SELECT id FROM users WHERE username = '{$username}'");
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function findByUsername($username)
    {
        $result = $this->db->query("SELECT * FROM users WHERE username = '{$username}'");
        return $result->fetch_assoc();
    }
}
?>