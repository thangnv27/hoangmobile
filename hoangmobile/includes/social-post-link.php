<?php
/**
 * @author Ngo Van Thang <ngothangit@gmail.com>
 * @copyright (c) 2013, Ngo Van Thang
 */

function add_head_meta_tags() {
    echo "<!-- Ngo Thang's Social share link -->\n";
    echo "<meta property=\"og:site_name\" content=\"" . get_bloginfo('name') . "\"/>\n";
    
    if (is_single() || is_page()) {
        $post = get_post( get_the_ID() );
        $thumbs = ngothang_get_thumbnail(200, 200);
        $the_description = get_short_content($post->post_content, 255);

        echo "<meta property=\"og:image\" content=\"" . $thumbs['thumb'] . "\"/>\n";
        //echo "<meta property=\"og:image:secure_url\" content=\"{$thumbs['thumb']}\" />\n";
        echo "<meta property=\"og:url\" content=\"" . get_permalink($post->ID) . "\"/>\n";
        echo "<meta property=\"og:title\" content=\"" . $post->post_title . "\"/>\n";
        echo "<meta property=\"og:description\" content=\"" . $the_description . "\" />\n";
        echo "<meta property=\"og:type\" content=\"article\" />\n";
        
        echo "<link rel=\"title\" content=\"" . $post->post_title . "\"/>\n";
        echo "<link rel=\"image_src\" href=\"" . $thumbs['thumb'] . "\" />\n";
    } else {
        $imgsrc = get_option('sitelogo');
        echo "<meta property=\"og:image\" content=\"" . $imgsrc . "\"/>\n";
        //echo "<meta property=\"og:image:secure_url\" content=\"{$imgsrc}\" />\n";
        echo "<meta property=\"og:url\" content=\"" . getCurrentRquestUrl() . "\"/>\n";
        echo "<meta property=\"og:title\" content=\"" . get_bloginfo('name') . " - ". get_bloginfo('description') ."\"/>\n";
        echo "<meta property=\"og:type\" content=\"website\" />\n";
        /*if(is_home()){
            echo "<meta property=\"og:type\" content=\"website\" />\n";
        }elseif(!is_single() && !is_page()){
            echo "<meta property=\"og:type\" content=\"blog\" />\n";
        }*/
        echo "<link rel=\"image_src\" href=\"" . $imgsrc . "\" />\n";
    }
    echo "<!-- /Ngo Thang's Social share link -->\n";
}

add_filter('wp_head', 'add_head_meta_tags');


/**
 * this function gets thumbnail from Post Thumbnail or Custom field or First post image
 */
function ngothang_get_thumbnail($width = 100, $height = 100, $custom_field = '', $post = '') {
    if ($post == '')
        global $post;

    $thumb_array['thumb'] = '';

    if (function_exists('has_post_thumbnail')) {
        if (has_post_thumbnail($post->ID)) {

            $thumb_array['thumb'] = get_the_post_thumbnail($post->ID, array($width, $height));
            if ($thumb_array['thumb'] <> '') {
                $thumb_array['thumb'] = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $thumb_array['thumb'], $matches);
                $thumb_array['thumb'] = trim($matches[1][0]);
            }
        }
    }

    if ($thumb_array['thumb'] == '') {
        if ($custom_field == '')
            $thumb_array['thumb'] = get_post_meta($post->ID, 'Thumbnail', $single = true);
        else {
            $thumb_array['thumb'] = get_post_meta($post->ID, $custom_field, $single = true);
            if ($thumb_array['thumb'] == '')
                $thumb_array['thumb'] = get_post_meta($post->ID, 'Thumbnail', $single = true);
        }

        if ($custom_field == '')
            $thumb_array['thumb'] = apply_filters('et_fullpath', $thumb_array['thumb']);
        elseif ($custom_field <> '' && get_post_meta($post->ID, 'Thumbnail', $single = true))
            $thumb_array['thumb'] = apply_filters('et_fullpath', get_post_meta($post->ID, 'Thumbnail', $single = true));
    }
    return $thumb_array;
}

/***********************************************************
 * add admin menu settings
 * *********************************************************/
/*add_action('admin_menu', 'ngothang_pstlink_add_menu');

function ngothang_pstlink_add_menu() {
    if (function_exists('add_options_page')) {
        add_options_page(__('Social Link Settings', 'wp-social-post-link'), __('Social Link Setting', 'wp-social-post-link'), 'manage_options', __FILE__);
    }
}*/