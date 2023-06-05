<?php
/**
 * Recommended plugins
 *
 * @package gymnaz
 */

if ( ! function_exists( 'gymnaz_recommended_plugins' ) ) :

    /**
     * Recommend plugins.
     *
     * @since 1.0.0
     */
    function gymnaz_recommended_plugins() {

        $plugins = array(
             
			 array(
                'name'     => esc_html__( 'Trainner Schedule', 'gymnaz' ),
                'slug'     => 'eventprime-event-calendar-management',
                'required' => false,
            ),
			 array(
                'name'     => esc_html__( 'Image Slider', 'gymnaz' ),
                'slug'     => 'image-slider-slideshow',
                'required' => false,
            )
        );
		 
		 
        tgmpa( $plugins );

    }

endif;

add_action( 'tgmpa_register', 'gymnaz_recommended_plugins' );
