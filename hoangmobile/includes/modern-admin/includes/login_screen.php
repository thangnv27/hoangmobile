<?php
$Options = $this->getOptions();
if (isset($_POST['save_login_screen'])){
    $login_fields=array();
    if(isset($_POST['login_screen'])){
        foreach($_POST['login_screen'] as $key => $value)
            $login_fields[$key]=stripslashes($value);
        $Options['login_screen'] = $login_fields;
        update_option($this->OptionsName, $Options);
    }
?>
<div class="updated"><p><strong><?php _e('Settings Updated',self::LANG);?></strong></p></div>
<?php
}
if(isset($_POST['reset_login_screen'])){
    unset($Options['login_screen']);
    update_option($this->OptionsName, $Options);
    ?>
    <div class="updated"><p><strong><?php _e('Reset Ok',self::LANG);?></strong></p></div>
<?php
}
?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Login Screen Setting',self::LANG);?></h2>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <?php $field="active";
                $val=(isset($Options['login_screen'][$field]))? $Options['login_screen'][$field]:0;
                ?>
                <th scope="row"><label for="<?php echo $field;?>"><?php _e('Enable custom login screen',self::LANG);?></label></th>
                <td>
                    <input type="checkbox" id="<?php echo $field;?>" value="1" name="login_screen[<?php echo $field;?>]" <?php checked($val,1)?>>

                </td>

            </tr>
            </tbody>
        </table>
        <div id="enable_custom_login">
        <h4><?php _e('Background',self::LANG);?></h4>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <?php $field="background";
                    $val=(isset($Options['login_screen'][$field]))? esc_html($Options['login_screen'][$field]):'';
                    ?>
                    <th scope="row"><label for="<?php echo $field;?>"><?php _e('Background Image',self::LANG);?></label></th>
                    <td>
                        <input type="text" id="<?php echo $field;?>" class="regular-text" value="<?php echo $val;?>" name="login_screen[<?php echo $field;?>]">
                        <input type="button" class="button button-secondary" value="Upload" name="bg_upload">
                    </td>

                </tr>
                <tr valign="top">
                    <?php $field="bg-repeat";
                    $val=(isset($Options['login_screen'][$field]))? esc_html($Options['login_screen'][$field]):'repeat';
                    ?>
                    <th scope="row"><label for="<?php echo $field;?>"><?php _e('Background Repeat',self::LANG);?></label></th>
                    <td>
                        <select name="login_screen[<?php echo $field;?>]">
                            <option value="inherit" <?php selected($val,"inherit");?>>Inherit</option>
                            <option value="repeat" <?php selected($val,"repeat");?>>Repeat</option>
                            <option value="repeat-x" <?php selected($val,"repeat-x");?>>Repeat X</option>
                            <option value="repeat-y" <?php selected($val,"repeat-y");?>>Repeat Y</option>
                            <option value="no-repeat" <?php selected($val,"no-repeat");?>>No repeat</option
                        </select>

                    </td>
                </tr>
                <tr valign="top">
                    <?php $field="bg-color";
                    $val=(isset($Options['login_screen'][$field]))? esc_html($Options['login_screen'][$field]):'';
                    ?>
                    <th scope="row"><label for="<?php echo $field;?>"><?php _e('Background Color',self::LANG);?></label></th>
                    <td>
                        <input type="text" id="<?php echo $field;?>" class="regular-text" value="<?php echo $val;?>" name="login_screen[<?php echo $field;?>]">
                    </td>
                </tr>
                <tr valign="top">
                    <?php $field="bg-position";
                    $val=(isset($Options['login_screen'][$field]))? esc_html($Options['login_screen'][$field]):'Left Top';
                    ?>
                    <th scope="row"><label for="<?php echo $field;?>"><?php _e('Background Position',self::LANG);?></label></th>
                    <td>
                        <select name="login_screen[<?php echo $field;?>]">
                            <option value="left top" <?php selected($val,"left top");?>>Left Top</option>
                            <option value="left center" <?php selected($val,"left center");?>>Left Center</option>
                            <option value="left bottom" <?php selected($val,"left bottom");?>>Left Bottom</option>
                            <option value="center center" <?php selected($val,"center center");?>>Center Center</option>
                            <option value="center top" <?php selected($val,"center top");?>>Center Top</option>
                            <option value="center bottom" <?php selected($val,"center bottom");?>>Center Bottom</option>
                            <option value="right top" <?php selected($val,"right top");?>>Right Top</option>
                            <option value="right center" <?php selected($val,"right center");?>>Right Center</option>
                            <option value="right bottom" <?php selected($val,"right bottom");?>>Right Bottom</option>
                        </select>

                    </td>
                </tr>
            </tbody>
        </table>
        <h4><?php _e('Login Form',self::LANG);?></h4>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <?php $field="image";
                $val=(isset($Options['login_screen'][$field]))? esc_html($Options['login_screen'][$field]):'';
                ?>
                <th scope="row"><label for="<?php echo $field;?>"><?php _e('Logo Image',self::LANG);?></label></th>
                <td>
                    <input type="text" id="<?php echo $field;?>" class="regular-text" value="<?php echo $val;?>" name="login_screen[<?php echo $field;?>]">
                    <input type="button" class="button button-secondary" value="Upload" name="ls_upload">
                </td>

            </tr>
            <tr valign="top">
                <?php $field="text";
                $val=(isset($Options['login_screen'][$field]))? esc_html($Options['login_screen'][$field]):'';
                ?>
                <th scope="row"><label for="<?php echo $field;?>"><?php _e('Logo Text',self::LANG);?></label></th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $field;?>" value="<?php echo $val;?>" name="login_screen[<?php echo $field;?>]">
                </td>
            </tr>
            </tbody>
            <tr valign="top">
                <?php $field="title";
                $val=(isset($Options['login_screen'][$field]))? esc_html($Options['login_screen'][$field]):'';
                ?>
                <th scope="row"><label for="<?php echo $field;?>"><?php _e('Logo Title',self::LANG);?></label></th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $field;?>" value="<?php echo $val;?>" name="login_screen[<?php echo $field;?>]">

                </td>

            </tr>
            <tr valign="top">
                <?php $field="url";
                $val=(isset($Options['login_screen'][$field]))? esc_html($Options['login_screen'][$field]):'';
                ?>
                <th scope="row"><label for="<?php echo $field;?>"><?php _e('Logo URL',self::LANG);?></label></th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $field;?>" value="<?php echo $val;?>" name="login_screen[<?php echo $field;?>]">

                </td>
            </tr>
            <tr valign="top">
                <?php $field="remember_me";
                $val=(isset($Options['login_screen'][$field]))? $Options['login_screen'][$field]:0;
                ?>
                <th scope="row"><label for="<?php echo $field;?>"><?php _e('Remove Remember Me',self::LANG);?></label></th>
                <td>
                    <input type="checkbox" id="<?php echo $field;?>" value="1" name="login_screen[<?php echo $field;?>]" <?php checked($val,1)?>>

                </td>

            </tr>
            <tr valign="top">
                <?php $field="lost_password";
                $val=(isset($Options['login_screen'][$field]))? $Options['login_screen'][$field]:0;
                ?>
                <th scope="row"><label for="<?php echo $field;?>"><?php _e('Remove Lost Password Link',self::LANG);?></label></th>
                <td>
                    <input type="checkbox" id="<?php echo $field;?>" value="1" name="login_screen[<?php echo $field;?>]" <?php checked($val,1)?>>

                </td>

            </tr>
            <tr valign="top">
                <?php $field="back_to";
                $val=(isset($Options['login_screen'][$field]))? $Options['login_screen'][$field]:0;
                ?>
                <th scope="row"><label for="<?php echo $field;?>"><?php _e('Remove Back to.... Link',self::LANG);?></label></th>
                <td>
                    <input type="checkbox" id="<?php echo $field;?>" value="1" name="login_screen[<?php echo $field;?>]" <?php checked($val,1)?>>

                </td>

            </tr>

        </table>
        <h4><?php _e('Footer',self::LANG);?></h4>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <?php $field="footer_text";
                $val=(isset($Options['login_screen'][$field]))? esc_html($Options['login_screen'][$field]):'';
                ?>
                <th scope="row"><label for="<?php echo $field;?>"><?php _e('Footer Text',self::LANG);?></label></th>
                <td>
                    <input type="text" id="<?php echo $field;?>" class="regular-text" value="<?php echo $val;?>" name="login_screen[<?php echo $field;?>]">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
        <p class="submit">
            <input type="submit" name="save_login_screen" class="button button-primary"  value="<?php _e('Save Changes',self::LANG);?>">
            <input type="submit" name="reset_login_screen" class="button button-primary"  value="<?php _e('Reset',self::LANG);?>">
        </p>
    </form>
</div>