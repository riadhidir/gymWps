<?php
/**
 * Gymnaz manage the Customizer options of general panel.
 *
 * @subpackage Gymnaz
 * @since 1.0.0
 */
Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'checkbox',
		'settings'    => 'gymnaz_home_posts',
		'label'       => esc_attr__( 'Checked to hide latest posts in homepage.', 'gymnaz' ),
		'section'     => 'static_front_page',
		'default'     => true,
	)
);

// Color Picker field for Primary Color
Kirki::add_field( 
	'gymnaz_config', array(
		'type'        => 'color',
		'settings'    => 'gymnaz_theme_color',
		'label'       => esc_html__( 'Primary Color', 'gymnaz' ),
		'section'     => 'colors',
		'default'     => '#ff5164',
	)
);