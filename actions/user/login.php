<?php

require_once __DIR__ . '/../../core/business/user_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$inputJSON = file_get_contents('php://input');
$_POST = json_decode($inputJSON, TRUE);

$email_address = $_POST['email_address'];
$password = $_POST['password'];

$user_business = new UserBusiness();

try {
    $user_id = $user_business->login($email_address, $password);
    if ($user_id) {
        // $sessionStorage->put('user_id', $user_id);
        $user_data = $user_business->get_clean_user_by_id($user_id);
        // $sessionStorage->put('user_data', $user_data);
        $json = [
            'success' => true,
            'message' => 'Login successful',
            'redirect' => '/pages/dashboard.php'
        ];
    } else {
        $json = [
            'success' => false,
            'message' => 'Invalid email address or password'
        ];
    }
} catch (Exception $e) {
    $json = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

header('Content-Type: application/json');
echo json_encode($json);