<?php get_header(); ?>

<div class="bannerArea">
    <?php include 'left_menu_product.php'; ?>
    
    <!--banner-->
    <div class="bigBanner">
        <?php 
        $loop = new WP_Query(array ( 'post_type' => 'slider', 'orderby' => 'meta_value_num', 'meta_key' => 'slide_order', 'order' => 'ASC' ));
        if($loop->post_count > 0) :
        ?>
        <div id="slider" class="nivoSlider">  
            <?php while ($loop->have_posts()) : $loop->the_post(); ?> 
            <a href="<?php echo get_post_meta(get_the_ID(), "slide_link", true);?>" target="_parent" rel="nofollow">
                <img src="<?php echo get_post_meta(get_the_ID(), "slide_img", true);?>" alt="<?php the_title(); ?>" rel="<?php the_title(); ?>"/>
            </a>
            <?php endwhile; ?>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#slider').nivoSlider({
                    controlNavTextFromRel: true, 
                    controlNavThumbs: true, 
                    directionNav: false, 
                    effect: 'slideInRight', 
                    animSpeed: 400
                });
            });
        </script> 
        <?php endif; ?>
        <?php wp_reset_query(); ?>
    </div>
</div>
<div class="clearBoth"></div>

<div class="banner-7"></div>

<form method="get" id="frmQuickSearch" action="<?php bloginfo( 'siteurl' ); ?>" novalidate="novalidate">
    <div class="quickSearchBg">
        <div class="quickSearchTitle">Tìm nhanh</div>
        <select name="category" id="ddlcat" class="quickSearchForm">
            <option value="">Chọn danh mục</option>
            <?php
            $args = array(
                'type' => 'product',
                'taxonomy' => 'product_category',
                'hide_empty' => 0,
                'parent' => 0,
            );
            $categories = get_categories( $args );
            foreach ($categories as $category) :
                echo "<option value=\"{$category->term_id}\">{$category->name}</option>";
            endforeach; 
            ?>
        </select> 
        <select class="quickSearchForm" id="ddlman" name="factor">
            <option value="">Chọn thương hiệu</option>
            <?php
            $args = array(
                'type' => 'product',
                'taxonomy' => 'product_factor',
                'hide_empty' => 0,
                'parent' => 0,
            );
            $factors = get_categories( $args );
            foreach ($factors as $factor) :
                echo "<option value=\"{$factor->term_id}\">{$factor->name}</option>";
            endforeach; 
            ?>
        </select>
        <select name="price" id="ddlPrice" class="quickSearchForm">
            <option value="">Chọn mức giá</option>
            <?php
            global $shortname;
            $prices = explode("\n", get_option($shortname . '_searchByPrice'));
            foreach ($prices as $price) {
                $price = trim($price);
                if($price != ""){
                    $arr = explode('-', $price);
                    $from = number_format($arr[0],0,',','.');
                    $to = number_format($arr[1],0,',','.');
                    echo "<option value=\"$price\">Từ $from đến $to</option>";
                }
            }
            ?>
        </select>

        <input type="submit" class="quickSearchBtn" value="" />
        <input type="hidden" name="post_type" value="product" />
        <input type="hidden" name="s" value="1" />
    </div>
</form>
<div class="clearBoth"></div>

<div id="showloadingim" style="display: none" class="ajax-loading-block-window">
    <div class="loading-image">
    </div>
</div>

<!--San pham KM dac biet-->
<?php
if(get_settings('sp_special_promotion_active') == 1):
$loop = new WP_Query(array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'is_highlights',
                    'value' => '1',
//                    'compare' => '!=',
                )
            ),
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'meta_key' => 'sp_displayorder',
        ));
if($loop->post_count > 0):
?>
<div class="board">
    <div class="lbl-promotion"><h2 class="lable">Sản phẩm nổi bật</h2></div>
    <div class="bound-promotion" id="slidePromotion">
        <ul class="jcarousel-skin-tango">
            <?php while($loop->have_posts()) : $loop->the_post(); ?>
            <li>
                <div class="image-slide">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="img">
                        <img rel="img_product" original="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=125&h=125" alt="<?php the_title(); ?>" 
                             onerror="this.src=no_image_src" width="125" height="125" class="noborder"
                             src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=125&h=125" />
                    </a>
                </div>
                <div class="brief-slide">
                    <h3 class="name"><a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="info-brief">Tình trạng: <span class="inventory">
                            <?php echo trim(get_post_meta(get_the_ID(), "tinh_trang", true)); ?>
                        </span></div>
                    <div class="price">
                        <?php 
                        $price = trim(get_post_meta(get_the_ID(), "gia_moi", true));
                        echo number_format(floatval($price),0,',','.') . " đ"; 
                        ?>
                    </div>
                    <div class="pro_note">
                        <?php echo trim(get_post_meta(get_the_ID(), "khuyen_mai", true)); ?>
                    </div>
                </div>
                <div class="clearBoth"></div>
            </li>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>
        </ul>
    </div>
    <div class="promotion-bottom">
        <marquee behavior="SCROLL" style="padding-top: 5px;"><?php echo get_option("slide_msg"); ?></marquee>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#slidePromotion ul').jcarousel({
            auto: 2,
            wrap: 'circular',
            scroll: 1
        });
    });
