<?php

add_action('after_setup_theme', 'orders_install');
add_action('admin_menu', 'add_orders_page');

/* ----------------------------------------------------------------------------------- */
# Create table in database
/* ----------------------------------------------------------------------------------- */
if (!function_exists('orders_install')) {
    function orders_install() {
        global $wpdb;
        
        $orders = $wpdb->prefix . 'orders';

        $sql = "CREATE TABLE IF NOT EXISTS $orders (
                ID int AUTO_INCREMENT PRIMARY KEY,
                customer_id int NOT NULL,
                customer_info longtext character set utf8 NOT NULL,
                payment_method varchar(255) character set utf8 NOT NULL,
                total_amount varchar(50) NOT NULL,
                products longtext character set utf8 NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                status int default 0 comment '0: Unapproved, 1: Approved, 2: Cancel'
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

/* ----------------------------------------------------------------------------------- */
# Add orders page menu
/* ----------------------------------------------------------------------------------- */
function add_orders_page(){
    global $themename, $shortname, $menuname, $options, $fields;
    
    add_menu_page(__('Orders Management'), // Page title
            __('Orders'), // Menu title
            'manage_options', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            'nvt_orders', // menu id - Unique id of the menu
            'theme_orders_page',// render output function
            get_template_directory_uri() . '/images/icons/cart.png', // URL icon, if empty default icon
            25 // Menu position - integer, if null default last of menu list
        );
    /*-------------------------------------------------------------------------*/
    # Update data
    /*-------------------------------------------------------------------------*/
    if ($_GET['page'] == 'nvt_orders') {
        if (isset($_REQUEST['action']) and 'save' == $_REQUEST['action']) {
            foreach ($fields as $field) {
                update_option($field, $_REQUEST[$field]);
            }
            foreach ($fields as $field) {
                if (isset($_REQUEST[$field])) {
                    update_option($field, $_REQUEST[$field]);
                } else {
                    delete_option($field);
                }
            }
            header("Location: {$_SERVER['REQUEST_URI']}&saved=true");
            die();
        } 
    }
}

/* ----------------------------------------------------------------------------------- */
# Orders layout
/* ----------------------------------------------------------------------------------- */
function theme_orders_page() {
    if(isset($_GET['action']) and $_GET['action'] == 'view-detail'){
        require_once 'class-orders-detail-list-table.php';

        echo <<<HTML
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Orders Details</h2>
HTML;

        //Prepare Table of elements
        $wp_list_table = new WPOrders_Detail_List_Table();
        $wp_list_table->prepare_items();
        //Table of elements
        $wp_list_table->display();

        echo '</div>';
    }else{
        require_once 'class-orders-list-table.php';

        echo <<<HTML
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Orders List</h2>
            <ul class="subsubsub">
HTML;
                echo '<li><a class="', (!isset($_GET['status'])) ? 'current' : '' ,'" href="?page=nvt_orders">All</a> | </li>';
                echo '<li><a class="', (isset($_GET['status']) && $_GET['status'] == 0) ? 'current' : '' ,'" href="?page=nvt_orders&status=0">Pending</a> | </li>';
                echo '<li><a class="', (isset($_GET['status']) && $_GET['status'] == 1) ? 'current' : '' ,'" href="?page=nvt_orders&status=1">Approved</a> | </li>';
                echo '<li><a class="', (isset($_GET['status']) && $_GET['status'] == 2) ? 'current' : '' ,'" href="?page=nvt_orders&status=2">Cancelled</a></li>';
        echo <<<HTML
            </ul>
            <form action="" method="get">
            <input type="hidden" name="page" value="nvt_orders" />
HTML;

        //Prepare Table of elements
        $wp_list_table = new WPOrders_List_Table();
        $wp_list_table->prepare_items();
        //Table of elements
        $wp_list_table->display();

        echo '</form></div>';
    }
}