<?php

require_once __DIR__ . '/../../core/business/event_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$event_business = new EventBusiness();

try {
    $events = $event_business->get_next_event_user_is_attending($_GET['user_id']);

    if ($events) {
        echo json_encode($events);
    } else {
        echo json_encode(null);
    }
} catch (Exception $e) {
    header('Location: /pages/events.php?error=' . $e->getMessage());
}
