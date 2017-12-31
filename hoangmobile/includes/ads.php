<?php

$ads_fields = array(
    "ad_site_left", "ad_site_left_active", "ad_site_left_link",
    "ad_site_right", "ad_site_right_active", "ad_site_right_link",
    "ad_product_category", "ad_product_category_active", "ad_product_category_link",
    "ad_floating", "ad_floating_active",
    "message_floating", "message_floating_active",
    "slide_msg",
);

add_action('admin_menu', 'add_ads_settings_page');

function add_ads_settings_page(){
    global $menuname, $ads_fields;
    
    add_submenu_page($menuname, //Menu ID – Defines the unique id of the menu that we want to link our submenu to. 
                                    //To link our submenu to a custom post type page we must specify - 
                                    //edit.php?post_type=my_post_type
            __('Ads Options'), // Page title
            __('Quảng cáo'), // Menu title
            'edit_themes', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            'ads_options', // Submenu ID – Unique id of the submenu.
            'theme_ads_settings_page' // render output function
        );
    
    if ($_GET['page'] == 'ads_options') {
        if (isset($_REQUEST['action']) and 'save' == $_REQUEST['action']) {
            foreach ($ads_fields as $field) {
                if (isset($_REQUEST[$field])) {
                    if(is_array($_REQUEST[$field])){
                        update_option($field, json_encode($_REQUEST[$field]));
                    }else{
                        update_option($field, $_REQUEST[$field]);
                    }
                } else {
                    delete_option($field);
                }
            }
            header("Location: {$_SERVER['REQUEST_URI']}&saved=true");
            die();
        } 
    }
}

