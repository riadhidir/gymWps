<?php
/**
 * Gymnaz manage the Customizer options of frontpage panel.
 *
 * @subpackage Gymnaz
 * @since 1.0.0
 */

// Toggle field for Enable/Disable banner content
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_banner_section',
		'label'    => __( 'Enable Home Page Banner', 'gymnaz' ),
		'section'  => 'gymnaz_section_banner_content',
		'default'  => '1',
		'priority' => 5,
	)
);
Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'image',
		'settings'    => 'gymnaz_banner_image',
		'label'       => esc_attr__( 'Home Banner Image', 'gymnaz' ),
		'section'     => 'gymnaz_section_banner_content',
		'default'     => esc_url(  get_template_directory_uri() . '/assets/images/banner.jpg' ),
		'priority' 	  => 10,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_banner_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for banner title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_banner_title',
		'label'    => __( 'Banner Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_banner_content',
        'default'  => '',
		'priority' => 15,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_banner_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Textarea field for banner content
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'textarea',
		'settings' => 'gymnaz_banner_content',
		'label'    => __( 'Banner Text', 'gymnaz' ),
		'section'  => 'gymnaz_section_banner_content',
        'default'  => '',
		'priority' => 20,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_banner_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for banner content button label
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_banner_button_label1',
		'label'    => __( 'Banner Button Text', 'gymnaz' ),
		'section'  => 'gymnaz_section_banner_content',
		'default'  => '',
		'priority' => 25,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_banner_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Link field for banner content button link
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_banner_button_link1',
		'label'    => __( 'Banner Button URL', 'gymnaz' ),
		'section'  => 'gymnaz_section_banner_content',
		'default'  => '',
		'priority' => 30,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_banner_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);


Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_feature_section',
		'label'    => __( 'Enable Home Feature', 'gymnaz' ),
		'section'  => 'gymnaz_section_feature',
		'default'  => '0',
		'priority' => 5,
	)
);


// Text field for Feature section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'radio',
		'settings' => 'gymnaz_feature_column',
		'label'    => __( 'Column Layout', 'gymnaz' ),
		'section'  => 'gymnaz_section_feature',
		'default'  => '2',	
		'priority' => '',
		'choices'  => array(
					'3col' => esc_html__('2 Column Layout', 'gymnaz'),
		            '2' => esc_html__('4 Column Layout', 'gymnaz'),
					'4col' => esc_html__('3 Column Layout', 'gymnaz'),
		        )
	)
);

for($k=1;$k<=4;$k++){
	Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'gymnaz_feature_page'.$k,
		'label'       => 'Select Feature Page'.$k,
		'section'     => 'gymnaz_section_feature',
		'default'     => 0,
		'priority'    => 11,
		
	)
);
}


// Toggle field for Enable/Disable About Us Section
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_about_us_section',
		'label'    => __( 'Enable Home About Area', 'gymnaz' ),
		'section'  => 'gymnaz_section_about_us',
		'default'  => '0',
		'priority' => 5,
	)
);

// Dropdown pages field for about us section


	Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'gymnaz_about_page',
		'label'       => __( 'Select Page', 'gymnaz' ),
		'section'     => 'gymnaz_section_about_us',
		'default'     => 0,
		'priority'    => 10,
		
	)
);

// Text field for callout content button label
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_about_button_label1',
		'label'    => __( 'Read More Button Text', 'gymnaz' ),
		'default'  => '',
		'section'  => 'gymnaz_section_about_us',
		'priority' => 25,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_about_us_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Link field for callout content button link
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_about_button_link1',
		'label'    => __( 'Read More Button URL', 'gymnaz' ),
		'default'  => '',
		'section'  => 'gymnaz_section_about_us',
		'priority' => 30,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_about_us_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Toggle field for Enable/Disable About Us Section
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_service_section',
		'label'    => __( 'Enable Home Service Area', 'gymnaz' ),
		'section'  => 'gymnaz_section_services',
		'default'  => '0',
		'priority' => 5,
	)
);

// Text field for Service section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_service_title',
		'label'    => __( 'Service Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_services',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_service_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for Service section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_service_subtitle',
		'label'    => __( 'Service Sub Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_services',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_service_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

for($i=1;$i<=8;$i++){
	Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'gymnaz_service_page'.$i,
		'label'       => 'Select Service Page'.$i,
		'section'     => 'gymnaz_section_services',
		'default'     => 0,
		'priority'    => 11,
		
	)
);
}

// Text field for Service section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'radio',
		'settings' => 'gymnaz_service_column',
		'label'    => __( 'Column Layout', 'gymnaz' ),
		'section'  => 'gymnaz_section_services',
		'default'  => '4',	
		'priority' => 5,
		'choices'  => array(
		            '3' => esc_html__('4 Column Layout', 'gymnaz'),
					'4' => esc_html__('3 Column Layout', 'gymnaz'),
		        )
	)
);

// Toggle field for Enable/Disable Portfolio Section
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_portfolio_section',
		'label'    => __( 'Enable Home Portfolio Area', 'gymnaz' ),
		'section'  => 'gymnaz_section_portfolio',
		'default'  => '0',
		'priority' => 5,
	)
);

