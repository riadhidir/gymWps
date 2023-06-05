<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @subpackage gymnaz
 * @since gymnaz
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#content">
<?php esc_html_e( 'Skip to content', 'gymnaz' ); ?></a>
<?php
	do_action( 'gymnaz_before_header' );
	do_action( 'gymnaz_header' );
?>
<div id="content"></div>

<?php
if (!is_home() && is_front_page()) {
?>
		<div id="cv-fullscreen" class="site-content">
<?php
		do_action( 'gymnaz_frontpage_sections' );
} else {
		do_action( 'gymnaz_innerpage_header' );

}
?>