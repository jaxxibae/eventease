<?php

require_once __DIR__ . '/../../core/business/attendance_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$attendance_business = new AttendanceBusiness();

try {
    $attendance = $attendance_business->update_attendance($_POST['attendance_id'], $_POST['event_id'], $_POST['status'], $_POST['motive']);

    if ($attendance) {
        header('Location: /pages/event_attendance.php?id=' . $_POST['event_id'] . '&success=Attendance updated successfully.');
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    header('Location: /pages/event_attendance.php?id=' . $_POST['event_id'] . '&error=' . $e->getMessage());
}
