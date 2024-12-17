<?php


$pageTitle = 'Event - Leave Feedback';
require_once __DIR__ . '/../../core/components/header.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$event = [];

$attendance = null;

// Fetch events from the API

require_once __DIR__ . '/../../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/event/');

try {
    $response = $client->get_as_json('get_event_by_id.php?id=' . $_GET['event_id']);

    if ($response !== null) {
        $event = $response;
    }

    $client = new Requests('http://localhost:5550/actions/attendance/');

    $response = $client->get_as_json('get_attendance_by_event_id_and_user_id.php?event_id=' . $_GET['event_id'] . '&user_id=' . $sessionStorage->get('user_id'));

    if ($response !== null) {
        $attendance = $response;
    }

    if ($attendance === null || $attendance->Status !== 'Confirmed') {
        header('Location: /pages/events.php');
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>

<main>
    <section class="container">
        <?php require_once __DIR__ . '/../../core/components/event_card_simplified.php'; ?>

        <div>
            <h2>Leave Feedback</h2>
            <form action="/actions/feedback/create_feedback.php" method="POST">
                <input type="hidden" name="event_id" value="<?php echo $event->Id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $sessionStorage->get('user_id'); ?>">
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <select class="form-control" id="rating" name="rating">
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>
                <div class="input-field">
                    <textarea id="comment" class="materialize-textarea"></textarea>
                    <label for="comment">Comment</label>
                </div>
                <button type="submit" class="btn">Submit Feedback</button>
            </form>
        </div>

    </section>
</main>

<?php
require_once __DIR__ . '/../../core/components/footer.php';
?>