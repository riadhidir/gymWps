<?php
$gymnaz_enable_callout_section = get_theme_mod( 'gymnaz_enable_callout_section', false );
$gymnaz_callout_image = get_theme_mod( 'gymnaz_callout_image' );

if($gymnaz_enable_callout_section == true ) {
   
$gymnaz_callout_title = get_theme_mod( 'gymnaz_callout_title','
');
$gymnaz_callout_content = get_theme_mod( 'gymnaz_callout_content','');
$gymnaz_callout_button_label1 = get_theme_mod( 'gymnaz_callout_button_label1','');
$gymnaz_callout_button_link1 = get_theme_mod( 'gymnaz_callout_button_link1','');


?>
    <section class="cta" style="background-image: url(<?php echo esc_url($gymnaz_callout_image); ?>);">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 text-lg-left text-center">
                    <h3 class="c-white text-capitalize"><?php echo esc_html($gymnaz_callout_title); ?></h3>
                    <p class="c-white mb-0"><?php echo esc_html($gymnaz_callout_content); ?></p>
                </div>
                <?php if(!empty($gymnaz_callout_button_label1 && $gymnaz_callout_button_link1)): ?>
                <div class="col-lg-3 text-lg-right text-center">
                    <a href="<?php echo esc_url($gymnaz_callout_button_link1); ?>" class="btn btn-two mt-3"><?php echo esc_html($gymnaz_callout_button_label1); ?></a>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </section>
<?php } ?>