<?php

/**
 * Products Menu Page
 */

# Custom product post type
add_action('init', 'create_product_post_type');

function create_product_post_type(){
    register_post_type('product', array(
        'labels' => array(
            'name' => __('Sản phẩm'),
            'singular_name' => __('Products'),
            'add_new' => __('Add new'),
            'add_new_item' => __('Add new Product'),
            'new_item' => __('New Product'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Product'),
            'view' => __('View Product'),
            'view_item' => __('View Product'),
            'search_items' => __('Search Products'),
            'not_found' => __('No Product found'),
            'not_found_in_trash' => __('No Product found in trash'),
        ),
        'public' => true,
        'show_ui' => true,
        'publicy_queryable' => true,
        'exclude_from_search' => false,
        'menu_position' => 5,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array(
            'title', 'editor', 'comments', 'author', 'excerpt', 'thumbnail',
            'custom-fields'
        ),
        'rewrite' => array('slug' => 'product', 'with_front' => false),
        'can_export' => true,
        'description' => __('Product description here.'),
    ));
}

# Custom product taxonomies
add_action('init', 'create_product_taxonomies');

function create_product_taxonomies(){
    register_taxonomy('product_category', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Danh mục sản phẩm'),
            'singular_name' => __('Product Categories'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Category'),
            'new_item' => __('New Category'),
            'search_items' => __('Search Categories'),
        ),
    ));
    register_taxonomy('product_factor', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Hãng sản xuất'),
            'singular_name' => __('Product Factors'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Factor'),
            'new_item' => __('New Factor'),
            'search_items' => __('Search Factors'),
        ),
    ));
}

// Show filter

add_action('restrict_manage_posts','restrict_product_by_product_category');
function restrict_product_by_product_category() {
    global $typenow;
    global $wp_query;
    if ($typenow=='product') {
        $taxonomies = array('product_category', 'product_factor');
        foreach ($taxonomies as $taxonomy) {
            $category = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' =>  __("$category->label"),
                'taxonomy'        =>  $taxonomy,
                'name'            =>  $taxonomy,
                'orderby'         =>  'name',
                'selected'        =>  $wp_query->query['term'],
                'hierarchical'    =>  true,
                'depth'           =>  3,
                'show_count'      =>  true, // Show # listings in parens
                'hide_empty'      =>  true, // Don't show businesses w/o listings
            ));
        }
    }
}

// Get post where filter condition

add_filter( 'posts_where' , 'products_where' );
function products_where($where) {
    if (is_admin()) {
        global $wpdb;
        
        $wp_posts = $wpdb->posts;
        $term_relationships = $wpdb->term_relationships;
        $term_taxonomy = $wpdb->term_taxonomy;

        $product_category = intval(getRequest('product_category'));
        $product_factor = intval(getRequest('product_factor'));
        if ($product_category > 0 or $product_factor > 0) {
            $where .= " AND $wp_posts.ID IN (SELECT DISTINCT {$term_relationships}.object_id FROM {$term_relationships} 
                WHERE {$term_relationships}.term_taxonomy_id IN (
                    SELECT DISTINCT {$term_taxonomy}.term_taxonomy_id FROM {$term_taxonomy} ";
            
            if ($product_category > 0 and $product_factor > 0) {
                $where .= " WHERE {$term_taxonomy}.term_id IN ($product_category, $product_factor) 
                                AND {$term_taxonomy}.taxonomy IN ('product_category', 'product_factor' ) ) )";
            } elseif ($product_category > 0) {
                $where .= " WHERE {$term_taxonomy}.term_id = $product_category 
                                AND {$term_taxonomy}.taxonomy = 'product_category') )";
            } elseif ($product_factor > 0) {
                $where .= " WHERE {$term_taxonomy}.term_id = $product_factor 
                                AND {$term_taxonomy}.taxonomy = 'product_factor') )";
            }
                            
            $where = str_replace("AND 0 = 1", "", $where);
        }
    }
    return $where;
}

