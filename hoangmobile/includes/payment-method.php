<?php

$payment_fields = array(
    "payment_cashOnDelivery", "payment_atm", "payment_postOffice",
);

add_action('admin_menu', 'add_payment_settings_page');

function add_payment_settings_page(){
    global $menuname, $payment_fields;
    
    add_submenu_page($menuname, //Menu ID – Defines the unique id of the menu that we want to link our submenu to. 
                                    //To link our submenu to a custom post type page we must specify - 
                                    //edit.php?post_type=my_post_type
            __('Payment Methods'), // Page title
            __('Payment Methods'), // Menu title
            'edit_themes', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            'payment_options', // Submenu ID – Unique id of the submenu.
            'theme_payment_settings_page' // render output function
        );
    
    if ($_GET['page'] == 'payment_options') {
        if (isset($_REQUEST['action']) and 'save' == $_REQUEST['action']) {
            foreach ($payment_fields as $field) {
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

function theme_payment_settings_page() {
    global $themename;
?>
    <div class="wrap">
        <div class="opwrap" style="margin-top: 10px;" >
            <div class="icon32" id="icon-options-general"></div>
            <h2 class="wraphead"><?php echo $themename; ?> payment settings</h2>
    <?php
    if (isset($_REQUEST['saved']))
        echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' settings saved.</strong></p></div>';
    ?>
            <form method="post">
                <table class="form-table">
                   <tr>
                        <th><label for="payment_cashOnDelivery">Cash On Delivery</label></th>
                        <td>
                            <?php 
                            wp_editor(stripslashes(get_settings('payment_cashOnDelivery')), 'payment_cashOnDelivery', array(
                                'textarea_name' => 'payment_cashOnDelivery',
                            ));
                            ?>
                            <br />
                            <span class="description">Thanh toán khi nhận hàng (COD).</span>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="payment_atm">ATM</label></th>
                        <td>
                            <?php 
                            wp_editor(stripslashes(get_settings('payment_atm')), 'payment_atm', array(
                                'textarea_name' => 'payment_atm',
                            ));
                            ?>
                            <br />
                            <span class="description">Chuyển khoản qua tài khoản ATM.</span>
                        </td>
                    </tr>
                   <tr>
                        <th><label for="payment_postOffice">Post Office</label></th>
                        <td>
                            <?php 
                            wp_editor(stripslashes(get_settings('payment_postOffice')), 'payment_postOffice', array(
                                'textarea_name' => 'payment_postOffice',
                            ));
                            ?>
                            <br />
                            <span class="description">Thanh toán qua đường Bưu điện.</span>
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
    <script type="text/javascript">/* <![CDATA[ */
        jQuery(function($){
            $(".submit input[type='submit']").click(function(){
                tinyMCE.triggerSave();
            });
        });
        /* ]]> */
    </script>
<?php
}