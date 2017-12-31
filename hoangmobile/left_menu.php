<?php 
$dmsp = wp_nav_menu(array(
    'menu' => 'Danh Muc San Pham',
    'container' => '',
    'menu_class' => 'ur',
    'fallback_cb' => '__return_false'
)); 
if(!empty($dmsp)){
    echo $dmsp;
}
?>
<div class="leftMenuBgBottom"></div>
