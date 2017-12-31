<?php get_header(); ?>

<?php include 'left_menu_product.php'; ?>
<!--left menu-->

<div class="center">
    <div id="showloadingim" style="display: none" class="ajax-loading-block-window">
        <div class="loading-image"></div>
    </div>
    <!------------------------------------ menu danh muc trai -------------------------------------------------> 
    <div style="margin-top:0" class="col1020">
        
        <?php get_sidebar('news'); ?>
        
        <div class="colBig1">
            <?php while (have_posts()) : the_post(); ?>
            <div class="newsBgTitle1"><?php the_title(); ?></div>
            <div style="width:800px" class="spLine"></div>
            <div style="color:#E06F1B;font-style:italic;" class="spCat mt0">Ngày cập nhật: <?php the_time('H:s - d/m/Y'); ?></div>
            <div class="clearBoth"></div>
            
            <div class="newsArticle">
                <?php the_content(''); ?>
            </div>
            <div class="clearBoth"></div>

            <div class="social_box mt20 mb20">
                <!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox addthis_default_style ">
                <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                <a class="addthis_button_tweet"></a>
                <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
                <a class="addthis_counter addthis_pill_style"></a>
                </div>
                <script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e5a517830ae061f"></script>
                <!-- AddThis Button END -->
            </div>
            
            <?php endwhile; ?>
        </div><!--colBig1-->
        <div class="clearBoth"></div>
    </div><!--col1020-->
    <div class="clearBoth"></div>
</div>

</div>

<?php get_footer(); ?>