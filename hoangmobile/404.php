<?php get_header(); ?>

<?php include 'left_menu_product.php'; ?>

<div class="center">
    <div id="showloadingim" style="display: none" class="ajax-loading-block-window">
        <div class="loading-image"></div>
    </div>
    <!------------------------------------ menu danh muc trai -------------------------------------------------> 
    <div style="margin-top:0" class="col1020">
        
        <div class="page-404">
            <img alt="404" src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/404.png" />
            <div class="message">
                <p>Không tìm thấy nội dung</p>
                <p>Quay lại <a href="<?php bloginfo( 'siteurl' ); ?>">trang chủ?</a>.</p>
            </div>
        </div>
        
    </div><!--col1020-->
    <div class="clearBoth"></div>
</div>

</div>

<?php get_footer(); ?>