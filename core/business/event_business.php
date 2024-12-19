<?php

require_once __DIR__ . '/../data/event_dao.php';
require_once __DIR__ . '/../data/attendance_dao.php';
require_once __DIR__ . '/../utils/upload_file.php';

class EventBusiness
{
    private $eventDAO;
    private $attendanceDAO;

    public function __construct()
    {
        $this->eventDAO = new EventDAO();
        $this->attendanceDAO = new AttendanceDAO();
    }

    public function get_all_events(): array
    {
        return $this->eventDAO->get_all_events();
    }

    public function get_ongoing_events_with_venues(): array
    {
        return $this->eventDAO->get_ongoing_events_with_venues();
    }

    public function get_past_events_with_venues(): array
    {
        return $this->eventDAO->get_past_events_with_venues();
    }

    public function get_events_by_created_by_id($user_id): array
    {
        return $this->eventDAO->get_events_by_created_by_id($user_id);
    }

    public function get_event_by_id($id): mixed
    {
        return $this->eventDAO->get_event_by_id($id);
    }

    public function create_event($event_name, $event_description, $event_date, $event_time, $venue_id, $created_by_id): mixed
    {
        return $this->eventDAO->create_event($event_name, $event_description, $event_date, $event_time, $venue_id, $created_by_id);
    }

    public function upload_event_picture($event_id, $event_picture, $picture_type): mixed
    {
        $image_field = match ($picture_type) {
            'event_icon' => 'IconUrl',
            'event_banner' => 'BannerUrl',
            default => null,
        };

        if ($image_field === null) {
            throw new Exception("Invalid picture type.");
        }

        $event = $this->eventDAO->get_event_by_id($event_id);

        if ($event === null) {
            throw new Exception("Event not found.");
        }

        $uploaded_file_name = upload_file($event_picture, 'events', null);

        if ($uploaded_file_name === false) {
            throw new Exception("Failed to upload file.");
        }

        $uploaded_file_name = "/assets/events/$uploaded_file_name";

        return $this->eventDAO->upload_event_picture($event_id, $uploaded_file_name, $image_field);
    }

    public function get_next_event_user_is_attending($user_id): mixed
    {
        $user_attendances = $this->attendanceDAO->get_attendances_by_user_id($user_id);

        if (empty($user_attendances)) {
            return null;
        }

        $next_event = null;

        foreach ($user_attendances as $attendance) {
            $event = $this->eventDAO->get_event_by_id($attendance['EventId']);

            if ($event === null) {
                continue;
            }

            if ($next_event === null) {
                $next_event = $event;
            } else {
                $next_event_date = new DateTime($next_event['EventDate'] . ' ' . $next_event['EventTime']);
                $event_date = new DateTime($event['EventDate'] . ' ' . $event['EventTime']);

                if ($event_date < $next_event_date) {
                    $next_event = $event;
                }
            }
        }

        if (new DateTime($next_event['EventDate'] . ' ' . $next_event['EventTime']) < new DateTime()) {
            return null;
        }

        return $next_event;
    }

    public function update_event($event_id, $event_name, $event_description, $event_date, $event_time, $venue_id, $created_by_id): mixed
    {
        $event = $this->eventDAO->get_event_by_id($event_id);

        if ($event === null) {
            throw new Exception("Event not found.");
        }

        if ($event['CreatedById'] !== $created_by_id) {
            throw new Exception("You are not allowed to update this event.");
        }

        return $this->eventDAO->update_event($event_id, $event_name, $event_description, $event_date, $event_time, $venue_id);
    }

    public function get_events_attended_by_user($user_id): array
    {
        $user_attendances = $this->attendanceDAO->get_attendances_by_user_id($user_id);

        $events = [];

        foreach ($user_attendances as $attendance) {
            $event = $this->eventDAO->get_event_by_id($attendance['EventId']);

            if ($event !== null) {
                $events[] = $event;
            }
        }

        return $events;
    }
}