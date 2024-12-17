<?php


$pageTitle = 'Change Password';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}
?>

<main>
    <section class="container">
        <h1>Change Password</h1>
        <form action="actions/user/change_password.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $sessionStorage->get('user_id'); ?>">

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>

            <button class="btn waves-effect waves-light" type="submit" name="action">
                <i class="material-icons left">lock</i>
                Change Password
            </button>
    </section>
</main>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>