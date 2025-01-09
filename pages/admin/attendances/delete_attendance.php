<?php


$pageTitle = 'Admin Area - Attendances - Delete';
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
        <h1>Delete Attendance</h1>
        <p>Are you sure you want to delete this attendance?</p>
        <p>This action cannot be undone.</p>
        <div class="row">
            <form action="/actions/attendance/delete_attendance.php" class="col s12" method="POST">
                <div class="row">
                    <input type="text" id="id" name="id" value="<?php echo $data->Id; ?>" hidden>
                    <button class="btn waves-effect waves-light red" type="submit" name="action">Delete
                        <i class="material-icons left">delete</i>
                    </button>
                </div>
            </form>
    </section>
</main>


<?php
require_once __DIR__ . '/../../../core/components/footer.php';
?>