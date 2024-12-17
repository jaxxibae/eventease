<?php


$pageTitle = 'Create Event';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$user_data = $sessionStorage->get('user_data');

if ($user_data['Role'] !== 'Event Manager') {
    header('Location: /pages/events.php');
}

$venues = [];

// Fetch venues from the API

require_once __DIR__ . '/../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/venue/');

try {
    $response = $client->get_as_json('get_all_venues.php');

    if ($response !== null) {
        $venues = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>

<main>
    <section class="container">
        <h1>Create Event</h1>
        <form action="/actions/event/create_event.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="event_name">Event Name</label>
                <input type="text" class="form-control" id="event_name" name="event_name" required>
            </div>
            <div class="form-group">
                <label for="event_description">Event Description</label>
                <textarea class="materialize-textarea" id="event_description" name="event_description" required></textarea>
            </div>
            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" class="form-control" id="event_date" name="event_date" required>
            </div>
            <div class="form-group">
                <label for="event_time">Event Time</label>
                <input type="time" class="form-control" id="event_time" name="event_time" required>
            </div>
            <div class="form-group">
                <label for="venue_id">Venue</label>
                <select class="form-control" id="venue_id" name="venue_id" required>
                    <option value="" disabled selected>Select a venue</option>
                    <?php foreach ($venues as $venue) : ?>
                        <option value="<?= $venue->Id ?>"><?= $venue->Name . ' - ' . $venue->Location ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="event_icon">Event Icon</label>
                <br>
                <input type="file" id="event_icon" name="event_icon" accept="image/*">
            </div>

            <br>

            <div class="form-group">
                <label for="event_banner">Event Banner</label>
                <br>
                <input type="file" id="event_banner" name="event_banner" accept="image/*">
            </div>

            <br>
            <hr>
            <br>

            <a class="waves-effect waves-teal btn" href="events.php">
                <i class="material-icons left">arrow_back</i>
                Back to Events
            </a>
            <a class="waves-effect waves-teal btn" href="create_venue.php">
                <i class="material-icons left">location_on</i>
                Create Venue
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="material-icons left">save</i>
                Create Event
            </button>
        </form>
    </section>
</main>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>