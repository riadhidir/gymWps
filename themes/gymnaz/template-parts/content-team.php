<?php 
$gymnaz_enable_team_section = get_theme_mod( 'gymnaz_enable_team_section', false );
$gymnaz_team_title  = get_theme_mod( 'gymnaz_team_title' );
$gymnaz_team_subtitle  = get_theme_mod( 'gymnaz_team_subtitle' );


if($gymnaz_enable_team_section==true ) {
    

        $gymnaz_teams_no        = 6;
        $gymnaz_teams_pages      = array();
        for( $i = 1; $i <= $gymnaz_teams_no; $i++ ) {
             $gymnaz_teams_pages[] = get_theme_mod('gymnaz_team_page'.$i);

        }
        $gymnaz_teams_args  = array(
        'post_type' => 'page',
        'post__in' => array_map( 'absint', $gymnaz_teams_pages ),
        'posts_per_page' => absint($gymnaz_teams_no),
        'orderby' => 'post__in'
        ); 
        $gymnaz_teams_query = new WP_Query( $gymnaz_teams_args );
      

?>
<section class="traniner-two sp-100">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <?php if(!empty($gymnaz_team_title)) { ?>
        <div class="all-title">
          <h3 class="sec-title">
            <?php echo esc_html($gymnaz_team_title); ?>
          </h3>
          <svg class="title-sep" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <path fill-rule="evenodd" d="M70.000,10.000 L70.000,8.000 L80.000,8.000 L80.000,10.000 L70.000,10.000 ZM65.000,3.000 L68.000,3.000 L68.000,16.000 L65.000,16.000 L65.000,3.000 ZM60.000,-0.000 L63.000,-0.000 L63.000,19.000 L60.000,19.000 L60.000,-0.000 ZM22.000,8.000 L58.000,8.000 L58.000,10.000 L22.000,10.000 L22.000,8.000 ZM17.000,-0.000 L20.000,-0.000 L20.000,19.000 L17.000,19.000 L17.000,-0.000 ZM12.000,3.000 L15.000,3.000 L15.000,16.000 L12.000,16.000 L12.000,3.000 ZM-0.000,8.000 L10.000,8.000 L10.000,10.000 L-0.000,10.000 L-0.000,8.000 Z"
            />
          </svg>
          <p>
          <?php echo esc_html($gymnaz_team_subtitle); ?>
           </p>
        </div>
      <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="team-slider-two owl-carousel owl-theme">
        <?php
            $count = 0;
            while($gymnaz_teams_query->have_posts() && $count <= 5 ) :
            $gymnaz_teams_query->the_post();
         ?> 
        <div class="team-item team-two">
          <div class="team-det">
            <h5 class="mb-2">
              <?php the_title(); ?>
            </h5>
            <?php the_excerpt(); ?>
           
          </div>
          <?php if(has_post_thumbnail()): ?>
          <div class="team-img">
            <?php the_post_thumbnail(); ?>
          </div>
        <?php endif; ?>
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