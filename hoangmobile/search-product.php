
<?php get_header(); ?>

<?php include 'left_menu_product.php'; ?>

<div id="main">
    <div class="breadcrums"><?php if (function_exists('bcn_display')) { bcn_display(); } ?></div>
    <div id="frtCatalogSearch">
        
        <?php get_sidebar('product'); ?>
        <!--/.leftColumn-->
        
        <div class="rightColumn">
            <div class="banner-catagory"></div>
            
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
                    <div id="spShow">
                        <?php
                        global $wp_query;
                        $taxonomy = 'product_category';
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $args = array(
                            'post_type' => 'product',
                            'posts_per_page' => 30,
                            'paged' => $paged,
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                            'meta_key' => 'gia_moi',
                            'tax_query' => array(),  
                        );
                        $category = intval(getRequest('category'));
                        if($category > 0){
                            array_push($args['tax_query'], array(
                                'taxonomy' => $taxonomy,
                                'field' => 'id',
                                'terms' => $category,
                            ));
                        }
                        $factor = intval(getRequest('factor'));
                        if($factor > 0){
                            array_push($args['tax_query'], array(
                                'taxonomy' => $taxonomy,
                                'field' => 'id',
                                'terms' => $factor,
                            ));
                        }
                        $price = getRequest('price');
                        if ($price != "") {
                            $arr = explode('-', $price);
                            $args['meta_query'] = array(
                                array(
                                    'key' => 'gia_moi',
                                    'value' => $arr,
                                    'compare' => 'BETWEEN',
                                    'type' => 'NUMERIC'
                                ),
                            );
                        }
                        query_posts($args);
                        ?>
                        
                        <div class="numPro">Tìm thấy <?php echo $wp_query->found_posts; ?> sản phẩm</div>
                        <div class="spLine"></div>
                        <div class="clearBoth"></div>
                        
                        <!-------SHOWSP------->
                        <div class="spFour">
                            <ul id="ulpdl" class="grid">
                                <?php while(have_posts()) : the_post(); ?>
                                <li class="item">
                                    <div class="spInside" onmouseout="UnTip()" onmouseover="showToolTip('<?php the_ID(); ?>')">
                                        <div class="spInsideImg">
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