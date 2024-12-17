<?php

require_once __DIR__ . '/../../core/business/user_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$user_business = new UserBusiness();

try {
    $user = $user_business->change_password($_POST['user_id'], $_POST['current_password'], $_POST['new_password'], $_POST['confirm_password']);

    if ($user) {
        header('Location: /pages/profile.php?success=Password changed successfully.');
    } else {
        echo json_encode("{}");
    }
} catch (Exception $e) {
    header('Location: /pages/change_password.php?error=' . $e->getMessage());
}
