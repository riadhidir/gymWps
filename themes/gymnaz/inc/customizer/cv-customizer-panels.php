<?php
/**
 * Gymnaz manage the Customizer panels.
 *
 * @subpackage Gymnaz
 * @since 1.0.0
 */

/**
 * General Settings Panel
 */
Kirki::add_panel( 'gymnaz_general_panel', array(
	'priority' => 10,
	'title'    => __( 'General Settings', 'gymnaz' ),
) );

/**
 * Header Settings Panel
 */
Kirki::add_panel( 'gymnaz_header_panel', array(
	'priority' => 15,
	'title'    => __( 'Header Options', 'gymnaz' ),
) );

/**
 * Frontpage Settings Panel
 */
Kirki::add_panel( 'gymnaz_frontpage_panel', array(
	'priority' => 20,
	'title'    => __( 'Gymnaz HomePage', 'gymnaz' ),
) );

/**
 * Design Settings Panel
 */
Kirki::add_panel( 'gymnaz_design_panel', array(
	'priority' => 25,
	'title'    => esc_html__( 'Design Settings', 'gymnaz' ),
) );