<?php

$user_fields = array(
    "user_phone", "user_dob_day", "user_dob_month", "user_dob_year", "user_gender", "user_address", 
);
$user_gender = array(
    0 => 'Nữ',
    1 => 'Nam',
);

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { 
    global $user_gender;
    ?>
    <h3>Extra profile information</h3>
    <table class="form-table">
        <tr>
            <th><label for="user_phone">Phone number</label></th>
            <td>
                <input type="text" name="user_phone" id="user_phone" value="<?php echo esc_attr(get_the_author_meta('user_phone', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="user_date_of_birth">Dat of birth</label></th>
            <td>
                <span style="float: left;">
                    <input type="text" name="user_dob_day" id="user_dob_day" value="<?php echo esc_attr(get_the_author_meta('user_dob_day', $user->ID)); ?>" size="12" /><br />
                    <span class="description">Ngày</span>
                </span>
                <span style="float: left;">
                    <input type="text" name="user_dob_month" id="user_dob_month" value="<?php echo esc_attr(get_the_author_meta('user_dob_month', $user->ID)); ?>" size="12" /><br />
                    <span class="description">Tháng</span>
                </span>
                <span style="float: left;">
                    <input type="text" name="user_dob_year" id="user_dob_year" value="<?php echo esc_attr(get_the_author_meta('user_dob_year', $user->ID)); ?>" size="13" /><br />
                    <span class="description">Năm</span>
                </span>
                <div style="clear: both;"></div>
            </td>
        </tr>
        <tr>
            <th><label for="user_gender">Gender</label></th>
            <td>
                <select name="user_gender" id="user_gender" style="width: 15em;">
                    <?php
                    foreach ($user_gender as $key => $value) {
                        if(esc_attr(get_the_author_meta('user_gender', $user->ID)) == $key){
                            echo '<option value="'.$key.'" selected="selected">'.$value.'</option>';
                        }else{
                            echo '<option value="'.$key.'">'.$value.'</option>';
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="user_address">Address</label></th>
            <td>
                <input type="text" name="user_address" id="user_address" value="<?php echo esc_attr(get_the_author_meta('user_address', $user->ID)); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
<?php 
}

function my_save_extra_profile_fields($user_id) {
    global $user_fields;

    if (!current_user_can('edit_user', $user_id))
        return false;

    foreach ($user_fields as $field) {
        update_usermeta($user_id, $field, $_POST[$field]);
    }
}