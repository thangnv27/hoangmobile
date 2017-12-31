<?php
/*
  Template Name: Page Favorite List
 */
?>
<?php
if(!isset($_SESSION['current_user_login'])) {
    header("location: " . wp_login_url(getCurrentRquestUrl()));
}
?>
<?php get_header(); ?>

<?php include 'left_menu_product.php'; ?>

<div class="center">

    <div id="showloadingim" style="display: none" class="ajax-loading-block-window">
        <div class="loading-image"></div>
    </div>

    <div class="redTitle">Danh sách yêu thích</div>

    <div class="cartList">
        <form method="post" action="/danh-sach-yeu-thich" novalidate="novalidate">
            <table cellspacing="0" cellpadding="0" class="cartDetail">
                <thead>
                    <tr>
                        <th style="width:30px">Xóa</th>
                        <th style="width:90px">Thêm vào giỏ hàng</th>
                        <th style="width:400px">Sản phẩm</th>
                        <th style="width:200px">Giá</th>
                        <th style="width:70px">SL</th>
                        <th style="width:220px">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    global $shortname;
                    $maxQuantity = get_option($shortname . '_maxQuantity');
                    if($maxQuantity == "") $maxQuantity = 10;
                    $favorite_products = get_favorite_product();
                    foreach ($favorite_products as $favor) :
                        $title = get_the_title($favor->product_id);
                        $permalink = get_permalink($favor->product_id);
                        $thumbnail_id = get_post_meta($favor->product_id, "_thumbnail_id", true);
                        $image_url = wp_get_attachment_image_src($thumbnail_id, 'full');
                        if($image_url[0] == ""){
                            $image_url[0] = get_bloginfo( 'stylesheet_directory' ) . "/images/no_image_available.jpg";
                        }
                        $price = trim(get_post_meta($favor->product_id, "gia_moi", true)); 
                        $subtotal = floatval($price) * $favor->quantity;
                    ?>
                    <tr class="cart-item-row" id="product_item_<?php echo $favor->ID; ?>">
                        <td>
                            <input type="checkbox" value="<?php echo $favor->ID; ?>" name="removefromfavorite">
                        </td>
                        <td>
                            <input type="checkbox" value="<?php echo $favor->ID; ?>" name="addtocart">
                            <input type="hidden" id="product_id_<?php echo $favor->ID; ?>" value="<?php echo $favor->product_id; ?>" />
                            <input type="hidden" id="product_thumb_<?php echo $favor->ID; ?>" value="<?php echo $image_url[0]; ?>" />
                            <input type="hidden" id="product_title_<?php echo $favor->ID; ?>" value="<?php echo $title; ?>" />
                            <input type="hidden" id="product_price_<?php echo $favor->ID; ?>" value="<?php echo $price; ?>" />
                        </td>
                        <td>
                            <div class="cartSp">
                                <div class="cartPic">
                                    <img title="Hiển thị thông tin chi tiết cho <?php echo $title; ?>" 
                                         src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php echo $image_url[0]; ?>&w=49&h=49" alt="Hình ảnh của <?php echo $title; ?>" />
                                </div>
                                <div class="cartName">
                                    <a title="Xem chi tiết" href="<?php echo $permalink; ?>" target="_blank"><?php echo $title; ?></a>
                                </div>
                            </div>                                   
                        </td>
                        <td><?php echo number_format(floatval($price),0,',','.'); ?> đ</td>
                        <td>
                            <select id="quantity_<?php echo $favor->ID; ?>" onchange="AjaxFavorite.updateProduct(<?php echo $favor->ID; ?>, <?php echo $favor->product_id; ?>, this.value);">
                                <?php
                                for ($i = 1; $i <= $maxQuantity; $i++){
                                    if($i == $favor->quantity)
                                        echo '<option selected="selected">' . $i . '</option>';
                                    else 
                                        echo '<option>' . $i . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <span class="product-subtotal"><?php echo number_format($subtotal,0,',','.'); ?></span> đ
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('input[name="removefromfavorite"]').each(function(){
            $(this).change(function(){
                if($(this).is(':checked')){
                    if (confirm('Xóa sản phẩm này khỏi danh sách yêu thích phải không?')){
                        AjaxFavorite.deleteProduct($(this).val());
                    }else{
                        $(this).attr('checked', false);
                    }
                }
            });
        });
        $('input[name="addtocart"]').each(function(){
            $(this).change(function(){
                if($(this).is(':checked')){
                    if (confirm('Thêm sản phẩm này vào giỏ hàng của bạn phải không?')){
                        var id = $(this).val();
                        var product_id = $("#product_id_" + id).val();
                        var thumb = $("#product_thumb_" + id).val();
                        var title = $("#product_title_" + id).val();
                        var price = $("#product_price_" + id).val();
                        var quantity = $("#quantity_" + id).val();
                        var token = "<?php echo random_string(); ?>";
                        
                        AjaxFavorite.addToCart(product_id, thumb, title, price, quantity, token);
                    }
                    $(this).attr('checked', false);
                }
            });
        });
    });
</script>

</div>

<?php get_footer(); ?>