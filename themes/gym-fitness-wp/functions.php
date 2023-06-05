<?php
/**
 * Child Theme functions and definitions.
 * This theme is a child theme for gymnaz.
 *
 * @subpackage gym fitness
 * @author  wptexture https://testerwp.com/
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 */

/**
 * Theme functions and definitions.
 */
 
function gym_fitness_custom_excerpt_length( $length ) {
	if (is_admin()) {
            return $length;
        }
    return 20;
}

add_filter( 'excerpt_length', 'gym_fitness_custom_excerpt_length', 999 );

add_action( 'wp_enqueue_scripts', 'gym_fitness_child_css',25);
function gym_fitness_child_css() {
	wp_enqueue_style( 'gym-fitness-parent-theme-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'gym-fitness-child-style',get_stylesheet_directory_uri() . '/child-css/child.css');
    wp_enqueue_style( 'gym-fitness-child-color-style',get_stylesheet_directory_uri() . '/child-css/color.css');
	wp_enqueue_script( 'gym-fitness-custom-script', get_stylesheet_directory_uri() . '/child-js/custom-script.js', array(), false, true);
	
	wp_enqueue_style( 'gym-fitness-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap' ); 
}


function gymwt_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'gymnaz_custom_header_args', array(
		'default-image'          => get_stylesheet_directory_uri() . '/img/header.jpg',
		'default-text-color'     => 'fff',
		'width'                  => 1920,
		'height'                 => 850,
		'flex-height'            => true,
		'wp-head-callback'       => 'mini_gymnaz_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'gymwt_custom_header_setup' );

if ( ! function_exists( 'mini_gymnaz_header_style' ) ) :

	function mini_gymnaz_header_style() {
		$header_text_color = get_header_textcolor();

		?>
		<style type="text/css">
			<?php
				if ( get_header_image() ) :
			?>
			.page-banner
			  {
				background-image:url('<?php header_image(); ?>');
			  }
		
			.site-title,.site-description
			 {
			color: #<?php echo esc_attr($header_text_color); ?>;
			
			  }

			<?php endif; ?>	
		</style>
		<?php
	}
endif;