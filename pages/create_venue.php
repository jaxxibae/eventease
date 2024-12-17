<?php


$pageTitle = 'Create Venue';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';
require_once __DIR__ . '/../core/utils/countries.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$user_data = $sessionStorage->get('user_data');

if ($user_data['Role'] !== 'Event Manager') {
    header('Location: /pages/events.php');
}

$venues = [];

?>

<main>
    <section class="container">
        <h1>Create Venue</h1>
        <form action="/actions/venue/create_venue.php" method="POST">
            <div class="form-group">
                <label for="venue_name">Venue Name</label>
                <input type="text" class="form-control" id="venue_name" name="venue_name" required>
            </div>
            <div class="form-group">
                <label for="venue_city">Venue City</label>
                <input type="text" class="form-control" id="venue_city" name="venue_city" required>
            </div>
            <div class="form-group">
                <label for="venue_country">Venue Country</label>
                <select class="form-control" id="venue_country" name="venue_country" required>
                    <option value="" disabled selected>Select a venue</option>
                    <?php
                    foreach ($countries as $country) {
                        echo '<option value="' . $country . '">' . $country . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="venue_capacity">Venue Capacity</label>
                <input type="number" class="form-control" id="venue_capacity" name="venue_capacity" required min=1>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="material-icons left">save</i>
                Create Venue
            </button>
        </form>
    </section>
</main>


<?php
require_once __DIR__ . '/../core/components/footer.php';
?>