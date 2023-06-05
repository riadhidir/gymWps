<?php
/**
 * Template part for displaying section of blog content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @subpackage Gymnaz
 * @since 1.0.0
 */

$gymnaz_enable_blog_section = get_theme_mod( 'gymnaz_enable_blog_section', true );
$gymnaz_blog_cat 		= get_theme_mod( 'gymnaz_blog_cat', 'uncategorized' );
if($gymnaz_enable_blog_section == true) {
	

$gymnaz_blog_title 		= get_theme_mod( 'gymnaz_blog_title', __( 'Latest News', 'gymnaz' ) );
$gymnaz_blog_count 		= apply_filters( 'gymnaz_blog_count', 6 );

?>


<section class="blog sp-100">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="all-title">
					<?php
				if( !empty( $gymnaz_blog_title ) ) {
			?>
					<h3 class="sec-title">
						<?php echo esc_html( $gymnaz_blog_title ); ?>
					</h3>
				<?php } ?>
					<svg class="title-sep" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<path fill-rule="evenodd" d="M70.000,10.000 L70.000,8.000 L80.000,8.000 L80.000,10.000 L70.000,10.000 ZM65.000,3.000 L68.000,3.000 L68.000,16.000 L65.000,16.000 L65.000,3.000 ZM60.000,-0.000 L63.000,-0.000 L63.000,19.000 L60.000,19.000 L60.000,-0.000 ZM22.000,8.000 L58.000,8.000 L58.000,10.000 L22.000,10.000 L22.000,8.000 ZM17.000,-0.000 L20.000,-0.000 L20.000,19.000 L17.000,19.000 L17.000,-0.000 ZM12.000,3.000 L15.000,3.000 L15.000,16.000 L12.000,16.000 L12.000,3.000 ZM-0.000,8.000 L10.000,8.000 L10.000,10.000 L-0.000,10.000 L-0.000,8.000 Z"
						/>
					</svg>
				   
				</div>
			</div>
		</div>     
		<div class="blog-slider owl-carousel owl-theme owl-loaded owl-drag">
	<?php
					
					if( !empty( $gymnaz_blog_cat ) ) {
						$blog_args = array(
								'post_type' 	 => 'post',
								'category_name'	 => esc_attr( $gymnaz_blog_cat ),
								'posts_per_page' => absint( $gymnaz_blog_count ),
							);

							$blog_query = new WP_Query( $blog_args );
							if( $blog_query->have_posts() ) {
								while( $blog_query->have_posts() ) {
									$blog_query->the_post();
										?>
			<article class="blog-item blog-1">
					<?php if( has_post_thumbnail() ) { ?>
				<div class="post-img">
					<?php the_post_thumbnail(); ?>
						<div class="date">
							 <?php gymnaz_posted_on(); ?>
						</div>
				</div>
			<?php } ?>
				<ul class="post-meta">
						<?php gymnaz_posted_by(); 
					gymnaz_post_comments();?>
										</ul>
				<div class="post-content pt-4">
					<h5>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?>						</a>
					</h5>
                    <?php the_excerpt(); ?>
				</div>
			</article>
		 <?php
							}
						}
						wp_reset_postdata();
					}
				?>
		</div>

	</div>
</section>

<?php } ?>