<?php
function renderSimplifiedEventCard($event)
{
    ?>
    <div class="event-container">
        <div class="simplified-banner" style="background-image: url('<?php echo $event->BannerUrl; ?>');"></div>
        <div class="simplified-event-content">
            <div class="simplified-event-details">
                <div class="simplified-event-icon" style="background-image: url('<?php echo $event->IconUrl; ?>');"></div>
                <h2><?php echo $event->Name; ?></h2>
            </div>
        </div>
    </div>
    <?php
}

renderSimplifiedEventCard($event);
?>