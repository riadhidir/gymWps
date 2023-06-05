<?php 
$gymnaz_enable_portfolio_section = get_theme_mod( 'gymnaz_enable_portfolio_section', false );
$gymnaz_portfolio_title = get_theme_mod( 'gymnaz_portfolio_title');
$gymnaz_portfolio_subtitle = get_theme_mod( 'gymnaz_portfolio_subtitle' );

if($gymnaz_enable_portfolio_section==true ) {
    

        $gymnaz_portfolio_no        = 8;
        $gymnaz_portfolio_page      = array();
        for( $k = 1; $k <= $gymnaz_portfolio_no; $k++ ) {
             $gymnaz_portfolio_page[] = get_theme_mod('gymnaz_portfolio_page'.$k); 

        }
        $gymnaz_portfolio_args  = array(
        'post_type' => 'page',
        'post__in' => array_map( 'absint', $gymnaz_portfolio_page ),
        'posts_per_page' => absint($gymnaz_portfolio_no),
        'orderby' => 'post__in'
        ); 
        $gymnaz_portfolio_query = new WP_Query( $gymnaz_portfolio_args );
      

?>
<section class="gallary-one bg-dull sp-100 pb-0">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <?php if(!empty($gymnaz_portfolio_title)) { ?>
          <div class="all-title">
            <h3 class="sec-title">
               <?php echo esc_html($gymnaz_portfolio_title); ?>
            </h3>
            <svg class="title-sep" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <path fill-rule="evenodd" d="M70.000,10.000 L70.000,8.000 L80.000,8.000 L80.000,10.000 L70.000,10.000 ZM65.000,3.000 L68.000,3.000 L68.000,16.000 L65.000,16.000 L65.000,3.000 ZM60.000,-0.000 L63.000,-0.000 L63.000,19.000 L60.000,19.000 L60.000,-0.000 ZM22.000,8.000 L58.000,8.000 L58.000,10.000 L22.000,10.000 L22.000,8.000 ZM17.000,-0.000 L20.000,-0.000 L20.000,19.000 L17.000,19.000 L17.000,-0.000 ZM12.000,3.000 L15.000,3.000 L15.000,16.000 L12.000,16.000 L12.000,3.000 ZM-0.000,8.000 L10.000,8.000 L10.000,10.000 L-0.000,10.000 L-0.000,8.000 Z"></path>
            </svg>
             <p><?php echo esc_html($gymnaz_portfolio_subtitle); ?></p>
          </div>
        <?php } ?>
        </div>
      </div>
     
    </div>
    <div class="container-fluid">
      <div class="row masonary-wrap" style="position: relative; height: 676.579px;">
         <?php
                    $count = 0;
                    while($gymnaz_portfolio_query->have_posts() && $count <= 8 ) :
                    $gymnaz_portfolio_query->the_post();
                 ?> 
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 port-item mas-item px-0 cardio fitness">
          <div class="project">
            <?php if(has_post_thumbnail()): ?>
            <div class="proj-img">
              <?php the_post_thumbnail(); ?>
              <div class="proj-overlay">
                <a href="<?php the_post_thumbnail_url(); ?>" class="pop-btn">
                  <i class="fa fa-image"></i>
                </a>
              </div>
            </div>
          <?php endif; ?>
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