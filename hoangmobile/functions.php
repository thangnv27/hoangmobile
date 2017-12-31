<?php
$basename = basename($_SERVER['PHP_SELF']);
if(!in_array($basename, array('plugins.php', 'update.php', 'upgrade.php'))){
    ob_start();
    ob_start("ob_gzhandler");
}

$themename = "PPO";
$shortname = "hm";

include 'includes/HttpFoundation/Request.php';
include 'includes/HttpFoundation/Response.php';
include 'includes/HttpFoundation/Session.php';
include 'includes/custom.php';
include 'includes/theme_functions.php';
include 'includes/common-scripts.php';
include 'includes/meta-box.php';
include 'includes/theme_settings.php';
include 'includes/custom-user.php';
include 'includes/payment-method.php';
include 'includes/product.php';
include 'includes/ads.php';
include 'includes/support-online.php';
include 'includes/orders.php';
include 'includes/favorite-product.php';
include 'ajax.php';

if(is_admin()){
    //include 'includes/modern-admin/modern-admin.php';
    
    include 'includes/postMeta.php';
    include 'includes/slider.php';
    
    add_action( 'admin_menu', 'custom_remove_menu_pages' );
}else{
    include 'includes/social-post-link.php';
}
/**
 * Remove admin menu
 */
function custom_remove_menu_pages() {
    remove_menu_page('edit-comments.php');
    remove_menu_page('admin.php?page=modern-admin-ui-settings');
}

/*
update_post_meta_for_all_products();
function update_post_meta_for_all_products() {
    query_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    ));
    while(have_posts()) : the_post();
        update_post_meta(get_the_ID(), 'is_highlights', 0);
    endwhile;
}
*/
/* ----------------------------------------------------------------------------------- */
# User login
/* ----------------------------------------------------------------------------------- */
add_action('init', 'user_check_login_status');

function user_check_login_status(){
    global $current_user;
    get_currentuserinfo();
    if (is_user_logged_in()) {
        $_SESSION['current_user_login'] = $current_user;
    }else{
        if(isset($_SESSION['current_user_login'])){
            unset($_SESSION['current_user_login']);
        }
    }
}

add_action('init', 'redirect_after_logout');

function redirect_after_logout() {
    if (preg_match('#(wp-login.php)?(loggedout=true)#', $_SERVER['REQUEST_URI']))
        wp_redirect(get_option('siteurl'));
}

// Redefine user notification function
if (!function_exists('custom_wp_new_user_notification')) {
    function custom_wp_new_user_notification($user_id, $plaintext_pass = '') {
        global $shortname;

        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $message = sprintf(__('New user registration on %s:'), get_option('blogname')) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

        @wp_mail(
                        get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message
        );

        if (empty($plaintext_pass))
            return;

        $login_url = get_page_link(get_option($shortname . '_pageLoginID'));
        
        $message = sprintf(__('Hi %s,'), $user->display_name) . "\r\n\r\n";
        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
        $message .= ($login_url == "") ? wp_login_url() : $login_url . "\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";

        wp_mail(
                $user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message
        );
    }
}

/* ----------------------------------------------------------------------------------- */
# Post Thumbnails
/* ----------------------------------------------------------------------------------- */
if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
}
/* ----------------------------------------------------------------------------------- */
# Register Sidebar
/* ----------------------------------------------------------------------------------- */
register_sidebar(array(
    'name' => __('Sidebar'),
    'id' => __('sidebar'),
    'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<div class="widget-title">',
    'after_title' => '</div>',
));
register_sidebar(array(
    'name' => __('Footer Links Column 1'),
    'id' => __('footer-links-column-1'),
    'before_widget' => '<div id="%1$s" class="widget-container companySingle %2$s">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="widget-title companyTitle">',
    'after_title' => '</div><div class="line4"></div><div class="companyList">',
));
register_sidebar(array(
    'name' => __('Footer Links Column 2'),
    'id' => __('footer-links-column-2'),
    'before_widget' => '<div id="%1$s" class="widget-container companySingle %2$s">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="widget-title companyTitle">',
    'after_title' => '</div><div class="line4"></div><div class="companyList">',
));

function get_favorite_product(){
    global $wpdb, $current_user;
    get_currentuserinfo();
    $records = array();
    if (is_user_logged_in()) {
        $tblFavor = $wpdb->prefix . 'favorite_products';
        $query = "SELECT * FROM $tblFavor WHERE $tblFavor.user_id = $current_user->ID ORDER BY ID DESC";
        $records = $wpdb->get_results($query);
    }
    
    return $records;
}

function get_history_order(){
    global $wpdb, $current_user;
    get_currentuserinfo();
    $records = array();
    if (is_user_logged_in()) {
        $tblOrders = $wpdb->prefix . 'orders';
        $query = "SELECT $tblOrders.*, $wpdb->users.display_name, $wpdb->users.user_email FROM $tblOrders 
            JOIN $wpdb->users ON $wpdb->users.ID = $tblOrders.customer_id 
            WHERE $tblOrders.customer_id = $current_user->ID ORDER BY $tblOrders.ID DESC";
        $records = $wpdb->get_results($query);
    }
    
    return $records;
}

/**
 * Add wysiwyg to custom field textarea
 */
function admin_add_custom_js() {
    ?>
    <script type="text/javascript">/* <![CDATA[ */
        jQuery(function($){
            var area = new Array();
            
            $.each(area, function(index, id){
                //tinyMCE.execCommand('mceAddControl', false, id);
                tinyMCE.init({
                    selector: "textarea#" + id,
                    height: 400
                });
                $("#newmeta-submit").click(function(){
                    tinyMCE.triggerSave();
                });
            });
        });
        /* ]]> */
    </script>
<?php
}
//add_action('admin_print_footer_scripts', 'admin_add_custom_js', 99);