<?php

require_once __DIR__ . '/../data/attendance_dao.php';
require_once __DIR__ . '/../data/event_dao.php';

class AttendanceBusiness
{
    private $attendanceDAO;
    private $eventDAO;

    public function __construct()
    {
        $this->attendanceDAO = new AttendanceDAO();
        $this->eventDAO = new EventDAO();
    }

    public function get_all_attendances(): array
    {
        return $this->attendanceDAO->get_all_attendances();
    }
    
    public function register_attendance($event_id, $user_id): bool
    {
        $event_data = $this->eventDAO->get_event_by_id($event_id);

        $currentDate = new DateTime();
        $eventDate = new DateTime($event_data->EventDate . ' ' . $event_data->EventTime);
        $isPastEvent = $eventDate < $currentDate;

        if ($isPastEvent) {
            throw new Exception('Attendance cannot be registered for past events.');
        }

        $existing_attendance = $this->attendanceDAO->get_attendance_by_event_id_and_user_id($event_id, $user_id);

        if ($existing_attendance) {
            throw new Exception('Attendance already registered for this event.');
        }

        return $this->attendanceDAO->register_attendance($event_id, $user_id);
    }

    public function get_attendance_by_event_id_and_user_id($event_id, $user_id): mixed
    {
        return $this->attendanceDAO->get_attendance_by_event_id_and_user_id($event_id, $user_id);
    }

    public function get_attendance_by_event_id($event_id): array
    {
        return $this->attendanceDAO->get_attendance_by_event_id($event_id);
    }
    
    public function update_attendance($attendance_id, $event_id, $status, $motive): bool
    {
        return $this->attendanceDAO->update_attendance($attendance_id, $status, $motive ?? '');
    }

    public function get_attendances_by_user_id($user_id): array
    {
        return $this->attendanceDAO->get_attendances_by_user_id($user_id);
    }
}