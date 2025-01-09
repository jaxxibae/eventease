<?php

require_once __DIR__ . '/../../core/business/feedback_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$feedback_business = new FeedbackBusiness();

try {
    $feedback = $feedback_business->delete_feedback_by_id($_POST['id']);

    if ($feedback) {
        header('Location: /pages/admin/feedbacks.php?success=User deleted successfully');
    } else {
        echo json_encode("{}");
    }
} catch (Exception $e) {
    header('Location: /pages/dashboard.php?error=' . $e->getMessage());
}
