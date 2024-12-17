<?php


$pageTitle = 'My Events';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$ongoing_events = [];

// Fetch events from the API

require_once __DIR__ . '/../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/event/');

try {
    $response = $client->get_as_json('get_events_by_created_by_id.php?id=' . $sessionStorage->get('user_id'));

    if ($response !== null) {
        $ongoing_events = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>

<main>
    <section class="container">
        <h1>My Events</h1>
        <?php include __DIR__ . '/../core/components/ongoing_event_card.php' ?>
    </section>
</main>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>