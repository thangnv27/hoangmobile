<?php

/**
 * Custom post type
 */

# post meta box
$post_meta_box = array(
    'id' => 'post-meta-box',
    'title' => 'Thông tin bài viết',
    'page' => 'post',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Tin nổi bật',
            'desc' => 'Hiển thị trên slide tin nổi bật.',
            'id' => 'is_most',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                '1' => 'Yes',
                '0' => 'No'
            )
        ),
));

// Add post meta box
add_action('admin_menu', 'post_add_box');
add_action('save_post', 'post_add_box');
add_action('save_post', 'post_save_data');

function post_add_box(){
    global $post_meta_box;
    add_meta_box($post_meta_box['id'], $post_meta_box['title'], 'post_show_box', $post_meta_box['page'], $post_meta_box['context'], $post_meta_box['priority']);
}

function post_show_box() {
    // Use nonce for verification
    global $post_meta_box, $post;
    custom_output_meta_box($post_meta_box, $post);
}

// Save data from post meta box
function post_save_data($post_id) {
    global $post_meta_box;
    custom_save_meta_box($post_meta_box, $post_id);
}

/***************************************************************************/
// ADD NEW COLUMN  
function post_columns_head($defaults) {
    $defaults['is_most'] = 'Nổi bật';
    return $defaults;
}

// SHOW THE COLUMN
function post_columns_content($column_name, $post_id) {
    if ($column_name == 'is_most') {
        $is_most = get_post_meta( $post_id, 'is_most', true );
        if($is_most == 1){
            echo '<a href="edit.php?update_is_most=true&post_id=' . $post_id . '&is_most=' . $is_most . '&redirect_to=' . urlencode(getCurrentRquestUrl()) . '">Yes</a>';
        }else{
            echo '<a href="edit.php?update_is_most=true&post_id=' . $post_id . '&is_most=' . $is_most . '&redirect_to=' . urlencode(getCurrentRquestUrl()) . '">No</a>';
        }
    }
}

// Update is most stataus
function update_post_is_most(){
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

add_filter('manage_post_posts_columns', 'post_columns_head');  
add_action('manage_post_posts_custom_column', 'post_columns_content', 10, 2);  
add_filter('admin_init', 'update_post_is_most');  

function sortable_is_most_column( $columns ) {  
    $columns['is_most'] = 'is_most';  
  
    //To make a column 'un-sortable' remove it from the array  
    //unset($columns['date']);  
  
    return $columns;  
}

function is_most_orderby( $query ) {  
    if( ! is_admin() )  
        return;  
  
    $orderby = $query->get( 'orderby');  
  
    if( 'is_most' == $orderby ) {  
        $query->set('meta_key','is_most');  
        $query->set('orderby','meta_value_num');  
    }  
}

add_filter( 'manage_edit-post_sortable_columns', 'sortable_is_most_column' );  
add_action( 'pre_get_posts', 'is_most_orderby' );  