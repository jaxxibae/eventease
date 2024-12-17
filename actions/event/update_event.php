<?php

require_once __DIR__ . '/../../core/business/event_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$event_business = new EventBusiness();

try {
    $event = $event_business->update_event($_POST['event_id'], $_POST['event_name'], $_POST['event_description'], $_POST['event_date'], $_POST['event_time'], $_POST['venue_id'], $_SESSION['user_id']);

    if ($event) {
        // Check if the event icon and banner are set
        if (isset($_FILES['event_icon']) && $_FILES['event_icon']['size'] > 0) {
            $event_icon = $event_business->upload_event_picture($event['Id'], $_FILES['event_icon'], 'event_icon');
        }

        if (isset($_FILES['event_banner']) && $_FILES['event_banner']['size'] > 0) {
            $event_banner = $event_business->upload_event_picture($event['Id'], $_FILES['event_banner'], 'event_banner');
        }
        
        header('Location: /pages/events.php?success=Event updated successfully.');
    } else {
        echo json_encode("{}");
    }
} catch (Exception $e) {
    header('Location: /pages/create_event.php?error=' . $e->getMessage());
}
