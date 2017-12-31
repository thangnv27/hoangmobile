<?php

# Custom support_online post type
add_action('init', 'create_support_online_post_type');

function create_support_online_post_type(){
    register_post_type('support_online', array(
        'labels' => array(
            'name' => __('Hỗ trợ trực tuyến'),
            'singular_name' => __('Supports'),
            'add_new' => __('Add new'),
            'add_new_item' => __('Add new Support'),
            'new_item' => __('New Support'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Support'),
            'view' => __('View Support'),
            'view_item' => __('View Support'),
            'search_items' => __('Search Supports'),
            'not_found' => __('No Support found'),
            'not_found_in_trash' => __('No Support found in trash'),
        ),
        'public' => true,
        'show_ui' => true,
        'publicy_queryable' => true,
        'exclude_from_search' => false,
        'menu_position' => 5,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array(
            'title', 
            //'editor', 'comments', 'author', 'excerpt', 'thumbnail', 'custom-fields'
        ),
        'rewrite' => array('slug' => 'support_online', 'with_front' => false),
        'can_export' => true,
        'description' => __('Support description here.')
    ));
}

# support_online meta box
$support_online_meta_box = array(
    'id' => 'support_online-meta-box',
    'title' => 'Thông tin',
    'page' => 'support_online',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Tên hiển thị',
            'desc' => '',
            'id' => 'so_name',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Điện thoại',
            'desc' => '',
            'id' => 'so_phone',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Email',
            'desc' => '',
            'id' => 'so_email',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Chat Type',
            'desc' => '',
            'id' => 'so_chat_type',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                'yahoo' => 'Yahoo',
                'skype' => 'Skype',
            )
        ),
        array(
            'name' => 'Chat ID',
            'desc' => '',
            'id' => 'so_chat_id',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Sắp xếp',
            'desc' => 'Thứ tự sắp xếp hiển thị',
            'id' => 'so_order',
            'type' => 'text',
            'std' => '1',
        ),
));

// Add support_online meta box
add_action('admin_menu', 'support_online_add_box');
add_action('save_post', 'support_online_add_box');
add_action('save_post', 'support_online_save_data');

function support_online_add_box(){
    global $support_online_meta_box;
    add_meta_box($support_online_meta_box['id'], $support_online_meta_box['title'], 'support_online_show_box', $support_online_meta_box['page'], $support_online_meta_box['context'], $support_online_meta_box['priority']);
}

// Callback function to show fields in support_online meta box
function support_online_show_box() {
    global $support_online_meta_box, $post;
    custom_output_meta_box($support_online_meta_box, $post);
}

// Save data from support_online meta box
function support_online_save_data($post_id) {
    global $support_online_meta_box;
    custom_save_meta_box($support_online_meta_box, $post_id);
}