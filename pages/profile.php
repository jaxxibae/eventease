<?php


$pageTitle = 'Profile';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$user_data = new stdClass();

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

<style>
    .profile {
        display: flex;
        justify-content: space-between;
    }

    .profile-left {
        width: 50%;
    }

    .profile-right {
        width: 50%;
    }

    .profile-right a {
        margin-right: 10px;
    }
</style>

<main>
    <section class="container">
        <h1>Profile</h1>
        <div class="profile">
            <div class="profile-left">
                <h2>Personal Information</h2>
                <p>Name: <b><?php echo $user_data->Name; ?></b></p>
                <p>Email Address: <b><?php echo $user_data->EmailAddress; ?></b></p>
                <p>Role: <b><?php echo $user_data->Role; ?></b></p>
            </div>
            <div class="profile-right">
                <h2>Actions</h2>
                <a class="waves-effect waves-light btn" href="edit_profile.php">
                    <i class="material-icons left">edit</i>
                    Edit Profile
                </a>
                <a class="waves-effect waves-light btn" href="change_password.php">
                    <i class="material-icons left">lock</i>
                    Change Password
                </a>
            </div>
        </div>
    </section>
</main>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>