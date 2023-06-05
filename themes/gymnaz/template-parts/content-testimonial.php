<?php 
$gymnaz_enable_testimonial_section = get_theme_mod( 'gymnaz_enable_testimonial_section', false );
$gymnaz_testimonial_title= get_theme_mod( 'gymnaz_testimonial_title','What People Say');
$gymnaz_testimonial_subtitle= get_theme_mod( 'gymnaz_testimonial_subtitle');
$gymnaz_testimonial_column = get_theme_mod( 'gymnaz_testimonial_column','4');

if($gymnaz_enable_testimonial_section == true ) {
   

        $gymnaz_testimonials_no        = 4;
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
  <section class="testimonial-one bg-theme sp-100">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <?php if(!empty($gymnaz_testimonial_title)) { ?>
          <div class="all-title white2">
            <h3 class="sec-title">
              <?php echo esc_html($gymnaz_testimonial_title); ?>
            </h3>
            <svg class="title-sep" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <path fill-rule="evenodd" d="M70.000,10.000 L70.000,8.000 L80.000,8.000 L80.000,10.000 L70.000,10.000 ZM65.000,3.000 L68.000,3.000 L68.000,16.000 L65.000,16.000 L65.000,3.000 ZM60.000,-0.000 L63.000,-0.000 L63.000,19.000 L60.000,19.000 L60.000,-0.000 ZM22.000,8.000 L58.000,8.000 L58.000,10.000 L22.000,10.000 L22.000,8.000 ZM17.000,-0.000 L20.000,-0.000 L20.000,19.000 L17.000,19.000 L17.000,-0.000 ZM12.000,3.000 L15.000,3.000 L15.000,16.000 L12.000,16.000 L12.000,3.000 ZM-0.000,8.000 L10.000,8.000 L10.000,10.000 L-0.000,10.000 L-0.000,8.000 Z"
              />
            </svg>
            <p><?php echo esc_html($gymnaz_testimonial_subtitle); ?></p>
          </div>
        <?php } ?>
        </div>
      </div>
      <div id='testi-custom-thumb' class="clearfix row">
         <?php
                    $count = 0;
                    while($gymnaz_testimonials_query->have_posts() && $count <= 4 ) :
                    $gymnaz_testimonials_query->the_post();
                 ?> 
        <div class="col-lg-<?php echo esc_attr($gymnaz_testimonial_column); ?> col-md-6 col-sm-6 col-12 testi-dot">
          <?php if(has_post_thumbnail()){ ?>
          <div class="testi-inner">
            <?php the_post_thumbnail(); ?>
            <div class="testi-overlay">
              <div class="overlay-in">
                <h5 class="c-white mb-2">
                 <?php the_title(); ?>
                </h5>
              </div>
            </div>
          </div>
        <?php } ?>
        </div>
         <?php
                    $count = $count + 1;
                    endwhile;
                    wp_reset_postdata();
                ?> 
      </div>
      <div class="row">
        <div class="col-lg-10 offset-lg-1 col-12">
          <div class="testi-one-slider owl-carousel owl-theme">
             <?php
                    $count = 0;
                    while($gymnaz_testimonials_query->have_posts() && $count <= 5 ) :
                    $gymnaz_testimonials_query->the_post();
                 ?> 
            <div class="testi-item">
              <?php the_excerpt(); ?>
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