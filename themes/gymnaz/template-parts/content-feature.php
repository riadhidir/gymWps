<?php 
$gymnaz_enable_feature_section = get_theme_mod( 'gymnaz_enable_feature_section', true );
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
  <section class="service-two">
    <?php
        $count = 0;
        while($gymnaz_features_query->have_posts() && $count <= 4 ) :
        $gymnaz_features_query->the_post();
     ?> 
    <div class="service-box<?php echo esc_attr($gymnaz_feature_column); ?>">
      <?php if(has_post_thumbnail()): ?>
      <div class="s-icon-box">
        <?php the_post_thumbnail(); ?>
      </div>
    <?php endif; ?>
      <div class="s-content">
        <h5><?php the_title(); ?></h5>
        <?php the_excerpt(); ?>
      </div>
    </div>
    <?php
          $count = $count + 1;
          endwhile;
          wp_reset_postdata();
      ?>   
  </section>
  <?php } ?>