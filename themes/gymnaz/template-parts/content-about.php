<?php
/**
 * Template part for displaying section of About Us content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @subpackage Gymnaz
 * @since 1.0.0
 */

$gymnaz_enable_about_us_section = get_theme_mod( 'gymnaz_enable_about_us_section', true );
$gymnaz_about_button_label1 = get_theme_mod( 'gymnaz_about_button_label1');
$gymnaz_about_button_link1 = get_theme_mod( 'gymnaz_about_button_link1');

if($gymnaz_enable_about_us_section==true ) {
 

$gymnaz_about_page = get_theme_mod( 'gymnaz_about_page' );

if( !empty( $gymnaz_about_page ) ) {

	$page_args['page_id'] = absint( $gymnaz_about_page );

	$page_query = new WP_Query( $page_args );

	if( $page_query->have_posts() ) {
?>
	<section class="about-one sp-100-70 bg-dull">
		<?php
			while( $page_query->have_posts() ) {
				$page_query->the_post();
		?>	
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="all-title">
                        <h3 class="sec-title">
                            <?php the_title(); ?>
                        </h3>
                        <svg class="title-sep" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <path fill-rule="evenodd" d="M70.000,10.000 L70.000,8.000 L80.000,8.000 L80.000,10.000 L70.000,10.000 ZM65.000,3.000 L68.000,3.000 L68.000,16.000 L65.000,16.000 L65.000,3.000 ZM60.000,-0.000 L63.000,-0.000 L63.000,19.000 L60.000,19.000 L60.000,-0.000 ZM22.000,8.000 L58.000,8.000 L58.000,10.000 L22.000,10.000 L22.000,8.000 ZM17.000,-0.000 L20.000,-0.000 L20.000,19.000 L17.000,19.000 L17.000,-0.000 ZM12.000,3.000 L15.000,3.000 L15.000,16.000 L12.000,16.000 L12.000,3.000 ZM-0.000,8.000 L10.000,8.000 L10.000,10.000 L-0.000,10.000 L-0.000,8.000 Z"
                            />
                        </svg>
                        
                    </div>
                </div>
            </div>
            		
            <div class="row">
                <div class="<?php if(!has_post_thumbnail()){ ?>col-lg-12<?php } else { ?> col-lg-6<?php } ?> mt-3 mt-lg-0">
                  <?php the_content(); ?>
                 <?php if (!empty($gymnaz_about_button_link1 && $gymnaz_about_button_label1)) {
                     # code...
                ?>
				 <a href="<?php echo esc_url($gymnaz_about_button_link1); ?>" class="btn btn-dark mr-3" target="_blank"><?php echo esc_html($gymnaz_about_button_label1); ?></a>
<?php } ?>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if(has_post_thumbnail()): ?>
                            <div class="feature-box">
                                <?php the_post_thumbnail(); ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
	 
        <?php
					}
                wp_reset_postdata();
				?>
    </section>
<?php
	}
}
}

if(have_posts()) : 
  while(have_posts()) : the_post();
    if(get_the_content()!= "")
    {
    ?>
      <section class="blog sp-100">
          <div class="container">
            <div class="row">
          <?php the_content(); ?> 
        </div>
        </div> 
      </section>  
    <?php 
    } 
  endwhile;
endif; 

?>