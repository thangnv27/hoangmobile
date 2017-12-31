<div class="leftBg">
    <div class="leftBgTop"></div>
    <div class="leftBgCenter">
        <div class="leftMenufrt pdb0">
            <?php 
            $leftMenu = wp_nav_menu(array(
                'menu' => 'Left Menu Archive',
                'container' => '',
                'menu_class' => 'newsmenu1level',
                'menu_id' => 'nav',
                'fallback_cb' => '__return_false'
            )); 
            if(!empty($leftMenu)){
                echo $leftMenu;
            }
            ?>
        </div>
        <div class="clearBoth"></div>
    </div>
    <div class="leftBgBottom"></div>
    
    <div style="margin:20px 0 2px;" class="spTitleRight"><a>Hỗ trợ trực tuyến</a></div>
    <div style="margin:0;">
        <div class="rightBgTopOr"></div>
        <div class="rightBgCenterOr">
            <?php include 'box-support.php'; ?>
        </div>
        <div class="rightBgBottomOr"></div>
    </div>
    
    <div style="height:250px;margin:20px 0 14px;" class="sharefacebook">
        <?php include 'box-like-fb.php'; ?>
    </div>
    
</div><!--leftBg-->