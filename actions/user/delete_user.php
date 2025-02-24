<?php

require_once __DIR__ . '/../../core/business/user_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$user_business = new UserBusiness();

try {
    $user = $user_business->delete_user_by_id($_POST['id']);

    if ($user) {
        header('Location: /pages/admin/users.php?success=User deleted successfully');
    } else {
        echo json_encode("{}");
    }
} catch (Exception $e) {
    header('Location: /pages/events.php?error=' . $e->getMessage());
}
