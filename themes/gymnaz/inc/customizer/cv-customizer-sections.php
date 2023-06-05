<?php
/**
 * Gymnaz manage the Customizer sections.
 *
 * @subpackage Gymnaz
 * @since 1.0.0
 */

/**
 * Site Settings
 */
Kirki::add_section( 'gymnaz_section_site', array(
	'title'    => __( 'Site Settings', 'gymnaz' ),
	'panel'    => 'gymnaz_general_panel',
	'priority' => 40,
) );

/**
 * Hero Section
 */
Kirki::add_section( 'gymnaz_section_banner_content', array(
	'title'    => __( 'Home Banner Settings', 'gymnaz' ),
	'panel'    => 'gymnaz_frontpage_panel',
	'priority' => 5,
) );

/**
 * Blog Section
 */
Kirki::add_section( 'gymnaz_section_feature', array(
	'title'    => __( 'Home Feature Setting', 'gymnaz' ),
	'panel'    => 'gymnaz_frontpage_panel',
	'priority' => 7,
) );

/**
 * About Us Section
 */
Kirki::add_section( 'gymnaz_section_about_us', array(
	'title'    => __( 'Home About Setting', 'gymnaz' ),
	'panel'    => 'gymnaz_frontpage_panel',
	'priority' => 10,
) );

/**
 * Services Section
 */
Kirki::add_section( 'gymnaz_section_services', array(
	'title'    => __( 'Home Service Settings', 'gymnaz' ),
	'panel'    => 'gymnaz_frontpage_panel',
	'priority' => 15,
) );


/**
 * Portfolio Section
 */
Kirki::add_section( 'gymnaz_section_portfolio', array(
	'title'    => __( 'Home Portfolio Settings', 'gymnaz' ),
	'panel'    => 'gymnaz_frontpage_panel',
	'priority' => 15,
) );


/**
 * Contact Section
 */
Kirki::add_section( 'gymnaz_section_team', array(
	'title'    => __( 'Home Team Section', 'gymnaz' ),
	'panel'    => 'gymnaz_frontpage_panel',
	'priority' => 35,
) );

/**
 * Contact Section
 */
Kirki::add_section( 'gymnaz_section_testimonial', array(
	'title'    => __( 'Home Testimonial Section', 'gymnaz' ),
	'panel'    => 'gymnaz_frontpage_panel',
	'priority' => 40,
) );

/**
 * Blog Section
 */
Kirki::add_section( 'gymnaz_section_blog', array(
	'title'    => __( 'Home Blog Setting', 'gymnaz' ),
	'panel'    => 'gymnaz_frontpage_panel',
	'priority' => 45,
) );

/**
 * Blog Section
 */
Kirki::add_section( 'gymnaz_section_callout_content', array(
	'title'    => __( 'Home Callout Setting', 'gymnaz' ),
	'panel'    => 'gymnaz_frontpage_panel',
	'priority' => 47,
) );
/**
 * Footer Settings
 */
Kirki::add_section( 'gymnaz_footer_setting', array(
	'title'    => __( 'Footer Settings', 'gymnaz' ),
	'priority' => 40,
) );