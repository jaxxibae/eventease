<?php
function renderOngoingEventCard($event)
{
    ?>
    <div class="event-container">
        <div class="banner" style="background-image: url('<?php echo $event->BannerUrl; ?>');"></div>
        <div class="event-content">
            <div class="event-icon" style="background-image: url('<?php echo $event->IconUrl; ?>');"></div>
            <div class="event-details">
                <h2><?php echo $event->Name; ?></h2>
                <p>
                    <i class="material-icons">event</i>
                    <?php
                    $date = new DateTime($event->EventDate);
                    echo $date->format('l, F j, Y');
                    ?>
                </p>
                <p>
                    <i class="material-icons">schedule</i>
                    <?php
                    $time = new DateTime($event->EventTime);
                    echo $time->format('H:i');
                    ?>
                </p>
                <p>
                    <i class="material-icons">location_on</i>
                    <?php echo $event->VenueName; ?> (<?php echo $event->VenueLocation; ?>)
                </p>
            </div>
            <div class="event-details-right">
                <a href="event.php?id=<?php echo $event->Id; ?>" class="link-without-decoration">
                    <i class="material-icons">open_in_new</i>
                </a>
            </div>
        </div>
    </div>
    <?php
}

foreach ($ongoing_events as $event) {
    renderOngoingEventCard($event);
}
?>