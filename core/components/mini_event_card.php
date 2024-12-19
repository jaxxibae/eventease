<?php
function renderMiniEventCard($event)
{
    ?>
    <div class="event-container" style="width: 100px; height: 80px;">
        <div class="simplified-banner" style="background-image: url('<?php echo $event->BannerUrl; ?>');"></div>
        <div class="simplified-event-content">
            <div class="simplified-event-details">
                <h2><?php echo $event->Name; ?></h2>
            </div>
        </div>
    </div>
    <?php
}

renderMiniEventCard($event);
?>