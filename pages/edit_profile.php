<?php


$pageTitle = 'Edit Profile';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$user_data = new stdClass();

// Fetch events from the API

require_once __DIR__ . '/../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/user/');

try {
    $response = $client->get_as_json('get_user_by_id.php?id=' . $sessionStorage->get('user_id'));

    if ($response !== null) {
        $user_data = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}


?>

<main>
    <section class="container">
        <h1>Edit Profile</h1>
        <form action="/actions/user/edit_profile.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $sessionStorage->get('user_id'); ?>">

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data->Name); ?>" required>
            </div>
            <div class="form-group">
                <label for="email_address">Email Address:</label>
                <input type="email" id="email_address" name="email_address" value="<?php echo htmlspecialchars($user_data->EmailAddress); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="material-icons left">save</i>
                    Save Changes
                </button>
                <a href="change_password.php" class="btn btn-secondary">
                    <i class="material-icons left">lock</i>
                    Change Password
                </a>
            </div>
    </section>
</main>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>