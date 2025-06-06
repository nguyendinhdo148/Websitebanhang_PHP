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

    public function getAccountByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    function save($username, $name, $password, $role = "user", $email = null, $phoneNumber = null, $avatar = null)
    {
        try {
            // Begin transaction
            $this->conn->beginTransaction();

            // Check for existing email if provided
            if ($email !== null) {
                $existingEmail = $this->getAccountByEmail($email);
                if ($existingEmail) {
                    throw new PDOException("Email đã được sử dụng");
                }
            }

            $query = "INSERT INTO " . $this->table_name . 
                    "(username, password, fullname, role, email, phone_number, avatar) VALUES 
                    (:username, :password, :fullname, :role, :email, :phone_number, :avatar)";

            $stmt = $this->conn->prepare($query);

            // Clean the data
            $name = htmlspecialchars(strip_tags($name));
            $username = htmlspecialchars(strip_tags($username));
            $email = $email ? htmlspecialchars(strip_tags($email)) : null;
            $phoneNumber = $phoneNumber ? htmlspecialchars(strip_tags($phoneNumber)) : null;

            // Bind parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':fullname', $name);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone_number', $phoneNumber);
            $stmt->bindParam(':avatar', $avatar);

            $result = $stmt->execute();
            
            // Commit transaction
            $this->conn->commit();
            
            return $result;

        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteUserRelatedRecords($userId) {
        try {
            // Delete cart items first
            $query = "DELETE ci FROM cart_item ci 
                     INNER JOIN cart c ON ci.cart_id = c.id 
                     WHERE c.user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['user_id' => $userId]);

            // Delete cart
            $query = "DELETE FROM cart WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['user_id' => $userId]);

            // Delete order details
            $query = "DELETE od FROM order_details od 
                     INNER JOIN orders o ON od.order_id = o.id 
                     WHERE o.user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['user_id' => $userId]);

            // Delete orders
            $query = "DELETE FROM orders WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['user_id' => $userId]);

            return true;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function deleteUser($id) {
        try {
            $this->conn->beginTransaction();

            // Delete related records first
            $this->deleteUserRelatedRecords($id);

            // Now delete the user
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute(['id' => $id]);

            $this->conn->commit();
            return $result;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function updateUser($id, $username, $fullname, $role) {
        $query = "UPDATE " . $this->table_name . " 
                 SET username = :username, fullname = :fullname, role = :role 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'username' => $username,
            'fullname' => $fullname,
            'role' => $role
        ]);
    }

    public function updateProfile($id, $fullname, $email, $phoneNumber, $avatar = null) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET fullname = :fullname, 
                         email = :email, 
                         phone_number = :phone_number" .
                     ($avatar ? ", avatar = :avatar" : "") .
                     " WHERE id = :id";
            
            $params = [
                'id' => $id,
                'fullname' => $fullname,
                'email' => $email,
                'phone_number' => $phoneNumber
            ];
            
            if ($avatar) {
                $params['avatar'] = $avatar;
            }
            
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }
}