# product meta box
$product_meta_box = array(
    'id' => 'product-meta-box',
    'title' => 'Thông tin sản phẩm',
    'page' => 'product',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Loại hàng',
            'desc' => 'Ví dụ: Hàng công ty, Hàng xách tay,...',
            'id' => 'loai_hang',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => '<strike>Giá cũ</strike>',
            'desc' => '',
            'id' => 'gia_cu',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Giá mới',
            'desc' => '',
            'id' => 'gia_moi',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Giảm giá',
            'desc' => 'Là tỉ lệ giữa giá mới và giá cũ. Ví dụ: 20%',
            'id' => 'giam_gia',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Gắn cờ',
            'desc' => '',
            'id' => 'sp_flagged',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                '' => 'Không',
                'hot' => 'HOT',
                'new' => 'NEW',
                'sale' => 'SALE',
            )
        ),
        array(
            'name' => 'Tình trạng',
            'desc' => '',
            'id' => 'tinh_trang',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                'Còn hàng' => 'Còn hàng',
                'Hết hàng' => 'Hết hàng',
                'Sắp có hàng' => 'Sắp có hàng',
            )
        ),
        array(
            'name' => 'Sản phẩm nổi bật',
            'desc' => 'Nếu Yes, sản phẩm này sẽ hiển thị trên slider sản phẩm nổi bật.',
            'id' => 'is_highlights',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                '1' => 'Yes',
                '0' => 'No'
            )
        ),
        array(
            'name' => 'Sản phẩm bán chạy',
            'desc' => 'Nếu Yes, sản phẩm này sẽ hiển thị trên danh sách sản phẩm bán chạy.',
            'id' => 'is_most',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                '1' => 'Yes',
                '0' => 'No'
            )
        ),
        array(
            'name' => 'Thứ tự',
            'desc' => 'Số thứ tự hiển thị sản phẩm.',
            'id' => 'sp_displayorder',
            'type' => 'text',
            'std' => '1',
        ),
        array(
            'name' => 'Không hiển thị ở trang chủ',
            'desc' => '',
            'id' => 'not_in_home',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                '1' => 'Yes',
                '0' => 'No'
            )
        ),
));
$area_fields = array(
    array(
        "name" => "Giới thiệu đặc điểm nổi bật",
        "id" => "gioi_thieu",
        'desc' => '',
    ),
    array(
        "name" => "Đặc tính",
        "id" => "dac_tinh",
        'desc' => '',
    ),
    array(
        "name" => "Khuyễn mãi",
        "id" => "khuyen_mai",
        'desc' => '',
    ),
    array(
        "name" => "Tặng kèm",
        "id" => "tang_kem",
        'desc' => '',
    ),
);

// Add product meta box
add_action('admin_menu', 'product_add_box');
add_action('save_post', 'product_add_box');
add_action('save_post', 'product_save_data');

function product_add_box(){
    global $product_meta_box;
    add_meta_box($product_meta_box['id'], $product_meta_box['title'], 'product_show_box', $product_meta_box['page'], $product_meta_box['context'], $product_meta_box['priority']);
}

