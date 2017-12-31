<?php

# Custom slider post type
add_action('init', 'create_slider_post_type');

function create_slider_post_type(){
    $args = array(
        'labels' => array(
            'name' => __('Sliders'),
            'singular_name' => __('Sliders'),
            'add_new' => __('Add new'),
            'add_new_item' => __('Add new Slider'),
            'new_item' => __('New Slider'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Slider'),
            'view' => __('View Slider'),
            'view_item' => __('View Slider'),
            'search_items' => __('Search Sliders'),
            'not_found' => __('No Slider found'),
            'not_found_in_trash' => __('No Slider found in trash'),
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
        ),
        'rewrite' => array('slug' => 'slider', 'with_front' => false),
        'can_export' => true,
        'description' => __('Slider description here.')
    );
    
    register_post_type('slider', $args);
}

# Custom slider taxonomies
/*add_action('init', 'create_slider_taxonomies');

function create_slider_taxonomies(){
    register_taxonomy('slider_category', 'slider', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => __('Slider Categories'),
            'singular_name' => __('Slider Categories'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Category'),
            'new_item' => __('New Category'),
            'search_items' => __('Search Categories'),
        ),
    ));
}*/

# slider meta box
$slider_meta_box = array(
    'id' => 'slider-meta-box',
    'title' => 'Thông tin',
    'page' => 'slider',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Hình ảnh',
            'desc' => 'Có thể điền URL ảnh hoặc chọn ảnh từ thư viện. Size: 1020x280',
            'id' => 'slide_img',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Liên kết',
            'desc' => '',
            'id' => 'slide_link',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Sắp xếp',
            'desc' => '',
            'id' => 'slide_order',
            'type' => 'text',
            'std' => '1',
        ),
));

// Add slider meta box
add_action('admin_menu', 'slider_add_box');
add_action('save_post', 'slider_add_box');

function slider_add_box(){
    global $slider_meta_box;
    add_meta_box($slider_meta_box['id'], $slider_meta_box['title'], 'slider_show_box', $slider_meta_box['page'], $slider_meta_box['context'], $slider_meta_box['priority']);
}

// Callback function to show fields in slider meta box
function slider_show_box() {
    // Use nonce for verification
    global $slider_meta_box, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="slider_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table width="100%">';
    foreach ($slider_meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        echo '<tr>',
        '<th style="text-align: left; width: 20%;"><label for="', $field['id'], '">', $field['name'], '</label></th>',
        '<td>';
        switch ($field['type']) {
            case 'text':
                if($field['id'] == 'slide_img'){
                    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', htmlspecialchars($meta) ? htmlspecialchars($meta) : htmlspecialchars($field['std']), '" size="30" style="width:88%" />';
                    echo '<input type="button" id="upload_slide_img_button" class="button button-upload" value="Upload" />', '<br /><span class="description">', $field['desc'], '</span>';
                }else{
                    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', htmlspecialchars($meta) ? htmlspecialchars($meta) : htmlspecialchars($field['std']), '" size="30" style="width:88%" />', '<br /><span class="description">', $field['desc'], '</span>';
                }
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option value="', $option , '" ', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $key => $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $key, '"', $meta == $key ? ' checked="checked"' : '', ' /> ', $option , ' ';
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo '<td>',
        '</tr>';
    }

    echo '</table>';
}

add_action('save_post', 'slider_save_data');

// Save data from slider meta box
function slider_save_data($post_id) {
    global $slider_meta_box;
    // verify nonce
    if (!wp_verify_nonce($_POST['slider_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    foreach ($slider_meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
