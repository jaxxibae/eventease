<?php

require_once __DIR__ . '/../../core/business/venue_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$venue_business = new VenueBusiness();

try {
    $venue = $venue_business->create_venue($_POST['venue_name'], $_POST['venue_city'], $_POST['venue_country'], $_POST['venue_capacity']);

    if ($venue) {
        header('Location: /pages/events.php?success=Venue created successfully.');
    } else {
        echo json_encode("{}");
    }
} catch (Exception $e) {
    header('Location: /pages/events.php?error=' . $e->getMessage());
}
