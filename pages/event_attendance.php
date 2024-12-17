<?php


$pageTitle = 'Event Attendance';
require_once __DIR__ . '/../core/components/header.php';
require_once __DIR__ . '/../core/utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

if ($sessionStorage->get('user_id') === null) {
    header('Location: index.php');
}

$event = [];

$attendances = [];

// Fetch events from the API

require_once __DIR__ . '/../core/utils/requests.php';

$client = new Requests('http://localhost:5550/actions/event/');

try {
    $response = $client->get_as_json('get_event_by_id.php?id=' . $_GET['id']);

    if ($response !== null) {
        $event = $response;
    }

    if ($event->EventOrganizerId !== $sessionStorage->get('user_id')) {
        header('Location: /pages/events.php');
    }

    $client = new Requests('http://localhost:5550/actions/attendance/');

    $response = $client->get_as_json('get_attendance_by_event_id.php?event_id=' . $_GET['id']);

    if ($response !== null) {
        $attendances = $response;
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

$currentDate = new DateTime();
$eventDate = new DateTime($event->EventDate . ' ' . $event->EventTime);
$isPastEvent = $eventDate < $currentDate;

$selected_attendance_id = null;
?>

<main>
    <section class="container">
        <?php require_once __DIR__ . '/../core/components/event_card_simplified.php'; ?>

        <div class="col-12 col-md-12">
            <h2>Attendees</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($attendances as $attendance) {
                        echo '<tr>';
                        echo '<input type="hidden" name="attendance_id" value="' . $attendance->Id . '">';
                        echo '<td>' . $attendance->UserName . '</td>';
                        echo '<td>' . $attendance->UserEmail . '</td>';
                        echo '<td>' . ($attendance->Status) . '</td>';
                        echo '<td>
                                    <a class="modal-trigger" href="#attendance_modal">
                                        <i class="material-icons left">event</i>
                                    </a>
                                </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <div id="attendance_modal" class="modal bottom-sheet">
            <!-- <form action="/actions/attendance/update_attendance.php" method="POST"> -->
                <div class="modal-content">
                    <h4>Attendance Details</h4>
                    <br>
                    <input type="hidden" name="event_id" value="<?php echo $event->Id; ?>">
                    <input type="hidden" name="attendance_id">
                    <div class="input-field s12">
                        <select name="status" id="status">
                            <option value="Pending">Pending</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <label for="status">Status</label>
                    </div>
                    <div class="input-field s12" style="display: none;">
                        <input id="motive" type="text" name="motive" autofocus>
                        <label for="motive">Motive</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Update
                        <i class="material-icons right">send</i>
                    </button>
                    <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
                </div>
            <!-- </form> -->
        </div>
    </section>
</main>

<script>
    var attendanceId = null;

    var modalButtons = document.getElementsByClassName('modal-trigger');

    for (var i in modalButtons) {
        var modalButton = modalButtons.item(i);
        modalButton.addEventListener('click', function() {
            attendanceId = this.parentElement.parentElement.querySelector('input[name="attendance_id"]').value;
            var statusElement = document.getElementById('status');
            var motiveElement = document.querySelector('input[name="motive"]');
            var attendanceIdElement = document.querySelector('input[name="attendance_id"]');

            var attendance = <?php echo json_encode($attendances); ?>.find(function(attendance) {
                return attendance.Id == attendanceId;
            });

            statusElement.value = attendance.Status;

            attendanceIdElement.value = attendance.Id;

            statusElement.dispatchEvent(new Event('change'));

            if (statusElement.value === 'Cancelled') {
                motiveElement.parentElement.style.display = 'block';
                motiveElement.value = attendance.Motive;
            } else {
                motiveElement.parentElement.style.display = 'none';
            }
        });
    }

    var statusElement = document.querySelector('select[name="status"]');

    statusElement.addEventListener('change', function() {
        var motiveElement = document.querySelector('input[name="motive"]');

        if (statusElement.value === 'Cancelled') {
            motiveElement.parentElement.style.display = 'block';
        } else {
            motiveElement.parentElement.style.display = 'none';
        }
    });

    var submitButton = document.querySelector('button[type="submit"]');

    submitButton.addEventListener('click', function() {
        var statusElement = document.querySelector('select[name="status"]');
        var motiveElement = document.querySelector('input[name="motive"]');

        if (statusElement.value === 'Cancelled' && motiveElement.value === '') {
            alert('Motive is required for cancelled attendances.');
            return;
        }

        // make AJAX request to update attendance
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/actions/attendance/update_attendance.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                location.reload();
            }
        };
        xhr.send('attendance_id=' + attendanceId + '&status=' + statusElement.value + '&motive=' + motiveElement.value + '&event_id=' + <?php echo $event->Id; ?>);

        return true;
    });
</script>

<?php
require_once __DIR__ . '/../core/components/footer.php';
?>