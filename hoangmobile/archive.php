<?php get_header(); ?>

<?php include 'left_menu_product.php'; ?>

<!--MAIN-->
<div class="center">
    <div id="showloadingim" style="display: none" class="ajax-loading-block-window">
        <div class="loading-image"></div>
    </div>

    <div class="mainNews mt0">
        <!--main left-->
        <div class="vung1Left">
            <div style="float: left; background-color: #d61311; margin-top: 2px; height: 18px;">
                <a style="color:#ffffff; margin-left:3px; font-weight:bold;"><?php echo single_cat_title(); ?></a>
            </div>
            <div class="Newscatetilte" style="float: left;"></div>
            <div class="listspNews"></div>
            <div class="spLine"></div>
            <div class="clearBoth"></div>

            <div style="margin-top: 14px;" id="data">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="newsContent">
                        <div class="newsContentLeft">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <img src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=347" />
                            </a>
                        </div>
                        <div class="newsContentRight">
                            <div class="newsBgTitle">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            </div>
                            <i>Ngày đăng: <?php the_time('d/m/Y'); ?></i>
                            <br>
                            <p><?php the_excerpt(); ?></p>
                            <div class="linkstylenews"><a href="<?php the_permalink(); ?>">Xem chi tiết</a></div>
                        </div>
                        <div class="clearBoth"></div>
                        <div class="newsSpace"></div>
                        <div class="clearBoth"></div>
                    </div> 
                <?php endwhile; ?>
            </div>
            <div style="height: 22px;" class="seeMore" id="seeMore"><!--Hiển thị thêm ↓--></div>
            <div style="margin-top:-22px !important;" class="mb20">
                <?php if(function_exists('getpagenavi')){ getpagenavi(); } ?>
            </div>
        </div>
        <!---/vung1Left----->
        
        <?php get_sidebar('archive'); ?>
        
    </div>
    <!---mainNews--->  
</div>

</div>

<?php get_footer(); ?>