</script>
<?php endif;
endif;
?>
<!--/San pham dac biet-->

<div id="divCenter">
    <div class="vung1">
        <div class="vung1Left">
            
            <div class="block"></div>

            <div class="block">
                <?php 
                $boxArr = json_decode(get_option('cat_box1'));
                if(count($boxArr) > 0):
                $taxonomy = 'product_category';
                foreach ($boxArr as $catID) :
                    $category = get_term($catID, $taxonomy);
                ?>
                <div class="sp1">
                    <!-----------------------show Banner-------------------------------------------------------------->     
                    <div class="banner-4"></div>

                    <div class="spTitle">
                        <a href="<?php echo get_term_link($category, $taxonomy); ?>"><?php echo $category->name; ?></a>
                    </div>
                    <div class="spCat">
                        <?php
                        $subCats = get_categories( array(
                            'type' => 'product',
                            'taxonomy' => $taxonomy,
                            'child_of' => $category->term_id,
                            'hide_empty' => 0,
                            'orderby' => 'term_order',
                            'order' => 'ASC',
                        ));
                        foreach ($subCats as $child) :
                        ?>
                        <a href="<?php echo get_term_link($child, $taxonomy); ?>"><?php echo $child->name; ?>&nbsp; |</a>  
                        <?php endforeach; ?>
                        <a href="<?php echo get_term_link($category, $taxonomy); ?>" class="bold"> Xem hết&nbsp; </a> 
                    </div>
                    <div class="spLine"></div>
                    <div class="clearBoth"></div>
                    <!------------------------------------------------------------------------------------->
                    <div class="spFive">
                        <div id="category_<?php echo $category->term_id; ?>">
                            <?php
                                $loop = new WP_Query(array(
                                    'post_type' => 'product',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => $taxonomy,
                                            'field' => 'id',
                                            'terms' => $category->term_id,
                                        )
                                    ),
                                    'posts_per_page' => 15,
                                    'orderby' => 'meta_value_num',
                                    'order' => 'DESC',
                                    'meta_key' => 'sp_displayorder',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'not_in_home',
                                            'value' => '1',
                                            'compare' => '!='
                                        ),
                                    ),
                                ));
                                if($loop->post_count > 0):
                            ?>
                            <ul>
                                <?php while($loop->have_posts()) : $loop->the_post(); ?>
                                <li>
                                    <div class="spBg" onmouseout="UnTip()" onmouseover="showToolTip('<?php the_ID() ?>')">
                                        <div class="spImg">   
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
                                                <?php get_the_image( array( 'meta_key' => 'thumbnail', 'width' => 125, 'height' => 125 ) ); ?>
                                            </a>
                                        </div>
                                        <div style="position:absolute" class="tiff">
                                            <span class="gift_status"></span>
                                        </div>
                                        <div class="spText">
                                            <div class="spTextTitle">
                                                <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
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
                                            <!--<div class="star">
                                                <div style="width:62px" class="starlight"></div>
                                            </div>-->
                                        </div>
                                    </div>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="clearBoth"></div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
        
        <?php get_sidebar(); ?>
        <div class="clearBoth"></div>

        <!--<div class="block">
            <div id="featured-recently"><div class="spSawTitle"><a href="#">SẢN PHẨM VỪA XEM</a></div>
                <div style="text-align:center" class="spSawBg">
                    <ul style="width:1010px !important; padding-left:10px;">
                        <div class="spBg" onmouseout="UnTip()" onmouseover="showToolTip('3752')">
                            <div class="spImg">   
                                <a href="/samsung-galaxy-tab-3-101-p5200">
                                    <img alt="Hình ảnh của Samsung Galaxy Tab 3 10.1 P5200" src="http://fptshop.com.vn/content/images/thumbs/0019747_samsung-galaxy-tab-3-101-p5200_125.jpeg" class="noborder">

                                </a>
                            </div>
                            <div style="position:absolute" class="tiff">

                            </div>
                            <div class="spText">
                                <div class="spTextTitle">
                                    <a title="Hiển thị thông tin chi tiết cho Samsung Galaxy Tab 3 10.1 P5200" href="/samsung-galaxy-tab-3-101-p5200">Samsung Galaxy Tab 3 10.1 P520...</a></div>

                                <div class="spTextPrice">
                                    10.490.000 đ        </div>

                                <div class="star">
                                    <div style="width:57px" class="starlight">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>-->
        <!--/.block-->
        
    </div>
</div>


</div>

<?php get_footer(); ?>