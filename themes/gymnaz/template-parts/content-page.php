<?php
/**
 * Template part for displaying single posts content.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @subpackage Gymnaz
 * @since 1.0.0
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-detail">
		<?php gymnaz_post_thumbnail(); ?>
		
			<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'gymnaz' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
								get_the_title()

			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'gymnaz' ),
				'after'  => '</div>',
			) );
			?>	
				<?php if (has_tag()) : ?>
		<div class="post-tags mt-4">
			<span class="text-capitalize mr-2 c-black">
				<i class="fa fa-tags"></i><?php echo __('Tags :','gymnaz'); ?></span>
			<?php the_tags('&nbsp;'); ?>
		</div>
		<?php endif; ?> 
	</div>
</div>