<?php


$pageTitle = 'Admin Area - Attendances - View';
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

$client = new Requests('http://localhost:5550/actions/attendance/');

try {
    $response = $client->get_as_json('get_attendance_by_id.php?id=' . $_GET['id']);

    if ($response !== null) {
        $data = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>

<main>
    <section class="container">
        <h1>Attendance Data</h1>
        <div class="row">
            <div class="input-field col s12">
                <input id="event_name" name="event_name" type="text" class="validate" disabled>
                <label for="event_name">Event Name</label>
            </div>
            <div class="input-field col s12">
                <input id="user_name" name="user_name" type="text" class="validate" disabled>
                <label for="user_name">Attendee</label>
            </div>
            <div class="input-field col s12">
                <select name="status" id="status" disabled>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <label for="status">Status</label>
            </div>
            <div class="input-field col s12">
                <input id="motive" name="motive" type="text" class="validate" disabled>
                <label for="motive">Motive</label>
            </div>
        </div>
        <div class="row">
            <a href="/pages/admin/attendances/delete_attendance.php?id=<?php echo $_GET['id']; ?>" class="waves-effect waves-light btn red"><i class="material-icons left">delete</i>Delete</a>
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

    console.log(<?php echo json_encode($data); ?>);

    document.getElementById('event_name').value = '<?php echo $data->EventName; ?>';
    document.getElementById('user_name').value = '<?php echo $data->UserName; ?>';
    document.getElementById('status').value = '<?php echo $data->Status; ?>';
    document.getElementById('motive').value = '<?php echo $data->Motive; ?>';
</script>