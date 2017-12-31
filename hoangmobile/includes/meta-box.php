<?php
if(!function_exists('custom_output_meta_box')){
    /**
     * Custom meta box ouput
     * 
     * @param array $meta_box
     * @param object $post
     * @return string HTML Ouput
     */
    function custom_output_meta_box($meta_box, $post){
        // Use nonce for verification
        echo '<input type="hidden" name="secure_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

        echo '<table width="100%">';
        foreach ($meta_box['fields'] as $field) {
            // get current post meta data
            $meta = get_post_meta($post->ID, $field['id'], true);

            echo '<tr>',
            '<th style="text-align: left;"><label for="', $field['id'], '">', $field['name'], '</label></th>',
            '<td>';
            switch ($field['type']) {
                case 'text':
                    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', htmlspecialchars($meta) ? htmlspecialchars($meta) : htmlspecialchars($field['std']), '" size="30" style="width:99%" />', '<br /><span class="description">', $field['desc'], '</span>';
                    break;
                case 'textarea':
                    echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:99%">', $meta ? $meta : $field['std'], '</textarea>', '<br /><span class="description">', $field['desc'], '</span>';
                    break;
                case 'select':
                    echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                    foreach ($field['options'] as $option) {
                        echo '<option value="', $option, '" ', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                    }
                    echo '</select>';
                    echo '<br /><span class="description">', $field['desc'], '</span>';
                    break;
                case 'radio':
                    foreach ($field['options'] as $key => $option) {
                        echo '<input type="radio" name="', $field['id'], '" value="', $key, '"', $meta == $key ? ' checked="checked"' : '', ' /> ', $option, ' ';
                    }
                    echo '<br /><span class="description">', $field['desc'], '</span>';
                    break;
                case 'checkbox':
                    echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                    echo '<br /><span class="description">', $field['desc'], '</span>';
                    break;
            }
            echo '<td>',
            '</tr>';
        }

        echo '</table>';
    }
}
if(!function_exists('custom_save_meta_box')){
    /**
     * Save meta box data
     * 
     * @param array $meta_box
     * @param int $post_id
     * @return 
     */
    function custom_save_meta_box($meta_box, $post_id){
        // verify nonce
        if (!wp_verify_nonce($_POST['secure_meta_box_nonce'], basename(__FILE__))) {
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
        foreach ($meta_box['fields'] as $field) {
            $old = get_post_meta($post_id, $field['id'], true);
            $new = $_POST[$field['id']];
            if (isset($_POST[$field['id']]) && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
            } elseif ('' == $new && $old) {
//                delete_post_meta($post_id, $field['id'], $old);
            }
        }
    }
}