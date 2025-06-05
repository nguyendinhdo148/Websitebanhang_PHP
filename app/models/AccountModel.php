<?php
class AccountModel
{
    private $conn;
    private $table_name = "users"; // Changed from "account" to "users"
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getAccountByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username"; // Updated table name
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    function save($username, $name, $password, $role = "user")
    {
        $query = "INSERT INTO " . $this->table_name . 
                "(username, password, fullname, role) VALUES 
                (:username, :password, :fullname, :role)";

        $stmt = $this->conn->prepare($query);

        // Clean the data
        $name = htmlspecialchars(strip_tags($name));
        $username = htmlspecialchars(strip_tags($username));

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':fullname', $name);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }
}