// Text field for Service section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_portfolio_title',
		'label'    => __( 'Portfolio Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_portfolio',
		'default'  =>'',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_portfolio_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for Service section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_portfolio_subtitle',
		'label'    => __( 'Portfolio Sub Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_portfolio',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_portfolio_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

for($k=1;$k<=8;$k++){
	Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'gymnaz_portfolio_page'.$k,
		'label'       =>  'Select Portfolio Page'.$k,
		'section'     => 'gymnaz_section_portfolio',
		'default'     => 0,
		'priority'    => 11,
		
	)
);
}

// Toggle field for Enable/Disable Testimonial Section
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_team_section',
		'label'    => __( 'Enable Home Team Area', 'gymnaz' ),
		'section'  => 'gymnaz_section_team',
		'default'  => '0',
		'priority' => 5,
	)
);


// Text field for Team section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_team_title',
		'label'    => __( 'Team Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_team',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_team_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for Team section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_team_subtitle',
		'label'    => __( 'Team Sub Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_team',
		'default'  => '',	
		'priority' => 6,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_team_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

for($k=1;$k<=6;$k++){
	Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'gymnaz_team_page'.$k,
		'label'       => 'Select Team Page'.$k,
		'section'     => 'gymnaz_section_team',
		'default'     => 0,
		'priority'    => 11,
		
	)
);
}

Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_testimonial_section',
		'label'    => __( 'Enable Home Testimonial Area', 'gymnaz' ),
		'section'  => 'gymnaz_section_testimonial',
		'default'  => '0',
		'priority' => 5,
	)
);

// Text field for Testimonial section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_testimonial_title',
		'label'    => __( 'Testimonial Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_testimonial',
		'default'  => '',	
		'priority' => 5,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_testimonial_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for Testimonial section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_testimonial_subtitle',
		'label'    => __( 'Testimonial Sub Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_testimonial',
		'default'  => '',	
		'priority' => 6,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_testimonial_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for Testimonial section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'radio',
		'settings' => 'gymnaz_testimonial_column',
		'label'    => __( 'Column Layout', 'gymnaz' ),
		'section'  => 'gymnaz_section_testimonial',
		'default'  => '4',	
		'priority' => 5,
		'choices'  => array(
		            '3' => esc_html__('4 Column Layout', 'gymnaz'),
					'4' => esc_html__('3 Column Layout', 'gymnaz'),
		        )
	)
);

for($k=1;$k<=4;$k++){
	Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'dropdown-pages',
		'settings'    => 'gymnaz_testimonial_page'.$k,
		'label'       => 'Select Testimonial Page'.$k,
		'section'     => 'gymnaz_section_testimonial',
		'default'     => 0,
		'priority'    => 11,
		
	)
);
}

Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_blog_section',
		'label'    => __( 'Enable Home Blog Area', 'gymnaz' ),
		'section'  => 'gymnaz_section_blog',
		'default'  => '1',
		'priority' => 5,
	)
);


Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_blog_section',
		'label'    => __( 'Enable Home Blog Area', 'gymnaz' ),
		'section'  => 'gymnaz_section_blog',
		'default'  => '1',
		'priority' => 5,
	)
);

// Text field for blog section title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_blog_title',
		'label'    => __( 'Top Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_blog',
		'default'  => '',	
		'priority' => 10,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_blog_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Select field for blog section categories.
Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'select',
		'settings'    => 'gymnaz_blog_cat',
		'label'       => esc_attr__( 'Select Category', 'gymnaz' ),
		'section'     => 'gymnaz_section_blog',
		'default'     => 'Uncategorized',
		'priority'    => 15,
		'choices'     => gymnaz_select_categories_list(),
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_blog_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);



// Toggle field for Enable/Disable callout content
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'toggle',
		'settings' => 'gymnaz_enable_callout_section',
		'label'    => __( 'Enable Home Page Callout', 'gymnaz' ),
		'section'  => 'gymnaz_section_callout_content',
		'default'  => '0',
		'priority' => 5,
	)
);
Kirki::add_field(
	'gymnaz_config', array(
		'type'        => 'image',
		'settings'    => 'gymnaz_callout_image',
		'label'       => esc_attr__( 'Home Callout Image', 'gymnaz' ),
		'description' => __('Use Png Image','gymnaz'),
		'section'     => 'gymnaz_section_callout_content',
		'default'     => '',
		'priority' 	  => 10,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for callout title
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_callout_title',
		'label'    => __( 'Callout Title', 'gymnaz' ),
		'section'  => 'gymnaz_section_callout_content',
        'default'  => '',
		'priority' => 15,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Textarea field for callout content
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'textarea',
		'settings' => 'gymnaz_callout_content',
		'label'    => __( 'Callout Text', 'gymnaz' ),
		'section'  => 'gymnaz_section_callout_content',
        'default'  => '',
		'priority' => 20,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Text field for callout content button label
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_callout_button_label1',
		'label'    => __( 'Callout Button Text', 'gymnaz' ),
		'default'  => '',
		'section'  => 'gymnaz_section_callout_content',
		'priority' => 25,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);

// Link field for callout content button link
Kirki::add_field(
	'gymnaz_config', array(
		'type'     => 'text',
		'settings' => 'gymnaz_callout_button_link1',
		'label'    => __( 'callout Button URL', 'gymnaz' ),
		'default'  => '',
		'section'  => 'gymnaz_section_callout_content',
		'priority' => 30,
		'active_callback' => array(
			array(
				'setting'  => 'gymnaz_enable_callout_section',
				'value'    => true,
				'operator' => 'in',
			),
		)
	)
);