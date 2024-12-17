<?php

require_once __DIR__ . '/../../core/business/user_business.php';

$name = $_POST['name'];
$email_address = $_POST['email_address'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$role = $_POST['role'];

$user_business = new UserBusiness();

try {
    $user_id = $user_business->register($name, $email_address, $password, $confirm_password, $role);
    if ($user_id) {
        if (!is_numeric($user_id)) {
            header('Location: /pages/login.php?success=Your account has been created, but is pending admin approval.');
        } else {
            $_SESSION['user_id'] = $user_id;
            header('Location: /pages/login.php?success=Account created successfully! You can now login.');
        }
    } else {
        header('Location: /pages/register.php?error=An error occurred while creating your account. Please try again.');
    }
} catch (Exception $e) {
    header('Location: /pages/register.php?error=' . $e->getMessage());
}
