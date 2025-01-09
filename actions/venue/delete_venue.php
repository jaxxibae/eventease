<?php

require_once __DIR__ . '/../../core/business/venue_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$venue_business = new VenueBusiness();

try {
    $venue = $venue_business->delete_venue_by_id($_POST['id']);

    if ($venue) {
        header('Location: /pages/admin/venues.php?success=User deleted successfully');
    } else {
        echo json_encode("{}");
    }
} catch (Exception $e) {
    header('Location: /pages/dashboard.php?error=' . $e->getMessage());
}