// Callback function to show fields in product meta box
function product_show_box() {
    global $product_meta_box, $post, $area_fields;
    custom_output_meta_box($product_meta_box, $post);
    
    echo <<<HTML
    <style type="text/css">
        .wp_themeSkin iframe{background: #FFFFFF;}
    </style>
HTML;
    echo '<table width="100%">';
    foreach ($area_fields as $field) :
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        echo '<tr><td>';
        echo "<h4><label for=\"{$field['id']}\">{$field['name']}</label></h4>";
        wp_editor($meta, $field['id'], array(
            'textarea_name' => $field['id'],
            'tinymce' => array(
                "textarea_rows" => 5
            )
        ));
        echo '<br /><span class="description">' . $field['desc'] . '</span>';
        echo '<td></tr>';

    endforeach;
    echo '</table>';
}

// Save data from product meta box
function product_save_data($post_id) {
    global $product_meta_box, $area_fields;
    custom_save_meta_box($product_meta_box, $post_id);
    
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    foreach ($area_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if (isset($_POST[$field['id']]) && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
//            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
/***************************************************************************/
// ADD NEW COLUMN  
function product_columns_head($defaults) {
    $defaults['is_most'] = 'Bán chạy';
    $defaults['tinh_trang'] = 'Tình trạng';
    $defaults['sp_displayorder'] = 'Thứ tự';
    return $defaults;
}

// SHOW THE COLUMN
function product_columns_content($column_name, $post_id) {
    switch ($column_name) {
        case 'is_most':
            $is_most = get_post_meta( $post_id, 'is_most', true );
            if($is_most == 1){
                echo '<a href="edit.php?update_is_most=true&post_id=' . $post_id . '&is_most=' . $is_most . '&redirect_to=' . urlencode(getCurrentRquestUrl()) . '">Yes</a>';
            }else{
                echo '<a href="edit.php?update_is_most=true&post_id=' . $post_id . '&is_most=' . $is_most . '&redirect_to=' . urlencode(getCurrentRquestUrl()) . '">No</a>';
            }
            break;
        case 'tinh_trang':
            echo get_post_meta( $post_id, 'tinh_trang', true );
            break;
        case 'sp_displayorder':
            echo get_post_meta( $post_id, 'sp_displayorder', true );
            break;
        default:
            break;
    }
}

// Update is most stataus
function update_product_is_most(){
    if(getRequest('update_is_most') == 'true'){
        $post_id = getRequest('post_id');
        $is_most = getRequest('is_most');
        $redirect_to = urldecode(getRequest('redirect_to'));
        if($is_most == 1){
            update_post_meta($post_id, 'is_most', 0);
        }else{
            update_post_meta($post_id, 'is_most', 1);
        }
        header("location: $redirect_to");
        exit();
    }
}

add_filter('manage_product_posts_columns', 'product_columns_head');  
add_action('manage_product_posts_custom_column', 'product_columns_content', 10, 2);  
add_filter('admin_init', 'update_product_is_most');  

function sortable_product_is_most_column( $columns ) {  
    $columns['is_most'] = 'is_most';
    $columns['tinh_trang'] = 'tinh_trang';
    $columns['sp_displayorder'] = 'sp_displayorder';
    return $columns;
}

function product_is_most_orderby( $query ) {  
    if( ! is_admin() )  
        return;  
  
    $orderby = $query->get( 'orderby');  
  
    switch ($orderby) {
        case 'is_most':
            $query->set('meta_key','is_most');  
            $query->set('orderby','meta_value_num');  
            break;
        case 'tinh_trang':
            $query->set('meta_key','tinh_trang');  
            $query->set('orderby','meta_value');  
            break;
        case 'sp_displayorder':
            $query->set('meta_key','sp_displayorder');  
            $query->set('orderby','meta_value_num');  
            break;
        default:
            break;
    }
}

add_filter( 'manage_edit-product_sortable_columns', 'sortable_product_is_most_column' );  
add_action( 'pre_get_posts', 'product_is_most_orderby' );  

# Add custom field to quick edit

//add_action( 'bulk_edit_custom_box', 'quickedit_products_custom_box', 10, 2 );
add_action('quick_edit_custom_box', 'quickedit_products_custom_box', 10, 2);

function quickedit_products_custom_box( $col, $type ) {
    if( $col != 'tinh_trang' || $type != 'product' ) {
        return;
    }

    $tinh_trang = array(
                'Còn hàng' => 'Còn hàng',
                'Hết hàng' => 'Hết hàng',
                'Sắp có hàng' => 'Sắp có hàng',
            );
?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col product-custom-fields">
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Giá cũ</span>
                    <input type="text" name="gia_cu" id="gia_cu" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Giá mới</span>
                    <input type="text" name="gia_moi" id="gia_moi" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Giảm giá</span>
                    <input type="text" name="giam_gia" id="giam_gia" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Thứ tự</span>
                    <input type="text" name="sp_displayorder" id="sp_displayorder" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Tình trạng sản phẩm</span>
                    <select name="tinh_trang" id='tinh_trang'>
                        <?php
                        foreach ($tinh_trang as $k => $v) {
                            if($tt == $k){
                                echo "<option value='{$k}' selected='selected'>{$v}</option>\n";
                            }else{
                                echo "<option value='{$k}'>{$v}</option>\n";
                            }
                        }
                        ?>
                    </select>
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
        </div>
    </fieldset>
<?php
}

add_action('save_post', 'product_save_quick_edit_data');
 
function product_save_quick_edit_data($post_id) {
    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;   
    // Check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }
    // OK, we're authenticated: we need to find and save the data
    $post = get_post($post_id);
    if (isset($_POST['gia_cu']) && ($post->post_type != 'revision')) {
        $tinh_trang = esc_attr($_POST['gia_cu']);
        if ($tinh_trang)
            update_post_meta( $post_id, 'gia_cu', $tinh_trang);
    }
    if (isset($_POST['gia_moi']) && ($post->post_type != 'revision')) {
        $tinh_trang = esc_attr($_POST['gia_moi']);
        if ($tinh_trang)
            update_post_meta( $post_id, 'gia_moi', $tinh_trang);
    }
    if (isset($_POST['giam_gia']) && ($post->post_type != 'revision')) {
        $tinh_trang = esc_attr($_POST['giam_gia']);
        if ($tinh_trang)
            update_post_meta( $post_id, 'giam_gia', $tinh_trang);
    }
    if (isset($_POST['sp_displayorder']) && ($post->post_type != 'revision')) {
        $tinh_trang = esc_attr($_POST['sp_displayorder']);
        if ($tinh_trang)
            update_post_meta( $post_id, 'sp_displayorder', $tinh_trang);
    }
    if (isset($_POST['tinh_trang']) && ($post->post_type != 'revision')) {
        $tinh_trang = esc_attr($_POST['tinh_trang']);
        if ($tinh_trang)
            update_post_meta( $post_id, 'tinh_trang', $tinh_trang);
    }
    
    return $post_id;
}

add_action( 'admin_print_scripts-edit.php', 'product_enqueue_edit_scripts' );
function product_enqueue_edit_scripts() {
   wp_enqueue_script( 'product-admin-edit', get_bloginfo( 'stylesheet_directory' ) . '/js/adminjs/quick_edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
}