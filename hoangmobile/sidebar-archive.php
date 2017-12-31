<div class="vung1Right mt8">

    <div class="spTitleRightOr"><a>Đọc nhiều</a></div>
    <div class="rightContent">
        <div class="rightBgTopOr"></div>
        <div class="rightBgCenterOr">
            <div class="commentInside">
                <?php
                $loop = new WP_Query(array(
                            'post_type' => 'post',
                            'posts_per_page' => 10,
                            'orderby' => 'meta_value_num',
                            'meta_key' => 'views',
                            'order' => 'DESC',
                        ));
                $counter = 1;
                ?>
                <?php while ($loop->have_posts()) : $loop->the_post(); ?> 
                    <div class="commentCol1">
                        <a href="<?php the_permalink(); ?>">
                            <img style="width:49px;" class="noborder" src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=49"></a>
                    </div>
                    <div style="font-size: 11px" class="commentCol2Title mt0">
                        <a href="<?php the_permalink(); ?>">
                            <?php echo get_short_content(get_the_title(), 40); ?>
                        </a>
                    </div>
                    <div class="clearBoth"></div>
                    <?php if ($counter != $loop->post_count): ?>
                        <div class="line3"></div>
                <?php   endif;
                    $counter++;
                endwhile; ?>
                <?php wp_reset_query(); ?>
            </div>
            <!--commentInside--->
        </div>
        <!--rightBgCenterOr--->
        <div class="rightBgBottomOr"></div>
    </div>
    <!---rightContent--->
    <div class="clearBoth"></div> 

</div>
<!---vung1Right--->