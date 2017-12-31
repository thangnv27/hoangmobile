<?php
global $wpdb;
if(isset($_POST['reset_modern_settings'])) $Options=$this->getOptions(true);
else $Options=$this->getOptions();
if(isset($_POST['reset_modern_settings'])) {
delete_option($this->OptionsName);
delete_option($wpdb->prefix.'modern_admin_dashboard_widget_registered');
?>
<div class="updated"><p><strong><?php _e('Reset successfully',self::LANG);?>.</strong></p></div>
<?php
}
if (isset($_POST['save_modern_settings'])) {
    $settings=array();
    if(isset($_POST['settings'])){
        foreach($_POST['settings'] as $key => $value){
            $settings[$key]=stripslashes($value);
        }
        $Options['settings'] = $settings;
        update_option($this->OptionsName, $Options);
    }
    ?>
    <div class="updated"><p><strong><?php _e('Settings Updated',self::LANG);?>.</strong></p></div>
<?php
}
if(isset($_POST['reset_settings'])){
    unset($Options['settings']);
    update_option($this->OptionsName, $Options);
    ?>
    <div class="updated"><p><strong><?php _e('Reset ok',self::LANG);?>.</strong></p></div>
<?php
}
$color = (isset($Options['settings']['color']))?$Options['settings']['color']:0;
$hover = (isset($Options['settings']['hover']))?$Options['settings']['hover']:'';
$custom_css=(isset($Options['settings']['custom_css']))?$Options['settings']['custom_css']:'';
$admin_logo_image = (isset($Options['settings']['admin_logo_image']))?$Options['settings']['admin_logo_image']:'';
$admin_logo_text = (isset($Options['settings']['admin_logo_text']))?$Options['settings']['admin_logo_text']:'';
$admin_logo_url = (isset($Options['settings']['admin_logo_url']))?$Options['settings']['admin_logo_url']:'';
$admin_footer_text = (isset($Options['settings']['admin_footer_text']))?$Options['settings']['admin_footer_text']:'Xây dựng và phát triển ứng dụng <a href="http://ppo.vn">PPO</a>';
$admin_footer_version = (isset($Options['settings']['admin_footer_version']))?$Options['settings']['admin_footer_version']:1;
?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Settings',self::LANG);?></h2>

    <form action="" method="post">
        <h4><?php _e('Color',self::LANG);?></h4>
        <table class="form-table" id="modern-admin-icons-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php _e('Choose color',self::LANG);?></th>
                <td id="front-static-pages">

                        <p>
                                <label for="admin_color1"><?php _e('Default',self::LANG);?></label>
                                <input type="radio" id="admin_color1" class="tog" value="0" <?php checked($color,0);?> name="settings[color]">

                        </p>
                        <p>
                                <label for="admin_color2"><?php _e('Red',self::LANG);?></label>
                                <input type="radio" id="admin_color2" class="tog" value="red" <?php checked($color,'red');?> name="settings[color]">

                        </p>
                        <p>
                                <label for="admin_color3"><?php _e('Yellow',self::LANG);?></label>
                                <input type="radio" id="admin_color3" class="tog" value="yellow" <?php checked($color,'yellow');?> name="settings[color]">

                        </p>
                        <p>
                                <label for="admin_color4"><?php _e('Green',self::LANG);?></label>
                                <input type="radio" id="admin_color4" class="tog" value="green" <?php checked($color,'green');?> name="settings[color]">

                        </p>
                        <p>
                                <label for="admin_color5"><?php _e('Purple',self::LANG);?></label>
                                <input type="radio" id="admin_color5" class="tog" value="purple" <?php checked($color,'purple');?> name="settings[color]">

                        </p>

                        <p>
                                <label for="admin_color6"><?php _e('Custom',self::LANG);?></label>
                                <input type="radio" id="admin_color6" class="tog" value="custom" <?php checked($color,'custom');?> name="settings[color]">

                        </p>


                </td>
            </tr>
            </tbody>
        </table>
        <h4><?php _e('Admin menu',self::LANG);?></h4>
        <table class="form-table" id="modern-admin-icons-table">
            <tbody>
            <tr valign="top">
                <th scope="row"></th>
                <td id="front-static-pages">
                        <p>
                                <input type="checkbox" id="admin_menu_hover" value="hover" <?php checked($hover,'hover');?> name="settings[hover]"/>
                                <label for="admin_menu_hover"><?php _e('Hover to show submenu',self::LANG);?></label>
                        </p>
                </td>
            </tr>
            </tbody>
        </table>
        <h4><?php _e('Admin logo',self::LANG);?></h4>
        <table class="modern-admin-form form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><label for="admin_logo_image"><?php _e('Admin Logo Image',self::LANG);?></label></th>
                <td>
                    <input type="text" class="regular-text" id="admin_logo_image" value="<?php echo stripslashes($admin_logo_image);?>" name="settings[admin_logo_image]">
                    <input type="button" class="button button-secondary" value="Upload" name="admin_upload">
                </td>

            </tr>
            <tr valign="top">
                <th scope="row"><label for="admin_logo_image"><?php _e('Admin Logo Text',self::LANG);?></label></th>
                <td>
                    <input type="text" class="regular-text" id="admin_logo_image" value="<?php echo esc_html(stripslashes($admin_logo_text));?>" name="settings[admin_logo_text]">

                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="admin_logo_url"><?php _e('Logo URL',self::LANG);?></label></th>
                <td>
                    <input type="text" class="regular-text" id="admin_logo_url" value="<?php echo esc_html(stripslashes($admin_logo_url));?>" name="settings[admin_logo_url]">

                </td>

            </tr>

            </tbody>
        </table>
        <h4><?php _e('Footer Settings',self::LANG);?></h4>
        <table class="modern-admin-form form-table">
            <tbody>
            <tr valign="top" style="display: none;">
                <th scope="row"><label for="admin_footer_text"><?php _e('Footer Text',self::LANG);?></label></th>
                <td>
                    <input type="text" class="regular-text" id="admin_footer_text"  value="<?php echo esc_html(stripslashes($admin_footer_text));?>" name="settings[admin_footer_text]" />
                </td>

            </tr>
            <tr valign="top">
                <th scope="row"><label for="admin_footer_version"><?php _e('Footer Version',self::LANG);?></label></th>
                <td>
                    <input type="checkbox" id="admin_footer_version" value="1" name="settings[admin_footer_version]" <?php checked($admin_footer_version,1);?>>
                </td>

            </tr>
            </tbody>
        </table>
        <h4><?php _e('Custom CSS',self::LANG);?></h4>
        <table class="modern-admin-form form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><label for="your_custom_css"><?php _e('Your CSS',self::LANG);?></label></th>
                <td>
                    <textarea name="settings[custom_css]" id="your_custom_css"><?php echo $custom_css;?></textarea>
                </td>

            </tr>

            </tbody>
        </table>
        <p class="submit">

            <input type="submit" name="save_modern_settings" class="button button-primary"  value="<?php _e('Save Changes',self::LANG);?>">
            <input type="submit" name="reset_settings" class="button button-secondary"  value="<?php _e('Reset',self::LANG);?>">
        </p>
        <input type="submit" name="reset_modern_settings" class="button button-secondary"  value="<?php _e('Reset All Settings',self::LANG);?>">
    </form>
</div>