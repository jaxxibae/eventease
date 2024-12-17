<?php


$pageTitle = 'My Calendar';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$ongoing_events = [];


$ongoing_events = [];

// Fetch events from the API

require_once __DIR__ . '/../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/event/');

try {
    $response = $client->get_as_json('get_next_event_user_is_attending.php?user_id=' . $sessionStorage->get('user_id'));

    if ($response !== null) {
        $ongoing_events = $response;

        if ($ongoing_events instanceof stdClass) {
            $ongoing_events = [$ongoing_events];
        }
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>
<main>
    <section class="container">
        <h1>My Calendar</h1>
        <?php require_once __DIR__ . '/../core/components/calendar.php'; ?>

        <h2>Next Event I'm Attending</h2>
        <?php if (count($ongoing_events) > 0) : ?>
            <?php include __DIR__ .'/../core/components/ongoing_event_card.php'; ?>
        <?php else : ?>
            <p>You don't have any upcoming events.</p>
        <?php endif; ?>
    </section>
</main>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>