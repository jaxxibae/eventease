<?php


$pageTitle = 'Event';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$event = [];

$attendance = null;

$feedback = null;

// Fetch events from the API

require_once __DIR__ . '/../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/event/');

try {
    $response = $client->get_as_json('get_event_by_id.php?id=' . $_GET['id']);

    if ($response !== null) {
        $event = $response;
    }

    $client = new Requests('http://localhost:5550/actions/attendance/');

    $response = $client->get_as_json('get_attendance_by_event_id_and_user_id.php?event_id=' . $_GET['id'] . '&user_id=' . $sessionStorage->get('user_id'));

    if ($response !== null) {
        $attendance = $response;
    }

    $client = new Requests('http://localhost:5550/actions/feedback/');

    $response = $client->get_as_json('get_feedback_by_event_id_and_user_id.php?event_id=' . $_GET['id'] . '&user_id=' . $sessionStorage->get('user_id'));

    if ($response !== null) {
        $feedback = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

$currentDate = new DateTime();
$eventDate = new DateTime($event->EventDate . ' ' . $event->EventTime);
$isPastEvent = $eventDate < $currentDate;

?>

<main>
    <section class="container">
        <?php require_once __DIR__ . '/../core/components/event_card_simplified.php'; ?>

        <div class="event-detail">
            <div class="event-detail-left">
                <h2>Event Details</h2>
                <?php echo '<p>' . $event->Description . '</p>'; ?>

                <h2>Time and Place</h2>
                <?php
                $date = new DateTime($event->EventDate . ' ' . $event->EventTime);
                $formattedDate = $date->format('l, F j, Y \a\t g:i A');
                echo '<p>Starts on <b>' . $formattedDate . '</b> </p>';
                ?>
                <?php echo '<p>Venue: <b>' . $event->VenueName . '</b> </p>'; ?>
                <?php echo '<p>Location: <b>' . $event->VenueLocation . '</b> </p>'; ?>
                <?php
                $locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
                $fmt = new NumberFormatter($locale, NumberFormatter::DECIMAL);
                $formattedCapacity = $fmt->format($event->VenueCapacity);
                echo '<p>Capacity: <b>' . $formattedCapacity . ' people</b> </p>';
                ?>
            </div>
            <div class="event-detail-right">
                <h2>Event Organizer</h2>
                <p><?php echo $event->EventOrganizer; ?></p>
                <h2>Want to attend?</h2>
                <form action="/actions/attendance/register_attendance.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $event->Id; ?>">
                    <button class="waves-effect waves-light btn <?php echo $isPastEvent ? 'disabled' : ($attendance ? 'disabled' : ''); ?>">
                        <i class="material-icons left">event</i>
                        <?php echo $isPastEvent ? 'The Event Has Ended' : ($attendance ? (
                            $attendance->Status === 'Pending' ? 'Attendance Pending Approval' : ($attendance->Status === 'Confirmed' ? 'Attendance Confirmed' : 'Attendance Rejected')
                        ) : 'Register Attendance');
                        ?>
                    </button>
                    <?php if (!empty($attendance) && $attendance->Status === 'Confirmed' && $isPastEvent) : ?>
                        <button class="waves-effect waves-light btn" <?php echo $feedback ? 'disabled' : ''; ?> onclick="event.preventDefault(); window.location.href = 'event/leave_feedback.php?event_id=<?php echo $event->Id; ?>'">
                            <i class="material-icons left">feedback</i>
                            <?php echo $feedback ? 'Feedback Submitted' : 'Leave Feedback'; ?>
                        </button>
                    <?php endif; ?>
                </form>
                <div class="event-organizer-actions">
                    <?php if ($event->CreatedById == $sessionStorage->get('user_id')) : ?>
                        <h2>Manage Event</h2>
                        <a href="edit_event.php?id=<?php echo $event->Id; ?>" class="waves-effect waves-light btn">
                            <i class="material-icons left">edit</i>
                            Edit Event
                        </a>
                        <a href="/actions/event/delete_event.php?id=<?php echo $event->Id; ?>" class="waves-effect waves-light btn red">
                            <i class="material-icons left">delete</i>
                            Delete Event
                        </a>
                        <a href="event_attendance.php?id=<?php echo $event->Id; ?>" class="waves-effect waves-light btn">
                            <i class="material-icons left">event</i>
                            View Attendance
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </section>
</main>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>