<?php
$gymnaz_enable_blog_section = get_theme_mod( 'gymnaz_enable_blog_section', true );
$gymnaz_blog_cat 		= get_theme_mod( 'gymnaz_blog_cat', 'uncategorized' );
if($gymnaz_enable_blog_section == true) 
{
	$gymnaz_blog_title 	= get_theme_mod( 'gymnaz_blog_title', esc_html__( 'Our News & Blogs','gym-fitness-wp'));
	$gymnaz_blog_subtitle 	= get_theme_mod( 'gymnaz_blog_subtitle', esc_html__( 'Latest News','gym-fitness-wp') );
	$gymnaz_rm_button_label 	= get_theme_mod( 'gymnaz_rm_button_label', esc_html__( 'Read More','gym-fitness-wp'));
	$gymnaz_blog_count 	 = apply_filters( 'gymnaz_blog_count', 3 );
?> 	
	<!-- blog start--> 
    <section class="blog-sec">
        <div class="container">
          <div class="section-heading">
            <span class="section-title"><?php echo esc_html($gymnaz_blog_title);?></span>
          </div>
            <div class="row">
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


                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <article class="blog-item">
                      <div class="post-img">
                         <?php if(has_post_thumbnail()): ?>
	                		<?php the_post_thumbnail(); ?>
                   		 <?php endif; ?>
                      </div>
                      <div class="post-inner">
                        <div class="inner-post">
                          <div class="post-cat">
                            <div class="posted-in">
                             <?php  gymnaz_posted_by(); ?>
                            </div>
                          </div>                
                          <div class="entry-header">
                            <h5 class="entry-title">
                              <a class="title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h5>
                          </div>
                        </div>
                        <div class="entry-meta">
                          <span class="posted-on">
                            <?php echo esc_html(get_the_date()); ?>
                          </span>
                          <span class="comment-num"><?php _e(' / ','gym-fitness-wp'); ?><?php comments_number(); ?></span>
                            <a href="<?php the_permalink(); ?>" class="btn-details"><i class="fa fa-arrow-right"></i></a>
                        </div>
                      </div>
                    </article>
                  </div>
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