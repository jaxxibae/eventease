<?php

require_once __DIR__ . '/../../core/business/user_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$email_address = $_POST['email_address'];
$password = $_POST['password'];

$user_business = new UserBusiness();

try {
    $user_id = $user_business->login($email_address, $password);
    if ($user_id) {
        $sessionStorage->put('user_id', $user_id);
        $user_data = $user_business->get_clean_user_by_id($user_id);
        $sessionStorage->put('user_data', $user_data);
        header('Location: /pages/events.php');
    } else {
        header('Location: /pages/login.php?error=Invalid email or password');
    }
} catch (Exception $e) {
    header('Location: /pages/login.php?error=' . $e->getMessage());
}
