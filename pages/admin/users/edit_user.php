<?php


$pageTitle = 'Admin Area - Users - Edit';
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

$client = new Requests('http://localhost:5550/actions/user/');

try {
    $response = $client->get_as_json('get_user_by_id.php?id=' . $_GET['id']);

    if ($response !== null) {
        $data = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>

<main>
    <section class="container">
        <h1>User Data</h1>
        <div class="row">
            <form action="/actions/user/edit_user.php" class="col s12" method="POST">
                <div class="row">
                    <input type="text" id="id" name="id" value="<?php echo $data->Id; ?>" hidden>
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name">Name</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="email_address" name="email_address" type="email" class="validate">
                        <label for="email_address">Email Address</label>
                    </div>
                    <div class="input-field col s12">
                        <select id="role" name="role">
                            <option value="" disabled selected>Choose your option</option>
                            <option value="Admin">Admin</option>
                            <option value="Attendee">Attendee</option>
                            <option value="Event Manager">Event Manager</option>
                        </select>
                        <label for="role">Role</label>
                    </div>
                    <div class="input-field col s12">
                        <p>
                            <label>
                                <input type="checkbox" id="is_active" name="is_active" />
                                <span>Is Active?</span>
                            </label>
                        </p>
                    </div>
                </div>
        </div>
        <div class="row">
            <button class="btn waves-effect waves-light" type="submit" name="action">Edit
                <i class="material-icons left">edit</i>
            </button>
            </form>
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

    document.getElementById('name').value = '<?php echo $data->Name; ?>';
    document.getElementById('email_address').value = '<?php echo $data->EmailAddress; ?>';
    document.getElementById('role').value = '<?php echo $data->Role; ?>';
    document.getElementById('is_active').checked = <?php echo $data->Active ? 'true' : 'false'; ?>;
</script>