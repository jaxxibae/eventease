<?php
require_once __DIR__ . '/../utils/session_storage/native_session_storage.php';

use Utils\Session_storage\NativeSessionStorage;

// Create a new session storage instance
$sessionStorage = new NativeSessionStorage();

$user_data = $sessionStorage->get('user_data');

$role = $user_data['Role'];
?>

<nav>
  <div class="nav-wrapper">
    <a href="/index.php" class="brand-logo">EventEase</a>
    <ul id="nav-mobile" class="right hide-on-med-and-down">
      <?php
      if ($role === 'Event Manager') {
        echo '<li><a href="/pages/create_event.php">Create Event</a></li>';
        echo '<li><a href="/pages/my_events.php">My Events</a></li>';
      } if ($role === 'Admin') {
        echo '<li><a href="/pages/admin/index.php">Admin Area</a></li>';
      }
      echo '<li><a href="/pages/events_attended.php">Events Attended</a>';
      ?>
      <li><a href="/pages/events.php">All Events</a></li>
      <li><a href="/pages/my_calendar.php">My Calendar</a></li>
      <li><a href="/pages/profile.php">Profile</a></li>
      <li><a href="/pages/logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<?php
$current_page = basename($_SERVER['PHP_SELF']);

echo '<script>
      let lists = document.querySelectorAll("li");
      
      lists.forEach(list => {
        // Check if the list item is the one we want to highlight
        if (list.getElementsByTagName("a")[0].getAttribute("href") === "' . $current_page . '") {
          list.classList.add("active");
        }
      });
    </script>';
?>