<?php get_header(); ?>

<?php global $shortname; ?>

<?php include 'left_menu_product.php'; ?>

<?php while (have_posts()) : the_post(); ?>
<div class="center">

    <div class="ajax-loading-block-window" style="display: none" id="showloadingim">
        <div class="loading-image"></div>
    </div>

    <div class="breadDetail">
        <div class="breadcrums"><?php if (function_exists('bcn_display')) { bcn_display(); } ?></div>
    </div>
    
    <form action="" id="product-details-form" method="post">
        <?php $price = trim(get_post_meta(get_the_ID(), "gia_moi", true)); ?>
        <input type="hidden" name="action" value="addToCart" />
        <input type="hidden" name="id" value="<?php the_ID(); ?>" />
        <input type="hidden" name="thumb" value="<?php get_image_url(); ?>" />
        <input type="hidden" name="title" value="<?php the_title(); ?>" />
        <input type="hidden" name="price" value="<?php echo $price; ?>" />
        <input type="hidden" name="token" value="<?php echo random_string(); ?>" />
        
        <!-- Same Product -->
        <div class="sameSP">
            <div class="sameSPTitle">sản phẩm tương tự</div>
            <img src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=50&h=45" 
                 alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="samePic" style="opacity:1" />
            <?php
            $taxonomy = 'product_category';
            $excludeID = array();
            array_push($excludeID, get_the_ID()); 
            $terms = get_the_terms(get_the_ID(), $taxonomy);
            $terms_id = array();
            foreach ($terms as $term) {
                array_push($terms_id, $term->term_id);
            }
            $loop = new WP_Query(array(
                'post_type' => 'product',
                'posts_per_page' => 5,
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'id',
                        'terms' => $terms_id,
                    )
                ),
                'post__not_in' => $excludeID,
            ));
            while($loop->have_posts()) : $loop->the_post();
            ?>
            <div class="sameSpace"></div>
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <img src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=50&h=45" 
                     onmouseover="showToolTip('<?php the_ID() ?>')" onmouseout="UnTip()" class="samePic" />
            </a>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>

            <script type="text/javascript">

                $(function(){
                    $(".add-to-compare-list-button").click(function(e){
                        e.preventDefault();
                        var id=$(this).attr("id");

                        $.ajax({                    
                            cache:false,
                            type: "POST",
                            url: "/frtaddproducttocompareList",
                            data: { "productId": id },
                            success: function (data) {
                                if((data.pResult==1)||(data.pResult==2)){
						   
                                    $.ajax({                    
                                        cache:false,
                                        type: "POST",
                                        url: "/AddProductToCompareList",
                                        data: { "productId": id},
                                        beforeSend: function(){
                                            $("#showloadingim").show();
                                        },
                                        success: function (data) {
                                
                                            $("#showloadingim").hide();
                                            if(data=="valid")
                                            {
                                                $("#"+id).attr("checked", "checked");
                                                $("#"+id).attr("disabled", "disabled");								
                                                setLocation("/compareproducts");
                                            }
                                            else
                                                setLocation("/");
                              
                                        },
                                        error:function (xhr, ajaxOptions, thrownError){
                                            alert('Failed to retrieve states.');
                                        } 
              
                                    });
                                }else{
                                    if(data.pResult==3){
                                        var r=window.confirm("Đã có sản phẩm không cùng loại trong danh so sánh\n Bạn có muốn xóa và tiếp tục?");
                                        if (r==true){
                                            $.ajax({                    
                                                cache:false,
                                                type: "POST",
                                                url: "/clearcomparelist",
                                                data: {},
                                                beforeSend: function(){
                                                    $("#showloadingim").show();
                                                },
                                                success: function (data) {
                                                    $("#showloadingim").hide();
                                                },
                                                error:function (xhr, ajaxOptions, thrownError){
                                                    // alert('Failed to retrieve states.');
                                                } 
              
                                            });
                                            alert("Đã xóa");
                              
                                            $.ajax({                    
                                                cache:false,
                                                type: "POST",
                                                url: "/AddProductToCompareList",
                                                data: { "productId": id},
                                                beforeSend: function(){
                                                    $("#showloadingim").show();
                                                },
                                                success: function (data) {
                                                    if(data=="valid")
                                                    {
                                                        $("#"+id).attr("checked", "checked");
                                                        $("#"+id).attr("disabled", "disabled");								
                                                        setLocation("/compareproducts");
                                                    }
                                                    else
                                                        setLocation("/");                             
                                
                              
                                                },
                                                error:function (xhr, ajaxOptions, thrownError){
                                                    // alert('Bạn hãy thử lại.');
                                                } 
              
                                            });
                                        }
                                        else
                                        {
                                            // alert("Do not");
                                        } 
                                    }
                                }
                            },
                            error:function (xhr, ajaxOptions, thrownError){
                                alert('Bạn hãy thử lại.');
                            } 
              
                        });

                    });
                });
            </script>

            <!--<div id="greyBtn170"><input type="button" class="add-to-compare-list-button" id="3338" value="+ thêm vào so sánh"/></div>-->
        </div>
        <!-- End -->
        <div class="productView">
            <div class="productView1">
                <!------SHOW PICTURE--->
                <div style="width:300px;height:300px;text-align:center;display: table-cell;vertical-align: middle;"  class="productViewImg">
                    <a href="<?php get_image_url(); ?>" lightbox='lightbox' class="lightbox">
                        <img itemprop="image" content="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=300&h=300" 
                             style="max-width:300px;max-height:300px; border:0px; border-color:White;" 
                             id="imgView" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" 
                             src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=300&h=300"  />
                    </a>
                </div>
                <div class="thumbprev" style="display:none" ></div>
                <div class="listimg">
                    <ul id="thumb">
                        <li>
                            <a href="<?php get_image_url(); ?>" lightbox='lightbox' class="lightbox">
                                <img class="productViewImg" src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>" 
                                     data-thumb="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&w=300&h=300" 
                                     style="border: 1px solid #D3D1D1;" alt="<?php the_title(); ?>" />
                            </a>
                        </li> 
                        <?php
                        $args = array(
                            'orderby' => 'menu_order',
                            'post_type' => 'attachment',
                            'post_parent' => get_the_ID(),
                            'post_mime_type' => 'image',
                            'post_status' => null,
                            'posts_per_page' => -1,
                            'exclude' => get_post_thumbnail_id()
                        );
                        $attachments = get_posts($args);
                        foreach ($attachments as $attachment) :
                        ?>
                        <li>
                            <a href="<?php echo wp_get_attachment_url( $attachment->ID ); ?>" lightbox='lightbox' class="lightbox">
                                <img class="productViewImg" src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php echo wp_get_attachment_url( $attachment->ID ); ?>" 
                                     data-thumb="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php echo wp_get_attachment_url( $attachment->ID ); ?>&w=300&h=300" 
                                     style="border: 1px solid #D3D1D1;" alt="<?php the_title(); ?>" />
                            </a>
                        </li>           
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="thumbnext"></div>

                <div class="video2">
                    <div class="watchV"></div>
                    <div class="rotate1"></div>
                </div>
                <!---End Show picture--->
            </div>
            <!--productView1-->
            <div id="divDetail">
                <div class="detailBg">
                    <div class="detail1" style="margin-top: 15px;">
                        <div class="productViewTitle">
                            <h1><span itemprop="name" style="text-transform: uppercase;"><?php the_title(); ?></span></h1>
                        </div>
                        
                        <?php $loaihang = get_post_meta(get_the_ID(), "loai_hang", true); ?>
                        <?php if($loaihang != ""): ?>
                        <div class="spTextLoaiHang font13"><?php echo $loaihang; ?></div>
                        <?php endif; ?>

                        <h4 style="font-size:11px; color:#989898; margin: 5px 0 10px;">
                            Hãng: 
                            <?php 
                            $terms_factor = get_the_terms(get_the_ID(), 'product_factor');
                            $i = 0;
                            foreach ($terms_factor as $factor) {
                                if($i != count($terms_factor) - 1){
                                    echo "<span itemprop=\"brand\">{$factor->name}</span>, ";
                                }else{
                                    echo "<span itemprop=\"brand\">{$factor->name}</span>";
                                }
                                $i++;
                            }
                            ?>
                        </h4>  

                        <div class="rate" style="width: 400px;">
                            <div class="prices">
                                <span class="product-price">
                                    <strong>
                                        <label class="price_Onl" >Giá: 
                                            <span itemprop="price"> <?php echo number_format(floatval($price),0,',','.') . " đ"; ?></span>
                                            <span class="spTextoldprice ml15">
                                                <?php 
                                                    $price_old = trim(get_post_meta(get_the_ID(), "gia_cu", true));
                                                    if($price_old != ""){
                                                        echo number_format(floatval($price_old),0,',','.') . " đ"; 
                                                    }
                                                ?>
                                            </span>
                                        </label>
                                    </strong>
                                </span>
                            </div>
                            <div class="thongso">
                                <?php echo get_post_meta(get_the_ID(), "gioi_thieu", true); ?>
                            </div>
                            <div class="social_box mt10">
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
                            <div class="rating mt20"><?php if(function_exists('the_ratings')) { the_ratings(); } ?></div>
                        </div>
                        <?php 
                        $khuyen_mai = trim(get_post_meta(get_the_ID(), "khuyen_mai", true)); 
                        if($khuyen_mai != ""):
                        ?>
                        <div class="label-noitice">
                            <?php echo $khuyen_mai; ?>
                        </div>
                        <?php endif; ?>
                        <?php 
                        $khuyen_mai = trim(get_post_meta(get_the_ID(), "khuyen_mai", true)); 
                        if($khuyen_mai != ""):
                        ?>
                        <div class="label-noitice">
                            <h4 style="margin: 0 0 5px;">Khuyến mãi:</h4>
                            <?php echo $khuyen_mai; ?>
                        </div>
                        <?php endif; ?>
                        <?php 
                        $tang_kem = trim(get_post_meta(get_the_ID(), "tang_kem", true)); 
                        if($tang_kem != ""):
                        ?>
                        <div class="label-noitice">
                            <h4 style="margin: 0 0 5px;">Tặng kèm:</h4>
                            <?php echo $tang_kem; ?>
                        </div>
                        <?php endif; ?>

                        <!--<div class="detailLine3"></div>
                        <div class="giftSection">
                            <div class="giftText">
                                <p style="color: #f76211; font-weight: bold">
                                    Sản phẩm tặng kèm<span style="font-weight:bold;color:#d41212"> (Cho đến khi hết quà)</span></p>
                                <p class="giftline">
                                    <input type="radio" id = "PromotionGift_0" value = "2775"  name= "PromotionGift" checked ="checked" />
                                    <label>Pin v&#224; đế sạc Samsung Galaxy S4 trị gi&#225; 1.250.000đ</label>
                                </p>                                         
                            </div>
                        </div>-->
                        <!--Promotion Gift End-->
                        <!--Coupon-->
                        <!---end coupon-->

                    </div>                    
                    <div class="detail2x">
                        <!-- Mua ngay -->
                        <div class="detail2Frame">
                            <div class="detail2Quan">
                                <?php if(get_post_meta(get_the_ID(), "tinh_trang", true) == "Hết hàng"): ?>
                                <div style="font-weight: bold; font-size: 18px; color: #999999;">
                                    HẾT HÀNG
                                </div>
                                <?php else: ?>
                                <b>Số lượng &nbsp;&nbsp;&nbsp;&nbsp;</b>
                                <select class="textBoxSelect" data-val="true" name="quantity" style="width:50px;font-style: normal;">
                                    <?php 
                                    $maxQuantity = intval(get_option($shortname . '_maxQuantity'));
                                    for ($i = 1; $i <= $maxQuantity; $i++) {
                                        echo "<option value=\"{$i}\">{$i}</option>";
                                    }
                                    ?>
                                </select><br /><br />
                                <div class="themGio" style="margin-left: 26px">
                                    <a id="addCart" href="#">
                                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/themGiohover.png" style="opacity: 0" />
                                    </a>
                                </div>      
                                <?php endif; ?>
                                <!--<div style="margin-left: 85px; margin-bottom: 10px; float: left;" class="mt10 mb10">hoặc</div>
                                <input id="muangay" type="button" class="muaNgayNew ml27" />-->
                                <div class="icon2Small ml20 mt20"></div>
                                <div class="addText">
                                    <a class="button-2 add-to-wishlist-button" onclick="AjaxFavorite.addProduct(<?php the_ID(); ?>, $('select[name=quantity]').val(), '<?php echo random_string(); ?>'); return false;">Thêm vào yêu thích</a>
                                </div>
                            </div>
                        </div>

                        <div class="detail2Frame" style="text-align: center">
                            <b style="font-weight: bold; text-transform: uppercase">đặt hàng qua điện thoại</b><br />
                            <i style="float: left; margin: 0px 0 0 25px">(<?php echo get_option($shortname . '_timeWork'); ?>)</i>
                            <div class="phoneIcon1" style="margin-left: 30px; margin-top: 30px"></div>
                            <b style="font-weight: bold; text-transform: uppercase; color: #d41212; font-size: 20px; margin: -20px 0 0 60px; float: left">
                                <?php echo get_option($shortname . '_hotline'); ?>
                            </b>
                        </div>
                        <!-- End detail 2 -->
                    </div>
                    <div class="clearBoth"></div>
                </div>
            </div>
        </div>
    </form>
    
    <div class="tabCol" style="margin-left: 5px;width:auto">
        <div id="tabs" class="tabBg">
            <ul>
                <li><a href="#tab-1">Mô tả đầy đủ</a></li>
                <li><a href="#tab-2">Đặc tính sản phẩm</a></li>
            </ul>
            <div id="tab-1" class="article">
                <div class="noibat">
                    <div class="articleNote">
                        <span class="spantitle">Điểm nổi bật</span>
                        <p><?php echo get_post_meta(get_the_ID(), "gioi_thieu", true); ?></p>
                    </div>
                </div>
                <div><?php the_content(); ?></div>
            </div>
            <div id="tab-2" class="article">
                <?php echo get_post_meta(get_the_ID(), "dac_tinh", true); ?>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        $(function () {
            Lightbox.singleProduct();
            TSlider.productThumbnail();
            //$("a.youtube").YouTubePopup({ autoplay: 1 });
            
            AjaxCart.addToCart();
        });
    </script>
    
    <div class="comment_box">
        <h2 class="font16">Bình luận sản phẩm</h2>
        <div class="comment_box_ct">
            <div class="fb-comments" data-href="<?php echo getCurrentRquestUrl(); ?>" data-width="1020" data-num-posts="10"></div>
        </div>
    </div>
    <!--/.comment_box-->
</div>
<?php endwhile;?>

</div>
</div>

<?php get_footer(); ?>