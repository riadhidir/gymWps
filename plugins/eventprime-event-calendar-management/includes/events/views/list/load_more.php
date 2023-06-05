<?php
/**
 * View: Event List - Load More
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/performers/list/load_more.php
 *
 */
?>
<?php
if( isset( $args->events->max_num_pages ) && $args->events->max_num_pages > 1 ) {
    $show_no_of_events_card = ep_get_global_settings( 'show_no_of_events_card' );
    if( 'custom' == $show_no_of_events_card ) {
        $show_no_of_events_card = ep_get_global_settings( 'card_view_custom_value' );
    }
    if( ! empty( $args->events->posts ) && count( $args->events->posts ) >= $show_no_of_events_card ) {?>
        <div class="ep-events-load-more ep-frontend-loadmore ep-box-w-100 ep-my-4 ep-text-center">
            <input type="hidden" id="ep-events-limit" value="<?php echo esc_attr( $args->limit );?>"/>
            <input type="hidden" id="ep-events-paged" value="<?php echo esc_attr( $args->paged );?>"/>
            <input type="hidden" id="ep-events-types-ids" value="<?php echo esc_attr( isset( $args->types_ids ) ? implode( ',', $args->types_ids ) : '' ); ?>"/>
            <input type="hidden" id="ep-events-venues-ids" value="<?php echo esc_attr( isset( $args->venue_ids ) ? implode( ',', $args->venue_ids ) : '' ); ?>"/>
            <button data-max="<?php echo esc_attr( $args->events->max_num_pages );?>" id="ep-loadmore-events" class="ep-btn ep-btn-outline-primary">
                <span class="ep-spinner ep-spinner-border-sm ep-mr-1"></span>
                <?php echo esc_html__( 'Load more', 'eventprime-event-calendar-management' );?>
            </button>
        </div><?php
    }
}?>

<script>
    jQuery(".ep-box-card-item").hover(
        function() {
            jQuery(this).addClass("ep-shadow");
            jQuery(this).find(".ep-box-card-thumb img").css("transform", "scale(1.1,1.1)");
        },
        function() {
            jQuery(this).removeClass("ep-shadow");
            jQuery(this).find(".ep-box-card-thumb img").css("transform", "scale(1,1)");
        }
    );
    /* jQuery(document).ready(function() {
        var epColorRgbValue = jQuery('.emagic, #primary.content-area .entry-content').find('a').css('color');

        var epColorRgb = epColorRgbValue;
        var avoid = "rgb";
        var eprgbRemover = epColorRgb.replace(avoid, '');
        var emColor = eprgbRemover.substring(eprgbRemover.indexOf('(') + 1, eprgbRemover.indexOf(')'));
        jQuery(':root').css('--themeColor', emColor);
    }); */
</script>
