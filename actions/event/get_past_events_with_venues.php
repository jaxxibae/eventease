<?php

require_once __DIR__ . '/../../core/business/event_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$event_business = new EventBusiness();

try {
    $events = $event_business->get_past_events_with_venues();

    if ($events) {
        echo json_encode($events);
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    header('Location: /pages/dashboard.php?error=' . $e->getMessage());
}
