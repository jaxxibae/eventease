<?php


$pageTitle = 'Admin Area - Feedbacks - View';
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

$client = new Requests('http://localhost:5550/actions/feedback/');

try {
    $response = $client->get_as_json('get_feedback_by_id.php?id=' . $_GET['id']);

    if ($response !== null) {
        $data = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>

<main>
    <section class="container">
        <h1>Feedback Data</h1>
        <div class="row">
            <div class="input-field col s12">
                <input id="event_name" name="event_name" type="text" class="validate" disabled>
                <label for="event_name">Event Name</label>
            </div>
            <div class="input-field col s12">
                <input id="attendee_name" name="attendee_name" type="text" class="validate" disabled>
                <label for="attendee_name">Attendee Name</label>
            </div>
            <div class="input-field col s12">
                <input id="rating" name="rating" type="text" class="validate" disabled>
                <label for="rating">Rating</label>
            </div>
            <div class="input-field col s12">
                <input id="comment" name="comment" type="text" class="validate" disabled>
                <label for="comment">Comment</label>
            </div>
        </div>
        <div class="row">
            <a href="/pages/admin/feedbacks/delete_feedback.php?id=<?php echo $_GET['id']; ?>" class="waves-effect waves-light btn red"><i class="material-icons left">delete</i>Delete</a>
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

    document.getElementById('event_name').value = '<?php echo $data->EventName; ?>';
    document.getElementById('attendee_name').value = '<?php echo $data->AttendeeName; ?>';
    document.getElementById('rating').value = '<?php echo $data->Rating; ?>';
    document.getElementById('comment').value = '<?php echo $data->Comment; ?>';
</script>