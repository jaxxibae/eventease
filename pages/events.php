<?php


$pageTitle = 'Events';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$ongoing_events = [];
$past_events = [];

// Fetch events from the API

require_once __DIR__ . '/../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/event/');

try {
    $response = $client->get_as_json('get_ongoing_events_with_venues.php');

    if ($response !== null) {
        $ongoing_events = $response;
    }

    $response = $client->get_as_json('get_past_events_with_venues.php');
    if ($response !== null) {
        $past_events = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>

<main>
    <section class="container">
        <h1>Events</h1>
        <h2>Ongoing Events</h2>
        <?php include __DIR__ . '/../core/components/ongoing_event_card.php' ?>
        <h2>Past Events</h2>
        <?php include __DIR__ . '/../core/components/past_event_card.php' ?>
    </section>
</main>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>