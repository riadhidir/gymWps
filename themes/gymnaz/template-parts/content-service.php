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

        }
        $gymnaz_services_args  = array(
        'post_type' => 'page',
        'post__in' => array_map( 'absint', $gymnaz_services_pages ),
        'posts_per_page' => absint($gymnaz_services_no),
        'orderby' => 'post__in'
        ); 
        $gymnaz_services_query = new WP_Query( $gymnaz_services_args );
      

?>
    <section class="class-one sp-100-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php if(!empty($gymnaz_service_title)) { ?>
                    <div class="all-title">
                        <h3 class="sec-title">
                            <?php echo esc_html($gymnaz_service_title); ?>
                        </h3>
                        <svg class="title-sep" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <path fill-rule="evenodd" d="M70.000,10.000 L70.000,8.000 L80.000,8.000 L80.000,10.000 L70.000,10.000 ZM65.000,3.000 L68.000,3.000 L68.000,16.000 L65.000,16.000 L65.000,3.000 ZM60.000,-0.000 L63.000,-0.000 L63.000,19.000 L60.000,19.000 L60.000,-0.000 ZM22.000,8.000 L58.000,8.000 L58.000,10.000 L22.000,10.000 L22.000,8.000 ZM17.000,-0.000 L20.000,-0.000 L20.000,19.000 L17.000,19.000 L17.000,-0.000 ZM12.000,3.000 L15.000,3.000 L15.000,16.000 L12.000,16.000 L12.000,3.000 ZM-0.000,8.000 L10.000,8.000 L10.000,10.000 L-0.000,10.000 L-0.000,8.000 Z"
                            />
                        </svg>

                        <p><?php echo esc_html($gymnaz_service_subtitle); ?></p>
                    </div>
                <?php } ?>
                </div>
            </div>
            <div class="row justify-content-center">
                 <?php
                    $count = 0;
                    while($gymnaz_services_query->have_posts() && $count <= 8 ) :
                    $gymnaz_services_query->the_post();
                 ?> 
                <div class="col-xl-<?php echo esc_attr($gymnaz_service_column); ?> col-lg-4 col-md-6 col-12">
                    <div class="class-box">
                        <?php if(has_post_thumbnail()): ?>
                        <div class="class-img">
                            <?php the_post_thumbnail(); ?>
                        </div>
                    <?php endif; ?>
                        <div class="bot-box">
                            <h5>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h5>
                            <div class="class-schedule">
                                <?php the_excerpt(); ?>
                            </div>
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