<?php

require_once __DIR__ . '/../../core/business/user_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$user_business = new UserBusiness();

try {
    $user = $user_business->edit_profile($_POST['user_id'], $_POST['name'], $_POST['email_address']);

    if ($user) {
        header('Location: /pages/profile.php?success=Profile updated successfully.');
    } else {
        echo json_encode("{}");
    }
} catch (Exception $e) {
    header('Location: /pages/edit_profile.php?error=' . $e->getMessage());
}
