<?php get_header(); ?>

<?php include 'left_menu_product.php'; ?>

<div id="main">
    <div class="breadcrums"><?php if (function_exists('bcn_display')) { bcn_display(); } ?></div>
    <div id="frtCatalogSearch">
        
        <?php get_sidebar('product'); ?>
        <!--/.leftColumn-->
        
        <div class="rightColumn"> 
            <div class="banner-catagory ml10">
                <?php 
                if(get_settings('ad_product_category_active') == 1):
                ?>
                <a href="<?php echo get_option('ad_product_category_link'); ?>">
                    <img style="width: 807px;" src="<?php echo stripslashes(get_option('ad_product_category'));  ?>" alt="">
                </a>
                <?php endif; ?>
            </div>
            
            <!--<div style="height:63px;" id="comparepr">
                <div style="height:63px !important;" class="compare">
                    <div class="compareTitle">So sánh <br>sản phẩm</div>
                    <div class="compareBoard">
                        <div class="compareContent"><h1><a href="/samsung-galaxy-ace-duos-s6802-p3025" class="link"> Samsung Galaxy Ace Duos S6802</a></h1></div>
                        <div id="3025 listp" class="compareClose"></div>
                    </div>
                    <div class="compareLine"></div>
                    
                    <input type="button" id="compareproduct" class="compareBtn">
                </div>
            </div>-->
            <!--/#comparepr-->

            <div class="spShow">
                <form method="post" action="">
                    <?php 
                    $taxonomy = 'product_category';
                    $term = get_queried_object(); 
                    ?>
                    <div id="spShow">
                        <!--MENU SAP XEP--------->
                        <div class="spArrange">Sắp xếp theo :
                            <a rel="nofollow" href="<?php echo get_term_link($term, $taxonomy); ?>?orderby=price&order=DESC" data-ajax-update="#spShow" data-ajax-mode="replace" data-ajax-method="GET" data-ajax-loading="#ajax_loading" data-ajax="true">Giá cao đến thấp</a> | 
                            <a rel="nofollow" href="<?php echo get_term_link($term, $taxonomy); ?>?orderby=price&order=ASC" data-ajax-update="#spShow" data-ajax-mode="replace" data-ajax-method="GET" data-ajax-loading="#ajax_loading" data-ajax="true">Giá thấp đến cao</a> | 
                            <a rel="nofollow" href="<?php echo get_term_link($term, $taxonomy); ?>?orderby=views&order=DESC" data-ajax-update="#spShow" data-ajax-mode="replace" data-ajax-method="GET" data-ajax-loading="#ajax_loading" data-ajax="true">Xem nhiều nhất</a> | 
                            <a rel="nofollow" href="<?php echo get_term_link($term, $taxonomy); ?>?orderby=is_most&order=DESC" data-ajax-update="#spShow" data-ajax-mode="replace" data-ajax-method="GET" data-ajax-loading="#ajax_loading" data-ajax="true">Bán chạy nhất </a>
                        </div>
                        <!--END -MENU SAP XEP--------->
                        
                        <!----XEM THEO GRID_LIST--->
                        <div class="spView"></div>
                        <div class="clearBoth"></div>
                        <div class="spLine"></div>
                        <div class="clearBoth"></div>
                        
                        <?php
                        global $wp_query;
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $args = array(
                            'post_type' => 'product',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => $taxonomy,
                                    'field' => 'id',
                                    'terms' => $term->term_id,
                                )
                            ),
                            'posts_per_page' => 30,
                            'paged' => $paged,
                        );
                        if(getRequest('orderby') != "" and getRequest('order') != ""){
                            $orderby = getRequest('orderby');
                            if(getRequest('orderby') == "price"){
                                $orderby = "gia_moi";
                            }
                            $args['orderby'] = "meta_value_num";
                            $args['meta_key'] = $orderby;
                            $args['order'] = getRequest('order');
                        }else{
                            $args['orderby'] = "meta_value_num";
                            $args['meta_key'] = 'gia_moi';
//                            $args['meta_key'] = 'sp_displayorder';
                            $args['order'] = 'DESC';
                        }
                        query_posts($args);
                        ?>
                        <div class="numPro">Có <?php echo $wp_query->found_posts; ?> sản phẩm trong mục này</div>

                        <!-------SHOWSP------->
                        <div class="spFour">
                            <ul id="ulpdl" class="grid">
                                <?php while(have_posts()) : the_post(); ?>
                                <li class="item">
                                    <div class="spInside" onmouseout="UnTip()" onmouseover="showToolTip('<?php the_ID(); ?>')">
                                        <div class="spInsideImg">
                                            <?php 
                                            $sp_flagged = get_post_meta(get_the_ID(), "sp_flagged", true); 
                                            if($sp_flagged and $sp_flagged != "" ):
                                            ?>
                                            <span class="is_flagged">
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icons/<?php echo $sp_flagged; ?>.gif" />
                                            </span>
                                            <?php
                                            endif;
                                            ?>
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                <img class="noborder" alt="<?php the_title(); ?>" 
                                                     src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=125&h=125" />
                                            </a>
                                        </div>
                                        <div style="position:inherit" class="tiff"></div>
                                        <div class="spInsideText">
                                            <div class="spTextTitle">
                                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                            </div>
                                            <div class="spTextLoaiHang">
                                                <?php echo get_post_meta(get_the_ID(), "loai_hang", true); ?>
                                            </div>
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
                                            <div class="mt5">
                                                <?php if(function_exists('the_ratings_results')) { echo the_ratings_results(get_the_ID()); } ?>
                                            </div>
                                            <!--<div style="margin-left: 51px" class="star">
                                                <div style="width:64px" class="starlight"></div>
                                            </div>-->
                                        </div>
                                        <!--<div class="checkCompare">
                                            <input type="checkbox" id="<?php the_ID(); ?>" class="comparepid"> So sánh sản phẩm       
                                        </div>-->
                                    </div>
                                </li>
                                <?php endwhile; ?>
                                <div class="clearBoth"></div>
                            </ul>
                        </div>
                        <!--/.spFour-->

                        <div style="height: 22px;" id="loadmorepro" class="seeMorePro"><!--Hiển thị thêm ↓--></div>
                        <div style="margin-top:-23px !important;">
                            <?php if(function_exists('getpagenavi')){ getpagenavi(); } ?>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <!--/.rightColumn-->
    </div>
</div>

</div><!--/.wrapper-->

<?php get_footer(); ?>