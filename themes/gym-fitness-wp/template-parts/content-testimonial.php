`<?php 
$gymnaz_enable_testimonial_section = get_theme_mod( 'gymnaz_enable_testimonial_section', false );
$gymnaz_testimonial_title= get_theme_mod( 'gymnaz_testimonial_title','What People Say');
$gymnaz_testimonial_subtitle= get_theme_mod( 'gymnaz_testimonial_subtitle');

if($gymnaz_enable_testimonial_section == true ) {
	$gymnaz_testimonials_no        = 6;
	$gymnaz_testimonials_pages      = array();
	for( $i = 1; $i <= $gymnaz_testimonials_no; $i++ ) {
		 $gymnaz_testimonials_pages[] = get_theme_mod('gymnaz_testimonial_page'.$i);
	}
	$gymnaz_testimonials_args  = array(
	'post_type' => 'page',
	'post__in' => array_map( 'absint', $gymnaz_testimonials_pages ),
	'posts_per_page' => absint($gymnaz_testimonials_no),
	'orderby' => 'post__in'
	); 
	$gymnaz_testimonials_query = new WP_Query( $gymnaz_testimonials_args );
?>
 	<!-- ======= Testimonials Section ======= -->

<section id="testimonials" class="testimonials-5">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-lg-12">
            <div class="testimonials-content owl-carousel owl-theme text-center">
            	<?php
                $count = 0;
                while($gymnaz_testimonials_query->have_posts() && $count <= 3 ) :
                $gymnaz_testimonials_query->the_post();
                ?> 
               <div class="testi-item">
                 <div class="testi-detail">
                  <div class="client-pic">
                    <?php if(has_post_thumbnail()): ?>
	                	<?php the_post_thumbnail(); ?>
                    <i class="fa fa-quote-left"></i>
                    <?php endif; ?>
                  </div>
                  <div class="client-heading">
                    <div class="client-info">
                      <h6><?php the_title(); ?></h6>
                    </div>
                  </div>
                </div> 
                <div class="client-description">
                 <?php the_content(); ?>
                </div>   
              </div> 
              <?php
            $count = $count + 1;
            endwhile;
            wp_reset_postdata();
            ?> 
            </div>
          </div>
        </div>
      </div>
    </section>
<?php } ?>