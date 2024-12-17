<?php

require_once __DIR__ . '/../../core/business/user_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$user_business = new UserBusiness();

try {
    $user = $user_business->edit_user($_POST['id'], $_POST['name'], $_POST['email_address'], $_POST['role'], $_POST['is_active']);

    if ($user) {
        header('Location: /pages/admin/users.php?success=User updated successfully.');
    } else {
        echo json_encode("{}");
    }
} catch (Exception $e) {
    header('Location: /pages/dashboard.php?error=' . $e->getMessage());
}
