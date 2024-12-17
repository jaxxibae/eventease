<?php

require_once __DIR__ . '/../../core/business/feedback_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$feedback_business = new FeedbackBusiness();

try {
    $feedback = $feedback_business->get_feedback_by_event_id_and_user_id($_GET['event_id'], $_GET['user_id']);

    if ($feedback) {
        echo json_encode($feedback);
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    header('Location: /pages/event.php?id=' . $_POST['event_id'] . '&error=' . $e->getMessage());
}
