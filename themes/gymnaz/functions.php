<?php
/**
 * Gymnaz functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @subpackage Gymnaz
 * @since Gymnaz 1.0
 */

/**
 * Gymnaz only works in WordPress 4.7 or later.
 */

if ( ! function_exists( 'gymnaz_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function gymnaz_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Gymnaz, use a find and replace
		 * to change 'gymnaz' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'gymnaz');
        
		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
		
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// This theme uses wp_nav_menu() in single locations.
		add_theme_support( 'nav-menus' );
		register_nav_menu('primary', esc_html__( 'Primary Menu', 'gymnaz' ) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add the custom background prperty
		add_theme_support( 'custom-background', apply_filters( 'gymnaz_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add supportive refresh widgets 
		add_theme_support( 'customize-selective-refresh-widgets' );
		
		// Add default posts and comments RSS feed links 
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		
		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'gymnaz_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gymnaz_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'gymnaz_content_width', 640 );
}
add_action( 'after_setup_theme', 'gymnaz_content_width', 0 );

/**
 * Set the theme version, based on theme stylesheet.
 *
 * @global string $gymnaz_theme_version
 */
function gymnaz_theme_version_info() {
	$gymnaz_theme_info = wp_get_theme();
	$GLOBALS['gymnaz_theme_version'] = $gymnaz_theme_info->get( 'Version' );
}
add_action( 'after_setup_theme', 'gymnaz_theme_version_info', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function gymnaz_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'gymnaz' ),
		'id'            => 'blog-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'gymnaz' ),
		'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s clearfix">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="title-sep2 mb-30">',
		'after_title'   => '</h4>',
	) );


	     register_sidebar(array(
		'name' => esc_html__( 'Footer Widget Area', "gymnaz"),
		'id' => 'footer-widget-area',
		'description' => esc_html__( 'The footer widget area', "gymnaz"),
		'before_widget' => '<div class="%2$s footer-widget col-md-3 col-sm-6 col-xs-12">',
		'after_widget' => '</div>',
		'before_title' => '<div class="foot-title"><h4>',
		'after_title' => '</h4></div>',
	));	
}
add_action( 'widgets_init', 'gymnaz_widgets_init' );

/**
 * Customizer additional settings.
 */
