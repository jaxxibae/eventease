<?php


$pageTitle = 'Admin Area';
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

?>

<main>
    <section class="container">
        <h1>Admin Area</h1>
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Users</span>
                        <p>View and manage users</p>
                    </div>
                    <div class="card-action">
                        <a href="/pages/admin/users.php">View Users</a>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Venues</span>
                        <p>View and manage venues</p>
                    </div>
                    <div class="card-action">
                        <a href="/pages/admin/venues.php">View Venues</a>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Events</span>
                        <p>View and manage events</p>
                    </div>
                    <div class="card-action">
                        <a href="/pages/admin/events.php">View Events</a>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Attendances</span>
                        <p>View and manage attendances</p>
                    </div>
                    <div class="card-action">
                        <a href="/pages/admin/attendances.php">View Attendances</a>
                    </div>
                </div>
            </div>
            <div class="col s12 m12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Event Feedback</span>
                        <p>View and manage event feedback</p>
                    </div>
                    <div class="card-action">
                        <a href="/pages/admin/feedbacks.php">View Event Feedback</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
require_once __DIR__ . '/../../core/components/footer.php';
?>