<?php

require_once __DIR__ . '/../../core/business/attendance_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$attendance_business = new AttendanceBusiness();

try {
    $attendances = $attendance_business->get_all_attendances();

    if ($attendances) {
        echo json_encode($attendances);
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    header('Location: /pages/event.php?id=' . $_POST['event_id'] . '&error=' . $e->getMessage());
}
