<?php

require_once __DIR__ . '/../../core/business/feedback_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$feedback_business = new FeedbackBusiness();

try {
    $feedbacks = $feedback_business->get_all_feedbacks();

    if ($feedbacks) {
        echo json_encode($feedbacks);
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    header('Location: /pages/event.php?id=' . $_POST['event_id'] . '&error=' . $e->getMessage());
}
