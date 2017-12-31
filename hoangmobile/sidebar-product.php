<?php global $shortname; ?>

<div class="leftColumn">
    <div style="margin:10px 0 2px;" class="spTitleRight"><a>Hỗ trợ trực tuyến</a></div>
    <div style="margin:0;">
        <div class="rightBgTopOr"></div>
        <div class="rightBgCenterOr">
            <?php include 'box-support.php'; ?>
        </div>
        <div class="rightBgBottomOr"></div>
        <div class="clearBoth"></div>
    </div>
    
    <div class="tygia"><?php include 'box-tygia.php'; ?></div>
    
    <div style="height:250px;margin:20px 0 14px;" class="sharefacebook">
        <?php include 'box-like-fb.php'; ?>
    </div>
    
    <?php if ( function_exists('dynamic_sidebar') ) { dynamic_sidebar('sidebar'); } ?>
</div>
