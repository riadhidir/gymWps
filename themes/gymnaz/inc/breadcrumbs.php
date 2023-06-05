<?php
function gymnaz_breadcrumbs() {

       $gymnaz_showonhome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $gymnaz_showcurrent = 1;
    if (is_home() || is_front_page()) {

            echo '<ul id="breadcrumbs" class="banner-link text-center"><li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'gymnaz') . '</a></li></ul>';
    } else {

        echo '<ul id="breadcrumbs" class="banner-link text-center"><li><a href="' . esc_url(home_url('/')). '">' . esc_html__('Home', 'gymnaz') . '</a> ';
        if (is_category()) {
            $gymnaz_thisCat = get_category(get_query_var('cat'), false);
            if ($gymnaz_thisCat->parent != 0)
                echo esc_html(get_category_parents($gymnaz_thisCat->parent, TRUE, ' '));
            echo  esc_html__('Archive by category', 'gymnaz') . ' " ' . single_cat_title('', false) . ' "';
        }   elseif (is_search()) {
            echo  esc_html__('Search Results For ', 'gymnaz') . ' " ' . get_search_query() . ' "';
        } elseif (is_day()) {
            echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_html(get_the_time('Y')) . '</a> ';
            echo '<a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '">' . esc_html(get_the_time('F') ). '</a> ';
            echo  esc_html(get_the_time('d'));
        } elseif (is_month()) {
            echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_html(get_the_time('Y')) . '</a> ';
            echo  esc_html(get_the_time('F')) ;
        } elseif (is_year()) {
            echo esc_html(get_the_time('Y')) ;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $gymnaz_post_type = get_post_type_object(get_post_type());
                $gymnaz_slug = $gymnaz_post_type->rewrite;
                echo '<a href="' . esc_url(home_url('/'. $gymnaz_slug['slug'] . '/')) .'">' . esc_html($gymnaz_post_type->labels->singular_name) . '</a>';
                if ($gymnaz_showcurrent == 1)
                    echo  esc_html(get_the_title()) ;
            } else {
                $gymnaz_cat = get_the_category();
                $gymnaz_cat = $gymnaz_cat[0];
                $gymnaz_cats = get_category_parents($gymnaz_cat, TRUE, ' ');
                if ($gymnaz_showcurrent == 0)
                    $gymnaz_cats =
                            preg_replace("#^(.+)\s\s$#", "$1", $gymnaz_cats);
                echo $gymnaz_cats;
                if ($gymnaz_showcurrent == 1)
                    echo  esc_html(get_the_title());
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $gymnaz_post_type = get_post_type_object(get_post_type());
            echo esc_html($gymnaz_post_type->labels->singular_name );
        } elseif (is_page()) {
            if ($gymnaz_showcurrent == 1)
                echo esc_html(get_the_title());
        } elseif (is_page() && $post->post_parent) {
            $gymnaz_parent_id = $post->post_parent;
            $gymnaz_breadcrumbs = array();
            while ($gymnaz_parent_id) {
                $gymnaz_page = get_page($gymnaz_parent_id);
                $gymnaz_breadcrumbs[] = '<a href="' . esc_url(get_permalink($gymnaz_page->ID)) . '">' . esc_html(get_the_title($gymnaz_page->ID)) . '</a>';
                $gymnaz_parent_id = $gymnaz_page->post_parent;
            }
            $gymnaz_breadcrumbs = array_reverse($gymnaz_breadcrumbs);
            for ($gymnaz_i = 0; $gymnaz_i < count($gymnaz_breadcrumbs); $gymnaz_i++) {
                echo $gymnaz_breadcrumbs[$gymnaz_i];
                if ($gymnaz_i != count($gymnaz_breadcrumbs) - 1)
                    echo ' ';
            }
            if ($gymnaz_showcurrent == 1)
                echo esc_html(get_the_title());
        } elseif (is_tag()) {
            echo  esc_html__('Posts tagged', 'gymnaz') . ' " ' . esc_html(single_tag_title('', false)) . ' "';
        } elseif (is_author()) {
            global $author;
            $gymnaz_userdata = get_userdata($author);
            echo  esc_html__('Articles Published by', 'gymnaz') . ' " ' . esc_html($gymnaz_userdata->display_name ). ' "';
        } elseif (is_404()) {
            echo esc_html__('Error 404', 'gymnaz') ;
        }

        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
            printf( /* translators: %s is search query variable*/ esc_html__(' ( Page %s )', 'gymnaz'),esc_html(get_query_var('paged')) );
        }

        
        echo '</li></ul>';
    }
}