<?php
/**
 * View: Single Performer
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/performers/single-performer.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="emagic">
<div class="ep-single-frontend-view-container ep-mb-5" id="ep_single_frontend_view_container">
    <div class="ep-box-wrap ep-view-container">

        <?php do_action( 'ep_before_performers_contant');?>

        <!-- box wrapper -->
        <div class="ep-details-info-wrap">
            <div class="ep-box-row">
                <?php
                // Load single performer image template
                ep_get_template_part( 'performers/single-performer/image', null, $args );
                ?>
                <?php
                // Load single performer image template
                ep_get_template_part( 'performers/single-performer/detail', null, $args );
                ?>
            </div>
        </div>

        <?php do_action( 'ep_after_performers_contant');?>

        <?php
        if( $args->event_args['show_events'] == 1 ) {
            // Load upcoming event template
            ep_get_template_part( 'performers/single-performer/upcoming-events', null, $args );
        }?>
    </div>
</div>
</div>