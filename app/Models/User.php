<?php
namespace App\Models;

use App\Services\Database;

class User
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::connect();
    }

    public function get_all()
    {
        $result = $this->_db->query("SELECT * FROM users");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get_by_id($id)
    {
        $query = $this->_db->query("SELECT id, username FROM users WHERE id = '{$id}'");
        return $query->fetch_assoc();
    }

    public function create($userId, $name, $password)
    {
        $query = $this->_db->query("INSERT INTO users (id,username, password) VALUES ('{$userId}','{$name}', '{$password}')");
        return $query;
    }

    public function update($id, $username, $password)
    {
        $query = $this->_db->query("UPDATE users SET username = '{$username}', password = '{$password}' WHERE id = '{$id}'");
        return $query;
    }

    public function delete($id)
    {
        $query = $this->_db->query("DELETE FROM users WHERE id = '{$id}'");
        return $query;
    }

    public function username_exists($username)
    {
        $query = $this->_db->query("SELECT id FROM users WHERE username = '{$username}'");
        $exists = $query->num_rows > 0;
        $query->close();
        return $exists;
    }

    public function find_by_username($username)
    {
        $query = $this->_db->query("SELECT * FROM users WHERE username = '{$username}'");
        return $query->fetch_assoc();
    }
}
?>