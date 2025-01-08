<?php


$pageTitle = 'Event Feedback';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$event = [];

$feedbacks = [];

// Fetch events from the API

require_once __DIR__ . '/../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/event/');

try {
    $response = $client->get_as_json('get_event_by_id.php?id=' . $_GET['id']);

    if ($response !== null) {
        $event = $response;
    }

    if ($event->EventOrganizerId !== $sessionStorage->get('user_id')) {
        header('Location: /pages/events.php');
    }

    $client = new Requests('http://localhost:5550/actions/feedback/');

    $response = $client->get_as_json('get_feedback_by_event_id.php?event_id=' . $_GET['id']);

    if ($response !== null) {
        $feedbacks = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
?>

<main>
    <section class="container">
        <?php require_once __DIR__ . '/../core/components/event_card_simplified.php'; ?>

        <div class="col-12 col-md-12">
            <h2>Feedback</h2>
            <table>
                <thead>
                    <tr>
                        <th>Attendee</th>
                        <th>Rating</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($feedbacks as $feedback) {
                        echo '<tr>';
                        echo '<td>' . $feedback->AttendeeName . '</td>';
                        echo '<td>' . $feedback->Rating . ' star(s)</td>';
                        echo '<td>' . $feedback->Comment . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?php
require_once __DIR__ . '/../core/components/footer.php';
?>