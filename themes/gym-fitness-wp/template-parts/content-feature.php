<?php 
$gymnaz_enable_feature_section = get_theme_mod( 'gymnaz_enable_feature_section', false );
$gymnaz_feature_column = get_theme_mod( 'gymnaz_feature_column','2');
        $gymnaz_features_no        = 4;
        $gymnaz_features_pages      = array();
        for( $i = 1; $i <= $gymnaz_features_no; $i++ ) {
             $gymnaz_features_pages[] = get_theme_mod('gymnaz_feature_page'.$i); 

        }
        $gymnaz_features_args  = array(
        'post_type' => 'page',
        'post__in' => array_map( 'absint', $gymnaz_features_pages ),
        'posts_per_page' => absint($gymnaz_features_no),
        'orderby' => 'post__in'
        ); 
        $gymnaz_features_query = new WP_Query( $gymnaz_features_args );
      
if($gymnaz_enable_feature_section==true ) {
?>  
  <section class="feature-sec">
  	    <div class="container">
      <div class="row">

    <?php
        $count = 0;
        while($gymnaz_features_query->have_posts() && $count <= 4 ) :
        $gymnaz_features_query->the_post();
     ?> 
    <div class="col-lg-4 col-md-6 col-12 ">
    	<div class="feature-box">
    	<div class="icon-main">
    		<i class="fa fa-heartbeat" aria-hidden="true"></i>
    	</div>
      <div class="content-box">
        <h5 class="title-box"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
        <?php the_excerpt(); ?>
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