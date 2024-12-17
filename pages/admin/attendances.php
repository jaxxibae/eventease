<?php


$pageTitle = 'Admin Area - Attendances';
require_once __DIR__ . '/../../core/components/header.php';
require_once __DIR__ . '/../../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$user_data = $sessionStorage->get('user_data');

if ($user_data['Role'] !== 'Admin') {
    header('Location: /pages/events.php');
}

$data = [];

// Fetch users from the API

require_once __DIR__ . '/../../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/attendance/');

try {
    $response = $client->get_as_json('get_all_attendances.php');

    if ($response !== null) {
        $data = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

$object_name = 'attendance';
$rows = [
    'Id' => 'ID',
    'EventName' => 'Event Name',
    'UserName' => 'User Name',
    'Status' => 'Status',
];

?>

<main>
    <section class="container">
        <h1>Attendance</h1>
        <?php if (empty($data)): ?>
            <p>No attendances found.</p>
        <?php else: ?>
            <?php require_once __DIR__ . '/../../core/components/datatable.php'; ?>
        <?php endif; ?>
    </section>
</main>

<?php
require_once __DIR__ . '/../../core/components/footer.php';
?>