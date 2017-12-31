<?php
/*
  Template Name: Page Cart
 */
?>

<?php get_header(); ?>

<?php include 'left_menu_product.php'; ?>

<div class="center">
    <div class="ajax-loading-block-window" style="display: none" id="showloadingim">
        <div class="loading-image"></div>
    </div>
    <div class="page-body">
        <div class="cartList">
            <form action="" id="frmCart" method="post">
                <table class="cartDetail" style="width:100%" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <th style="width:402px">Sản phẩm</th>
                            <th style="width:155px">Giá</th>
                            <th style="width:70px">Số lượng</th>
                            <th style="width:159px">Thành tiền</th>
                            <th style="width:50px">Xóa</th>
                        </tr>
                        <?php 
                        global $shortname;
                        if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])): 
                        $cart = $_SESSION['cart'];
                        $maxQuantity = get_option($shortname . '_maxQuantity');
                        if($maxQuantity == "") $maxQuantity = 10;
                        $totalAmount = 0;
                        foreach ($cart as $product) : 
                            $totalAmount += $product['amount'];
                            $permalink = get_permalink($product['id']);
                        ?>
                        <tr id="product_item_<?php echo $product['id']; ?>">
                            <td>
                                <div class="cartSp">
                                    <div class="cartPic">
                                        <img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php echo $product['thumb']; ?>&w=80&h=80" />
                                    </div>
                                    <div class="cartName">
                                        <a href="<?php echo $permalink; ?>" title="View details"><?php echo $product['title']; ?></a>
                                    </div>                                                            
                                </div>
                            </td>
                            <td><?php echo number_format($product['price'],0,',','.'); ?> đ</td>
                            <td>
                                <select name="quantity[<?php echo $product['id']; ?>]" onchange="AjaxCart.updateItem(<?php echo $product['id']; ?>, this.value)">
                                    <?php
                                    for ($i = 1; $i <= $maxQuantity; $i++){
                                        if($i == $product['quantity'])
                                            echo '<option selected="selected">' . $i . '</option>';
                                        else 
                                            echo '<option>' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><span class="product-subtotal"><?php echo number_format($product['amount'],0,',','.'); ?> đ</span></td>
                            <td>
                                <div class="trash">
                                   <a href="#" onclick="if (confirm('Xóa sản phẩm này khỏi giỏ hàng')) {
                                       AjaxCart.deleteItem(<?php echo $product['id']; ?>);
                                    } return false;" id="trash"></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                        <tr>
                            <td style="border-bottom:none"></td>
                            <td style="border-bottom:none"></td>
                            <td colspan="3" style="border-bottom:none; text-align: left; padding-left:26px; font-weight: bold; font-size: 14px;">
                                <span> Tổng tiền:<br /><small>(Đã bao gồm VAT)</small></span> &nbsp;&nbsp;
                                <span class="sum"><?php echo number_format($totalAmount,0,',','.'); ?> đ</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="clear"></div>
                <div></div>
                <div class="clear"></div>
            </form>
            <div class="cart-footer">
                <div class="clear"></div>
                <div class="cart-collaterals"></div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="deals"></div>
        <div class="clearBoth"></div>
        
        <div class="mainBtn">        
            <input type="submit" name="conshopping" value="" class="contBtn" onclick="window.location='<?php bloginfo( 'siteurl' ); ?>'" />            
            <input type="submit" name="checkout" class="payBtn" value="" onclick="window.location=checkoutUrl" />
            <div class="clear"></div>
        </div> 
        <div class="clearBoth"></div>
        
        <!----RECENTLY PRODUCT-->
        <!--<div class="block">
            <div id="featured-recently"></div>
        </div>-->
    </div>
</div>

</div>
</div>

<?php get_footer(); ?>