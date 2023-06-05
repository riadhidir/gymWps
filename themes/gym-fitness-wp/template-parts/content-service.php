<?php 
$gymnaz_enable_service_section = get_theme_mod( 'gymnaz_enable_service_section', false );
$gymnaz_service_title = get_theme_mod( 'gymnaz_service_title');
$gymnaz_service_subtitle = get_theme_mod( 'gymnaz_service_subtitle' );
$gymnaz_service_column = get_theme_mod( 'gymnaz_service_column');
if($gymnaz_enable_service_section==true ) {


          $gymnaz_services_no        = 8;
        $gymnaz_services_pages      = array();
        for( $i = 1; $i <= $gymnaz_services_no; $i++ ) {
             $gymnaz_services_pages[] = get_theme_mod('gymnaz_service_page'.$i); 
                          $gymnaz_service_icon[]= get_theme_mod('gymnaz_service_icon '.$i,'fa fa-user');


        }		
        $gymnaz_services_args  = array(
        'post_type' => 'page',
        'post__in' => array_map( 'absint', $gymnaz_services_pages ),
        'posts_per_page' => absint($gymnaz_services_no),
        'orderby' => 'post__in'
        ); 
        $gymnaz_services_query = new WP_Query( $gymnaz_services_args );
      

?> 
    <!-- ======= Services Section ======= -->
    
        <section class="class-one sp-100-70">
        <div class="container">
        	<div class="section-heading">
		          <span class="section-title"><?php echo esc_html($gymnaz_service_title);?></span>
		          <h3 class="main-heading"><?php echo esc_html($gymnaz_service_subtitle); ?></h3>
		        </div>
            <div class="row">
                <div class="col-12">
                    <?php if(!empty($gymnaz_service_title)) { ?>                        
                       
                <?php } ?>
                </div>
            </div>
            <div class="row justify-content-center">
                 <?php
                    $count = 0;
                    while($gymnaz_services_query->have_posts() && $count <= 8 ) :
                    $gymnaz_services_query->the_post();

                 ?> 
                <div class="col-xl-<?php echo esc_attr($gymnaz_service_column); ?> col-lg-<?php echo esc_attr($gymnaz_service_column); ?> col-md-6 col-12">
                    <div class="serviceBox">
                    	<div class="service-icon">
                            <i class="fa fa-heartbeat" aria-hidden="true"></i>
                        </div>
                        <h5 class="title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>
                        <?php the_excerpt(); ?>
                        <div class="btn-yellow">
                        	<a href="<?php the_permalink(); ?>"><?php echo esc_html__('LEARN MORE','gym-fitness-wp'); ?><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                 <?php
                    $count = $count + 1;
                    endwhile;
                    wp_reset_postdata();
                ?> 
            </div>
        </div>
    </section>

<?php } ?>