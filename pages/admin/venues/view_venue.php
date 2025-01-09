<?php


$pageTitle = 'Admin Area - Venues - View';
require_once __DIR__ . '/../../../core/components/header.php';
require_once __DIR__ . '/../../../core/utils/session_storage/native_session_storage.php';
require_once __DIR__ . '/../../../core/utils/countries.php';

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

$client = new Requests('http://localhost:5550/actions/venue/');

try {
    $response = $client->get_as_json('get_venue_by_id.php?id=' . $_GET['id']);

    if ($response !== null) {
        $data = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>

<main>
    <section class="container">
        <h1>Venue Data</h1>
        <div class="row">
            <div class="form-group">
                <label for="venue_name">Name</label>
                <input type="text" class="form-control" id="venue_name" name="venue_name" disabled>
            </div>
            <div class="form-group">
                <label for="venue_city">City</label>
                <input type="text" class="form-control" id="venue_city" name="venue_city" disabled>
            </div>
            <div class="form-group">
                <label for="venue_country">Country</label>
                <select class="form-control" id="venue_country" name="venue_country" disabled>
                    <option value="" disabled selected>Select a venue</option>
                    <?php
                    foreach ($countries as $country) {
                        echo '<option value="' . $country . '">' . $country . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="venue_capacity">Capacity</label>
                <input type="number" class="form-control" id="venue_capacity" name="venue_capacity" disabled min=1>
            </div>
        </div>
        <div class="row">
            <a href="/pages/admin/venues/edit_venue.php?id=<?php echo $_GET['id']; ?>" class="waves-effect waves-light btn"><i class="material-icons left">edit</i>Edit</a>
            <a href="/pages/admin/venues/delete_venue.php?id=<?php echo $_GET['id']; ?>" class="waves-effect waves-light btn red"><i class="material-icons left">delete</i>Delete</a>
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

    var venueLocation = '<?php echo $data->Location ?>'

    var venueCity = venueLocation.split(',')[0].trim();
    var venueCountry = venueLocation.split(',')[1].trim();

    document.getElementById('venue_name').value = '<?php echo $data->Name; ?>';
    document.getElementById('venue_city').value = venueCity;
    document.getElementById('venue_country').value = venueCountry;
    document.getElementById('venue_capacity').value = '<?php echo $data->Capacity; ?>';
</script>