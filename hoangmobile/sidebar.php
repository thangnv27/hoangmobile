<div class="vung1Right">
    <div style="margin-bottom:2px" class="spTitleRight"><a>Hỗ trợ trực tuyến</a></div>
    <div style="margin:0 0 0 9px;">
        <div class="rightBgTopOr"></div>
        <div class="rightBgCenterOr">
            <?php include 'box-support.php'; ?>
        </div>
        <div class="rightBgBottomOr"></div>
        <div class="clearBoth"></div>
    </div>
    
    <div class="tygia pdl9"><?php include 'box-tygia.php'; ?></div>
    
    <div style="height:250px;margin:20px 0 14px 9px" class="sharefacebook">
        <?php include 'box-like-fb.php'; ?>
    </div>

    <div class="spTitleRight"><a>Sản phẩm bán chạy</a></div>
    <div class="rightContent">
        <div class="rightBgTopOr"></div>
        <div class="rightBgCenterOr">
            <div class="rightContentInside">
                <?php
                global $wp_query;
                query_posts( array ( 
                    'post_type' => 'product', 
                    'meta_query' => array(
                        array(
                            'key' => 'is_most',
                            'value' => '1',
                        )
                    ),
                    'posts_per_page' => -1,
                ));
                $counter = 1;
                while (have_posts()) : the_post();
                ?>
                <div class="spRightImg">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <img title="Hiển thị thông tin chi tiết cho <?php the_title(); ?>" 
                             src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=100&h=90" 
                             alt="Hình ảnh của <?php the_title(); ?>" class="noborder" />
                    </a>
                </div>
                <div class="tiff" style="float:left;position:inherit"></div>
                <div class="spRightText">
                    <div class="spTextTitle">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </div>
                    <?php $loaihang = get_post_meta(get_the_ID(), "loai_hang", true); ?>
                    <?php if($loaihang != ""): ?>
                    <div class="spTextLoaiHang"><?php echo $loaihang; ?></div>
                    <?php endif; ?>
                    <div class="spTextoldprice">
                        <?php 
                            $price_old = trim(get_post_meta(get_the_ID(), "gia_cu", true));
                            if($price_old != ""){
                                echo number_format(floatval($price_old),0,',','.') . " đ"; 
                            }
                        ?>
                    </div>
                    <div class="spTextPrice">
                        <?php 
                            $price = trim(get_post_meta(get_the_ID(), "gia_moi", true));
                            echo number_format(floatval($price),0,',','.') . " đ"; 
                        ?>
                    </div>
                </div>
                <div class="clearBoth"></div>
                <div class="rating mt5 t_center">
                    <?php if(function_exists('the_ratings_results')) { echo the_ratings_results(get_the_ID()); } ?>
                </div>

                <!--<div class="star"><div style="width:45px" class="starlight"></div></div>-->
                <?php if ($counter != $wp_query->found_posts): ?>
                <div class="line2"></div>
                <?php endif; ?>
                <?php $counter++; endwhile;?>
                <?php wp_reset_query(); ?>
            </div>
            <!---rightContentInside-->
        </div>
        <!---rightBgCenter-->
        <div class="rightBgBottomOr">
        </div>
    </div> 

    <div style="margin-bottom:2px" class="spTitleRight"><a>Thống kê truy cập</a></div>
    <div class="rightContent">
        <div class="rightBgTopOr"></div>
        <!-------------------------------------------------------------------------------------> 
        <div class="rightBgCenterOr">
            <div class="commentInside">
                <ul class="wp-stats">
                    <li>Đang online: <?php echo wp_statistics_useronline(); ?></li>
                    <li>Hôm nay: <?php echo wp_statistics_visit('today'); ?></li>
                    <li>Tháng hiện tại: <?php echo wp_statistics_visit('month'); ?></li>
                    <li>Tổng truy cập: <?php echo wp_statistics_visit('total'); ?></li>
                </ul>
            </div>
            <!---commentInside-->
        </div>
        <!---rightBgCenter-->
        <!------------------------------------------------------------------------------------->
        <div class="rightBgBottomOr">
        </div>
    </div>
    <!---rightContent-->

    <?php if ( function_exists('dynamic_sidebar') ) { dynamic_sidebar('sidebar'); } ?>
</div>