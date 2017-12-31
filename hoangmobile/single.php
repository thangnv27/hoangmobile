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
            <div style="color:#E06F1B;font-style:italic;" class="spCat mt0">
                Ngày cập nhật: <?php the_time('H:s - d/m/Y'); ?>
            </div>
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
            
            <div class="comment_box mb10">
                <h2 class="redTitletinkhac">Bình luận</h2>
                <div class="comment_box_ct">
                    <div class="fb-comments" data-href="<?php echo getCurrentRquestUrl(); ?>" data-width="800" data-num-posts="10"></div>
                </div>
            </div>
            <!--/.comment_box-->
            
            <div class="redTitletinkhac">Tin khác</div>
            <div class="spLine"></div>
            <div class="otherNews">
                <?php
                $categories = get_the_category();
                $cat = array();
                foreach ($categories as $category) {
                    array_push($cat, $category->term_id);
                }
                $excludeID = array();
                array_push($excludeID, get_the_ID());
                $args = array(
                    'post_type' => 'post',
                    'post__not_in' => $excludeID,
                    'posts_per_page' => 6,
                    //'orderby' => 'rand',
                    'category__in' => $cat,
                );
                $loop = new WP_Query($args);
                $counter = 1;
                while ($loop->have_posts()) : $loop->the_post();
                    ?>
                    <div class="twoCol1Row">
                        <div class="twoCol1Pic">
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=100&h=85">
                            </a>
                        </div>
                        <div class="twoCol1Text">
                            <a href="<?php the_permalink(); ?>" style="color:#E06F1B;font-weight:bold;">
                                <?php echo get_short_content(get_the_title(), 40); ?>
                            </a><br>
                            <i>Đăng ngày <?php the_time('d/m/Y'); ?></i><br />
                            <p style="width: 250px;"><?php echo get_short_content(get_the_content(), 95); ?></p>
                        </div>
                        <div class="clearBoth"></div>
                    </div>
                <?php if($counter % 2 != 0): ?>
                    <div style="height: 110px" class="newsLine1"></div>
                <?php endif; ?>
                <?php $counter++; endwhile; ?>
                <?php wp_reset_query(); ?>    
            </div>
            <!---otherNews---->
            <?php endwhile; ?>
        </div><!--colBig1-->
        <div class="clearBoth"></div>
    </div><!--col1020-->
    <div class="clearBoth"></div>
</div>

</div>

<?php get_footer(); ?>