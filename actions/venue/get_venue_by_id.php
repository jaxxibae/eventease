<?php

require_once __DIR__ . '/../../core/business/venue_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$venue_business = new VenueBusiness();

try {
    $venue = $venue_business->get_venue_by_id($_GET['id']);

    if ($venue) {
        echo json_encode($venue);
    } else {
        echo json_encode("{}");
    }
} catch (Exception $e) {
    header('Location: /pages/events.php?error=' . $e->getMessage());
}
