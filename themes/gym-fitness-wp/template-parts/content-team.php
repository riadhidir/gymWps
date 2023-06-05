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
	
	<!-- ======= Team Section ======= -->

   <section class="team-sec">
      <div class="container">
        <div class="section-heading">
          <span class="section-title"><?php echo esc_html($gymnaz_team_title);?></span>
          <h3 class="main-heading"><?php echo esc_html($gymnaz_team_subtitle); ?></h3>
        </div>
        <div class="row">
        	<?php
                $count = 0;
                while($gymnaz_teams_query->have_posts() && $count <= 8 ) :
                $gymnaz_teams_query->the_post();
             ?> 
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="our-team">
                <div class="pic">
	                <?php if(has_post_thumbnail()): ?>
	                	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
	                <?php endif; ?>
                </div>
                <div class="team-prof">
                    <h3 class="post-title"><?php the_title(); ?></h3>
                    <div class="team-overlay">
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