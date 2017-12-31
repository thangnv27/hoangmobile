<?php

add_action('after_setup_theme', 'favorite_products_install');

/* ----------------------------------------------------------------------------------- */
# Create table in database
/* ----------------------------------------------------------------------------------- */
if (!function_exists('favorite_products_install')) {
    function favorite_products_install() {
        global $wpdb;
        
        $favorite_products = $wpdb->prefix . 'favorite_products';

        $sql = "CREATE TABLE IF NOT EXISTS $favorite_products (
                ID int AUTO_INCREMENT PRIMARY KEY,
                user_id int NOT NULL,
                product_id int NOT NULL,
                quantity int NOT NULL default 1,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

