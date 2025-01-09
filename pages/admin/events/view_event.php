<?php


$pageTitle = 'Admin Area - Events - View';
require_once __DIR__ . '/../../../core/components/header.php';
require_once __DIR__ . '/../../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null || !isset($_GET['id'])) {
    header('Location: index.php');
}

$user_data = $sessionStorage->get('user_data');

if ($user_data['Role'] !== 'Admin') {
    header('Location: /pages/events.php');
}

$data = [];

// Fetch users from the API

require_once __DIR__ . '/../../../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/event/');

try {
    $response = $client->get_as_json('get_event_by_id.php?id=' . $_GET['id']);

    if ($response !== null) {
        $data = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>

<main>
    <section class="container">
        <h1>Event Data</h1>
        <div class="row">
            <div class="input-field col s12">
                <input id="name" name="name" type="text" class="validate" disabled>
                <label for="name">Name</label>
            </div>
            <div class="input-field col s12">
                <textarea class="materialize-textarea" id="description" name="description" disabled></textarea>
                <label for="description">Description</label>
            </div>
            <div class="input-field col s12">
                <input id="event_date" name="event_date" type="text" class="validate" disabled>
                <label for="event_date">Event Date</label>
            </div>
            <div class="input-field col s12">
                <input id="event_time" name="event_time" type="text" class="validate" disabled>
                <label for="event_time">Event Time</label>
            </div>
            <div class="input-field col s12">
                <input id="venue_name" name="venue_name" type="text" class="validate" disabled>
                <label for="venue_name">Venue Name</label>
            </div>
            <div class="input-field col s12">
                <input id="event_organizer" name="event_organizer" type="text" class="validate" disabled>
                <label for="event_organizer">Event Organizer</label>
            </div>
        </div>
        <div class="row">
            <a href="/pages/admin/events/delete_event.php?id=<?php echo $_GET['id']; ?>" class="waves-effect waves-light btn red"><i class="material-icons left">delete</i>Delete</a>
        </div>
    </section>
</main>


<?php
require_once __DIR__ . '/../../../core/components/footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    });

    document.getElementById('name').value = '<?php echo $data->Name; ?>';
    document.getElementById('description').value = '<?php echo str_replace('"', '', json_encode($data->Description)); ?>';
    document.getElementById('event_date').value = '<?php echo $data->EventDate; ?>';
    document.getElementById('event_time').value = '<?php echo $data->EventTime; ?>';
    document.getElementById('venue_name').value = '<?php echo $data->VenueName; ?>';
    document.getElementById('event_organizer').value = '<?php echo $data->EventOrganizer; ?>';
</script>