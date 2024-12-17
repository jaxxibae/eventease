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
        echo "<div class='calendar-day'>$day</div>";
    }
    ?>
</section>