function theme_ads_settings_page() {
    global $themename;
?>
    <style type="text/css">
        .wp-media-buttons .button {
            margin-right: 5px;
        }
        .wp-media-buttons .insert-media {
            padding-left: 0.4em;
        }
        .wp-media-buttons .add_media span.wp-media-buttons-icon {
            background: url("images/media-button.png") no-repeat left top;
            display: inline-block;
            height: 16px;
            margin: 0 2px;
            vertical-align: text-top;
            width: 16px;
        }
    </style>
    <div class="wrap">
        <div class="opwrap" style="margin-top: 10px;" >
            <div class="icon32" id="icon-options-general"></div>
            <h2 class="wraphead"><?php echo $themename; ?> ads settings</h2>
    <?php
    if (isset($_REQUEST['saved']))
        echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' settings saved.</strong></p></div>';
    ?>
            <form method="post">
<!--                <div style="margin-top: 20px;" class="wp-media-buttons">
                    <a class="button insert-media add_media" id="upload_media_button">
                        <span class="wp-media-buttons-icon"></span>
                        Add Media
                    </a>
                </div>-->
                <table class="form-table">
                   <tr>
                        <th><label for="ad_site_left">Ad Site Left</label></th>
                        <td>
                            <?php 
                            $active = get_settings('ad_site_left_active'); 
                            $checked = 'checked="checked"';
                            ?>
                            Active: <input type="radio" name="ad_site_left_active" value="1" <?php echo ($active==1) ? $checked : ""; ?> /> Yes
                            <input type="radio" name="ad_site_left_active" value="0" <?php echo ($active==0) ? $checked : ""; ?> /> No<br />
                            Link:<br />
                            <input type="text" name="ad_site_left_link" value="<?php echo stripslashes(get_settings('ad_site_left_link'));?>" class="regular-text" /><br />
                            Image:<br />
                            <input type="text" name="ad_site_left" id="ad_site_left" value="<?php echo stripslashes(get_settings('ad_site_left'));?>" class="regular-text" />
                            <input type="button" id="upload_ad_site_left_button" class="button button-upload" value="Upload" />
                            <br />
                            <span class="description">Quảng cáo hiển thị ở bên trái website. Size: 130x510</span>
                        </td>
                    </tr>
                   <tr>
                        <th><label for="ad_site_right">Ad Site Right</label></th>
                        <td>
                            <?php 
                            $active = get_settings('ad_site_right_active'); 
                            $checked = 'checked="checked"';
                            ?>
                            Active: <input type="radio" name="ad_site_right_active" value="1" <?php echo ($active==1) ? $checked : ""; ?> /> Yes
                            <input type="radio" name="ad_site_right_active" value="0" <?php echo ($active==0) ? $checked : ""; ?> /> No<br />
                            Link:<br />
                            <input type="text" name="ad_site_right_link" value="<?php echo stripslashes(get_settings('ad_site_right_link'));?>" class="regular-text" /><br />
                            Image:<br />
                            <input type="text" name="ad_site_right" id="ad_site_right" value="<?php echo stripslashes(get_settings('ad_site_right'));?>" class="regular-text" />
                            <input type="button" id="upload_ad_site_right_button" class="button button-upload" value="Upload" />
                            <br />
                            <span class="description">Quảng cáo hiển thị ở bên phải website. Size: 130x510</span>
                        </td>
                    </tr>
                   <tr>
                        <th><label for="ad_product_category">Quảng cáo danh mục sản phẩm</label></th>
                        <td>
                            <?php 
                            $active = get_settings('ad_product_category_active'); 
                            $checked = 'checked="checked"';
                            ?>
                            Active: <input type="radio" name="ad_product_category_active" value="1" <?php echo ($active==1) ? $checked : ""; ?> /> Yes
                            <input type="radio" name="ad_product_category_active" value="0" <?php echo ($active==0) ? $checked : ""; ?> /> No<br />
                            Link:<br />
                            <input type="text" name="ad_product_category_link" value="<?php echo stripslashes(get_settings('ad_product_category_link'));?>" class="regular-text" /><br />
                            Image:<br />
                            <input type="text" name="ad_product_category" id="ad_product_category" value="<?php echo stripslashes(get_settings('ad_product_category'));?>" class="regular-text" />
                            <input type="button" id="upload_ad_product_category_button" class="button button-upload" value="Upload" />
                            <br />
                            <span class="description">Quảng cáo hiển thị trong trang danh mục sản phẩm. Max width = 807px</span>
                        </td>
                    </tr>
                   <tr>
                        <th><label for="slide_msg">Thông báo dưới slide</label></th>
                        <td>
                            <input type="text" name="slide_msg" id="slide_msg" value="<?php echo stripslashes(get_settings('slide_msg'));?>" class="regular-text" />
                            <br />
                            <span class="description">Nội dung chạy dưới slide sản phẩm nổi bật ở trang chủ</span>
                        </td>
                    </tr>
                   <tr>
                        <th><label for="ad_floating">Ad Floating</label></th>
                        <td>
                            <?php 
                            $active = get_settings('ad_floating_active'); 
                            $checked = 'checked="checked"';
                            ?>
                            Active: <input type="radio" name="ad_floating_active" value="1" <?php echo ($active==1) ? $checked : ""; ?> /> Yes
                            <input type="radio" name="ad_floating_active" value="0" <?php echo ($active==0) ? $checked : ""; ?> /> No<br />
                            <?php 
                            wp_editor(stripslashes(get_settings('ad_floating')), 'ad_floating', array(
                                'textarea_name' => 'ad_floating',
                            ));
                            ?>
                            <br />
                            <span class="description">Ad floating popup. Height = 150px</span>
                        </td>
                    </tr>
                   <tr>
                        <th><label for="message_floating">Thông báo</label></th>
                        <td>
                            <?php 
                            $active = get_settings('message_floating_active'); 
                            $checked = 'checked="checked"';
                            ?>
                            Active: <input type="radio" name="message_floating_active" value="1" <?php echo ($active==1) ? $checked : ""; ?> /> Yes
                            <input type="radio" name="message_floating_active" value="0" <?php echo ($active==0) ? $checked : ""; ?> /> No<br />
                            <?php 
                            wp_editor(stripslashes(get_settings('message_floating')), 'message_floating', array(
                                'textarea_name' => 'message_floating',
                            ));
                            ?>
                            <br />
                            <span class="description"></span>
                        </td>
                    </tr>
                </table>
                <div class="submit">
                    <input name="save" type="submit" value="Save changes" class="button button-large button-primary" />
                    <input type="hidden" name="action" value="save" />
                </div>
            </form>
        </div>
    </div>
<?php
}