require_once( trailingslashit( get_template_directory() ) . '/inc/custom-addition/class-customize.php' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * File to manage the custom body classes
 */
require get_template_directory() . '/inc/template-css-class.php';
/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Add feature in Customizer  
 */
require get_template_directory() . '/inc/customizer/cv-customizer.php';


/**
 * Custom Hooks defined 
 */
require get_template_directory() . '/inc/custom-hooks/cv-custom-hooks.php';
require get_template_directory() . '/inc/custom-hooks/footer-hooks.php';
require get_template_directory() . '/inc/custom-hooks/header-hooks.php';



/**
 * Breadcrumbs files added 
 */

	require get_template_directory() . '/inc/breadcrumbs.php';
	
/**
 * plugin Recommendations.
 */
require_once  get_template_directory()  . '/inc/tgm/class-tgm-plugin-activation.php';
require get_template_directory(). '/inc/tgm/hook-tgm.php';
 
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package gymnaz
 */

/**
 * Header fearures expanded 
 */
function gymnaz_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'gymnaz_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/assets/images/header.jpg',
		'default-text-color'     => '000000',
		'width'                  => 1920,
		'height'                 => 850,
		'flex-height'            => true,
		'wp-head-callback'       => 'gymnaz_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'gymnaz_custom_header_setup' );

if ( ! function_exists( 'gymnaz_header_style' ) ) :

	function gymnaz_header_style() {
		$header_text_color = get_header_textcolor();

		?>
		<style type="text/css">
			<?php
				//Check if user has defined any header image.
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

 

function gymnaz_comparepage_css($hook) {
  if ( 'appearance_page_gymnaz-info' != $hook ) {
    return;
  }
  wp_enqueue_style( 'gymnaz-custom-style', get_template_directory_uri() . '/assets/css/compare.css' );
}
add_action( 'admin_enqueue_scripts', 'gymnaz_comparepage_css' );

/**
 * Compare page content
 */

add_action('admin_menu', 'gymnaz_themepage');
function gymnaz_themepage(){
  $theme_info = add_theme_page( __('Gymnaz','gymnaz'), __('Gymnaz','gymnaz'), 'manage_options', 'gymnaz-info.php', 'gymnaz_info_page' );
}

function gymnaz_info_page() {
  $user = wp_get_current_user();
  ?>
  <div class="wrap about-wrap one-pageily-add-css">
    <div>
      <h1>
        <?php echo __('Welcome to Gymnaz!','gymnaz'); ?>
      </h1>

      <div class="feature-section three-col">
        <div class="col">
          <div class="widgets-holder-wrap">
            <h3><?php echo __("Recommended Plugins", "gymnaz"); ?></h3>
            <p><?php echo __("Please install recommended plugins for better use of theme. It will help you to make website more useful", "gymnaz"); ?></p>
            <p><a target="blank" href="<?php echo esc_url(admin_url('/themes.php?page=tgmpa-install-plugins&plugin_status=activate'), 'gymnaz'); ?>" class="button button-primary">
              <?php echo __("Install Plugins", "gymnaz"); ?>
            </a></p>
          </div>
        </div>
        <div class="col">
          <div class="widgets-holder-wrap">
            <h3><?php echo __("Review Theme", "gymnaz"); ?></h3>
            <p><?php echo __("Nothing motivates us more than feedback, are you are enjoying Gymnaz? We would love to hear what you think!.", "gymnaz"); ?></p>
            <p><a target="blank" href="<?php echo esc_url('https://wordpress.org/support/theme/gymnaz/reviews/', 'gymnaz'); ?>" class="button button-primary">
              <?php echo __("Submit A Review", "gymnaz"); ?>
            </a></p>
          </div>
        </div>
         <div class="col">
          <div class="widgets-holder-wrap">
            <h3><?php echo __("Contact Support", "gymnaz"); ?></h3>
            <p><?php echo __("Getting started with a new theme can be difficult, if you have issues with Gymnaz then throw us an email.", "gymnaz"); ?></p>
            <p><a target="blank" href="<?php echo esc_url('http://testerwp.com/contact/', 'gymnaz'); ?>" class="button button-primary">
              <?php echo __("Contact Support", "gymnaz"); ?>
            </a></p>
          </div>
        </div>
      </div>
	  
	  <h2><?php echo __("Free Vs Premium","gymnaz"); ?></h2>
    <div class="one-pageily-button-container">
      <a target="blank" href="<?php echo esc_url('https://testerwp.com/product/gymnaz-pro/', 'gymnaz'); ?>" class="button button-primary">
        <?php echo __("Check Premium", "gymnaz"); ?>
      </a>
      <a target="blank" href="<?php echo esc_url('https://www.testerwp.com/lp/gymnaz-preview/', 'gymnaz'); ?>" class="button button-primary">
        <?php echo __("View Theme Demo", "gymnaz"); ?>
      </a>
    </div>


    <table class="wp-list-table widefat">
      <thead>
        <tr>
          <th><strong><?php echo __("Theme Feature", "gymnaz"); ?></strong></th>
          <th><strong><?php echo __("Basic Version", "gymnaz"); ?></strong></th>
          <th><strong><?php echo __("Premium Version", "gymnaz"); ?></strong></th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td><?php echo __("Header Background Color  ", "gymnaz"); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Custom Header Logo & Text ", "gymnaz"); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Custom Header Button Text & Link", "gymnaz"); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Custom Header Button Colors", "gymnaz"); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Custom Navigation Text Color", "gymnaz"); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Custom Background Image", "gymnaz"); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Hide Header Text", "gymnaz"); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>


        <tr>
          <td><?php echo __("Premium Support", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Unlimited Colors", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Google Fonts", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Website Builder", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Blog Layout", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Service Page", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Team Page", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Portfolio Page", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Page Layout", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Sidebar", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Home Section", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("SEO Friendly", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Ultimate Widgets", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Footer Featured Cusomization", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Contact Page", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Customize Footer Colors", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Mega Menu", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr> 
        <tr>
          <td><?php echo __("Pricing Page", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Ultimate Shorcodes", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Importable Demo Content", "gymnaz"); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "gymnaz"); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "gymnaz"); ?>" /></span></td>
        </tr>

      </tbody>
    </table>
    <div class="one-pageily-button-container">
      <a target="blank" href="<?php echo esc_url('https://testerwp.com/product/gymnaz-pro/', 'gymnaz'); ?>" class="button button-primary">
        <?php echo __("GO PREMIUM", "gymnaz"); ?>
      </a>
    </div>
	  
    </div>
    <hr>
 
  </div>
  <?php
}
//		
if ( is_admin() ) {
require get_template_directory() . '/inc/theme-notice.php';
}