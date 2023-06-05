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
<!-- ------------------------------------------------------------------- -->
   <section id="portfolio" class="portfolio-sec">
      <div class="container">
        <div class="section-heading">
          <span class="section-title"><?php echo esc_html($gymnaz_portfolio_title);?></span>
          <h3 class="main-heading"><?php echo esc_html($gymnaz_portfolio_subtitle); ?></h3>
        </div>
      </div>
      <div class="container">
        <div class="row portfolio-container">
          	<?php
                    $count = 0;
                    while($gymnaz_portfolio_query->have_posts() && $count <= 8 ) :
                     $gymnaz_portfolio_query->the_post();
                 ?> 
          <div class="col-lg-4">
            <div class="portfolio-item">
              <div class="portfolio-wrap">
                <?php if(has_post_thumbnail()):
                	the_post_thumbnail();
                	endif; ?>
                  <div class="cover">
                    <?php the_excerpt(); ?>
                  </div>
                </div>
                <div class="portfolio-info">
                  <h4 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a></h4>
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
      </div>
    </section>
<?php } ?>