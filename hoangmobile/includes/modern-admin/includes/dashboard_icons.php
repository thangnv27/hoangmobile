<?php
global $wpdb;
$dw= get_option($wpdb->prefix.'modern_admin_dashboard_widget_registered');
if($dw==false){
    $dw=array('dashboard_right_now','dashboard_plugins','dashboard_plugins','dashboard_quick_press','dashboard_recent_drafts',);
    $d=get_option("dashboard_widget_options");
    if(is_array($d))
        foreach($d as $key =>$v)
            array_push($dw,$key);
}

$Options = $this->getOptions();
if(!in_array("custom_db_widget",$dw))
    array_push($dw,"custom_db_widget");

if (isset($_POST['save_dashboard_widget'])){
    $custom_db_widget=array();
    if(isset($_POST['custom_db_widget'])){
        foreach($_POST['custom_db_widget'] as $key => $value){
            $custom_db_widget[$key]=stripslashes($value);
        }
        $Options['custom_db_widget'] = $custom_db_widget;
        update_option($this->OptionsName, $Options);
    }
    ?>
    <div class="updated"><p><strong><?php _e('Settings Updated',self::LANG);?>.</strong></p></div>
<?php
}
if (isset($_POST['save_dashboard_icons']))
{
    $dashboard_icons=array();
    if(isset($_POST['dashboard_icons'])){

        foreach($_POST['dashboard_icons'] as $key => $value){
            $icon=(isset($value["icon"]))?$value["icon"]:'';
            $show=(isset($value["show"]))? $value["show"]:0;
            $dashboard_icons[$key]['icon']=$icon;
            $dashboard_icons[$key]['show']=$show;

        }
        $Options['dashboard_icons'] = $dashboard_icons;
        update_option($this->OptionsName, $Options);
    }
?>
    <div class="updated"><p><strong><?php _e('Settings Updated',self::LANG);?>.</strong></p></div>
<?php
}
if(isset($_POST['reset_dashboard_icons'])){
    unset($Options['dashboard_icons']);
    update_option($this->OptionsName, $Options);
    ?>
    <div class="updated"><p><strong><?php _e('Reset Ok',self::LANG);?>.</strong></p></div>
<?php
}
if(isset($_POST['reset_dashboard_widget'])){
    unset($Options['custom_db_widget']);
    update_option($this->OptionsName, $Options);
    ?>
    <div class="updated"><p><strong><?php _e('Reset Ok',self::LANG);?>.</strong></p></div>
<?php
}
?>
<div class="wrap">
    <div class="icon32" id="icon-tools"><br></div>
    <h2><?php _e('Dashboard Settings',self::LANG);?></h2>
    <ul class="dashboard-tab">
        <li><a href="#dashboard_icons" class="active">Icons</a></li>
        <li><a href="#dashboard_widget">Custom Dashboard Widget</a></li>
        <li class="clear"></li>
    </ul>
    <div id="dashboard_widget" style="display: none">
        <form action="" method="post">

            <div class="clearfix">
                <table id="mordern-admin-icons-table" class="form-table" >
                    <tbody>
                    <tr valign="top">
                        <?php $field="title";
                        $value=(isset($Options['custom_db_widget'][$field]))?$Options['custom_db_widget'][$field]:'Your Custom Widget Title';
                        ?>
                        <td style="width: 100px"><label for="custom_db_widget[<?php echo $field;?>]"><?php _e('Title',self::LANG);?></label></td>
                        <td><input type="text" name="custom_db_widget[<?php echo $field;?>]" class="modern-admin-custom-dashboard-text" value="<?php echo $value;?>"></td>
                    </tr>
                    <tr valign="top">
                        <?php $field="content";
                        $value=(isset($Options['custom_db_widget'][$field]))?$Options['custom_db_widget'][$field]:'Your Custom Widget Content';
                        ?>
                        <td><label for="custom_db_widget[<?php echo $field;?>]"><?php _e("Content",self::LANG);?></label><br />
                        <i><?php _e("(Text or HTML content)",self::LANG);?></i>
                        </td>
                        <td>
                            <textarea name="custom_db_widget[<?php echo $field;?>]" class="modern-admin-custom-dashboard-textarea"><?php echo $value;?></textarea>
                        </td>

                    </tr>
                    </tbody>
                </table>

            </div>

            <p class="submit">
                <input type="submit" value="<?php _e('Save Changes',self::LANG);?>" class="button button-primary" id="save_dashboard_widget" name="save_dashboard_widget">
                <input type="submit" value="<?php _e('Reset',self::LANG);?>" class="button button-primary" name="reset_dashboard_widget">
            </p>
        </form>
    </div>
    <div id="dashboard_icons" style="display: block">
        <h5><?php _e('Note: If you could not see some dashboard widgets, please enter to Dashboard Home then return and set icons for them!',self::LANG);?></h5>
        <form action="" method="post">

            <div class="clearfix">
                <table id="mordern-admin-icons-table" class="modern-table-left form-table" >
                    <tbody>
                    <tr valign="top">
                        <th scope="row">Show</th>
                        <td>Name</td>
                        <td>Icon</td>
                    </tr>
                    <?php


                    foreach($dw as $key){
                        $name = ucwords(str_replace(array("dashboard","_"),array(""," "),$key));
                        if($key=='dashboard_primary') $name='Wordpress Blog';
                        if($key=='dashboard_secondary') $name="Other Wordpress News";
                        $show=(isset($Options['dashboard_icons'][strip_tags($key)]['show']))? $Options['dashboard_icons'][strip_tags($key)]['show']:1;
                        $val=(isset($Options['dashboard_icons'][strip_tags($key)]['icon']))? $Options['dashboard_icons'][strip_tags($key)]['icon']:'';
                        if($key=='custom_db_widget'){
                            if(!empty($Options['custom_db_widget']['title']))
                                $name=$Options['custom_db_widget']['title'];
                            if(!isset($Options['dashboard_icons']['custom_db_widget']['show'])) $show=0;
                        }

                        ?>

                        <tr valign="top">

                            <th scope="row"><input type="checkbox" value="1" name="dashboard_icons[<?php echo strip_tags($key);?>][show]" <?php checked($show,1);?>></th>
                            <td><label for="<?php echo $key;?>"><?php echo $name;?></label></td>
                            <td><a href="#"><i class="md-icon modern-admin-<?php echo strip_tags($key);?> icon-star"></i><input type="hidden" class="regular-text" value="<?php echo $val;?>" id="<?php echo $key;?>" name="dashboard_icons[<?php echo strip_tags($key);?>][icon]"></a></td>
                        </tr>
                    <?php
                    }
                    ?>

                    </tbody>
                </table>
                <?php include("icons_list.php");?>
            </div>

            <p class="submit">
                <input type="submit" value="<?php _e('Save Changes',self::LANG);?>" class="button button-primary" id="save_dashboard_icons" name="save_dashboard_icons">
                <input type="submit" value="<?php _e('Reset',self::LANG);?>" class="button button-primary" name="reset_dashboard_icons">
            </p>
        </form>
    </div>

</div>
