<?php

require_once __DIR__ . '/../data/user_dao.php';
require_once __DIR__ . '/../utils/encryption.php';

class UserBusiness
{
    private $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAO();
    }

    public function get_all_users(): mixed
    {
        return $this->userDAO->get_all_users();
    }

    public function login($email_address, $password): mixed
    {
        $user_id = $this->userDAO->login($email_address, $password);

        if ($user_id === false) {
            throw new Exception('Invalid email or password');
        }

        $user_data = $this->userDAO->get_user_by_id($user_id);

        if ($user_data['Active'] === 0) {
            throw new Exception('Your account is pending admin approval.');
        } else {
            return $user_data["Id"];
        }
    }

    public function register($name, $email_address, $password, $confirm_password, $role): mixed
    {
        if ($password !== $confirm_password) {
            throw new Exception('Passwords do not match.');
        }

        $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";

        if (!preg_match($regex, $password)) {
            throw new Exception('Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
        }

        $user_data = $this->userDAO->get_user_by_email($email_address);

        if ($user_data['Id']) {
            throw new Exception('Email address is already in use.');
        }

        $salt = Encryption::generateSalt();
        $hash = Encryption::hashPassword($password, $salt);

        $user_id = $this->userDAO->register($name, $email_address, $hash, $salt, $role);

        $user_data = $this->userDAO->get_user_by_id($user_id);

        if ($user_data['Active'] === 0) {
            return "N/A";
        } else {
            return $user_data["Id"];
        }
    }

    public function get_user_by_id($id): mixed
    {
        return $this->userDAO->get_user_by_id($id);
    }

    public function get_clean_user_by_id($id): mixed
    {
        $user_data = $this->userDAO->get_user_by_id($id);
        return $this->sanitize_sensitive_data($user_data);
    }

    public function change_password($user_id, $current_password, $new_password, $confirm_password): mixed
    {
        if ($new_password !== $confirm_password) {
            throw new Exception('Passwords do not match.');
        }

        $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";

        if (!preg_match($regex, $new_password)) {
            throw new Exception('Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
        }

        $user_data = $this->userDAO->get_user_by_id($user_id);

        if ($user_data['Id'] === null) {
            throw new Exception('User not found.');
        }

        $current_hash = Encryption::hashPassword($current_password, $user_data['Salt']);

        if ($current_hash !== $user_data['Hash']) {
            throw new Exception('Invalid current password.');
        }

        $salt = Encryption::generateSalt();
        $hash = Encryption::hashPassword($new_password, $salt);

        $this->userDAO->change_password($user_id, $hash, $salt);

        return $this->userDAO->get_user_by_id($user_id);
    }

    public function edit_profile($user_id, $name, $email_address): mixed
    {
        $user_data = $this->userDAO->get_user_by_id($user_id);

        if ($user_data['Id'] === null) {
            throw new Exception('User not found.');
        }

        $existing_user = $this->userDAO->get_user_by_email($email_address);

        if ($existing_user['Id'] && $existing_user['Id'] !== $user_id) {
            throw new Exception('Email address is already in use.');
        }

        $this->userDAO->edit_profile($user_id, $name, $email_address);

        return $this->userDAO->get_user_by_id($user_id);
    }

    public function delete_user_by_id($id): mixed
    {
        $user_data = $this->userDAO->get_user_by_id($id);

        if ($user_data['Id'] === null) {
            throw new Exception('User not found.');
        }

        $this->userDAO->delete_user_by_id($id);

        return $user_data;
    }

    public function edit_user($id, $name, $email_address, $role, $is_active): mixed
    {
        $user_data = $this->userDAO->get_user_by_id($id);

        if ($user_data['Id'] === null) {
            throw new Exception('User not found.');
        }

        $active = $is_active === 'on' ? decbin(1) : decbin(0);

        $this->userDAO->edit_user($id, $name, $email_address, $role, $active);

        return $this->userDAO->get_user_by_id($id);
    }

    private function sanitize_sensitive_data($user_data): mixed
    {
        unset($user_data['Hash']);
        unset($user_data['Salt']);
        return $user_data;
    }
}