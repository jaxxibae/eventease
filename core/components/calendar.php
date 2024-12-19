<?php
$month = isset($_GET['month']) ? (int) $_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');

if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

$prevMonth = $month - 1;
$nextMonth = $month + 1;

if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear = $year - 1;
} else {
    $prevYear = $year;
}

if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear = $year + 1;
} else {
    $nextYear = $year;
}
?>

<div class="calendar-nav">
    <form method="GET">
        <button type="submit" value="<?php echo $prevMonth; ?>" onclick="this.form.month.value='<?php echo $prevMonth; ?>'; this.form.year.value='<?php echo $prevYear; ?>'">&#9664;</button>
        <input type="number" name="month" value="<?php echo $month; ?>" min="1" max="12">
        <input type="number" name="year" value="<?php echo $year; ?>" min="1900" max="2100">
        <button type="submit" value="<?php echo $nextMonth; ?>" onclick="this.form.month.value='<?php echo $nextMonth; ?>'; this.form.year.value='<?php echo $nextYear; ?>'">&#9654;</button>
    </form>
</div>

<section class="calendar">
    <?php
    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    foreach ($daysOfWeek as $day) {
        echo "<div class='calendar-day'><strong>$day</strong></div>";
    }

    $daysInMonth = date('t', strtotime("$year-$month-01"));
    $firstDayOfMonth = date('w', strtotime("$year-$month-01"));

    for ($i = 0; $i < $firstDayOfMonth; $i++) {
        echo "<div class='calendar-day'></div>";
    }

    for ($day = 1; $day <= $daysInMonth; $day++) {
        echo "<div class='calendar-day' id='day_$day'>
            <span class='calendar-day-number'>$day</span>
        </div>";
    }
    ?>
</section>

<div id="mini-event-card"></div>

<?php
foreach ($attended_events as $event) {
    $eventDate = strtotime($event->EventDate);
    $eventDay = date('j', $eventDate);
    $eventMonth = date('n', $eventDate);
    $eventYear = date('Y', $eventDate);

    if ($eventMonth == $month && $eventYear == $year) {
        $event->BannerUrl = str_replace('/', '\/', $event->BannerUrl);

        // Check if event has already happened
        $currentDate = new DateTime();
        $eventDate = new DateTime($event->EventDate . ' ' . $event->EventTime);
        $isPastEvent = $eventDate < $currentDate;

        $button = <<<EOD
        <button class="attendance-dot"></button>
        EOD;

        $render_card_details = <<<EOD
            function renderCardDetails(buttonElement) {
                document.getElementById('mini-event-card').style.visibility = 'visible';
                document.getElementById('mini-event-card').style.transition = 'visibility 0s, opacity 0.5s linear;';
                document.getElementById('mini-event-card').innerHTML = `
                <div class="event-container" style="width: 200px; height: 120px;">
                    <div class="banner" style="background-image: url($event->BannerUrl); background-size: contain;">
                    </div>
                    <div class="event-content">
                        <div class="event-details">
                            <strong><p>$event->EventName</p></strong>
                            <div class="event-details-right">
                                <a href="event.php?id=$event->EventId" class="link-without-decoration">
                                    <i class="material-icons">open_in_new</i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                var buttonPosition = buttonElement.getBoundingClientRect();
                document.getElementById('mini-event-card').style.top = buttonPosition.top + 'px';
                document.getElementById('mini-event-card').style.left = buttonPosition.left + 'px';

                document.getElementById('mini-event-card').style.position = 'absolute';
                document.getElementById('mini-event-card').style.zIndex = '1000';
            }
        EOD;

        $check_past_event = <<<EOD
            if ($isPastEvent) {
                document.getElementById('day_$eventDay').style.backgroundColor = 'rgba(255, 0, 0, 0.2)';
            } else {
                document.getElementById('day_$eventDay').style.backgroundColor = 'rgba(0, 255, 0, 0.2)';
            }
        EOD;

        echo "<script>
            document.getElementById('day_$eventDay').innerHTML += '$button';

            $check_past_event

            $render_card_details

            document.addEventListener('click', function(event) {
                if (event.target.className !== 'attendance-dot') {
                    document.getElementById('mini-event-card').style.visibility = 'hidden';
                } else {
                    renderCardDetails(event.target);
                }
            });
        </script>";
    }
}
?>