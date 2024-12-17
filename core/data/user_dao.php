<?php

require_once __DIR__ . '/../utils/mysql_pdo_connector.php';
require_once __DIR__ . '/../utils/encryption.php';

class UserDAO {
    private $db;

    public function __construct() {
        $this->db = (new MySQLPDOConnector())->getConnection();
    }

    public function get_all_users(): mixed {
        $stmt = $this->db->prepare("SELECT * FROM Users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_user_by_email($email) {
        $stmt = $this->db->prepare('SELECT * FROM Users WHERE EmailAddress = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_user_by_id($id) {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE Id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($email_address, $password): mixed {
        $stmt = $this->db->prepare("SELECT Id, Hash, Salt FROM Users WHERE EmailAddress = :email_address");
        $stmt->bindParam(':email_address', $email_address);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $hash = Encryption::hashPassword($password, $user['Salt']);

        if ($user && Encryption::hashPassword($password, $user['Salt']) === $user['Hash']) {
            return $user['Id'];
        } else {
            throw new Exception('Invalid email or password. '. $hash);
        }
    }

    public function register($name, $email_address, $hash, $salt, $role): mixed {
        $stmt = $this->db->prepare("INSERT INTO Users (Name, EmailAddress, Hash, Salt, Role) VALUES (:name, :email_address, :hash, :salt, :role)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':salt', $salt);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function change_password($user_id, $hash, $salt): mixed {
        $stmt = $this->db->prepare("UPDATE Users SET Hash = :hash, Salt = :salt WHERE Id = :user_id");
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':salt', $salt);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $this->get_user_by_id($user_id);
    }

    public function edit_profile($user_id, $name, $email_address): mixed {
        $stmt = $this->db->prepare("UPDATE Users SET Name = :name, EmailAddress = :email_address WHERE Id = :user_id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $this->get_user_by_id($user_id);
    }

    public function delete_user_by_id($id): mixed {
        $stmt = $this->db->prepare("DELETE FROM Users WHERE Id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $this->get_user_by_id($id);
    }

    public function edit_user($id, $name, $email_address, $role, $is_active): mixed {
        $is_active = (bool) $is_active;

        $stmt = $this->db->prepare("UPDATE Users SET Name = :name, EmailAddress = :email_address, Role = :role, Active = :is_active WHERE Id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':is_active', $is_active, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $this->get_user_by_id($id);
    }
}