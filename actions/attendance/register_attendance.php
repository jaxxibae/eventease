<?php

require_once __DIR__ . '/../../core/business/attendance_business.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$attendance_business = new AttendanceBusiness();

try {
    $attendance = $attendance_business->register_attendance($_POST['event_id'], $sessionStorage->get('user_id'));

    if ($attendance) {
        header('Location: /pages/event.php?id=' . $_POST['event_id'] . '&success=Attendance submitted for approval of the event organizer.');
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    header('Location: /pages/event.php?id=' . $_POST['event_id'] . '&error=' . $e->getMessage());
}
