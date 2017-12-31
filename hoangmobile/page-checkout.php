<?php
/*
  Template Name: Page Checkout
 */
?>
<?php
if(!isset($_SESSION['current_user_login'])) {
    global $shortname;
    header("location: " . wp_login_url(getCurrentRquestUrl()));
}
global $current_user, $shortname;;
get_currentuserinfo();
?>
<?php get_header(); ?>

<?php include 'left_menu_product.php'; ?>

<style type="text/css">
    #BillAddress1 {
        display: none;
    }
    #BillAddress1.active {
        display: block;
    }
        
    #BillAddress2 {
        display: none;
    }
    #BillAddress2.active {
        display: block;
    }
        
    #PaymentMethodDiv_0 {
        display: none;
    }
    #PaymentMethodDiv_0.active {
        display: block;
    }
</style>
    
<div class="center">
    <div class="ajax-loading-block-window" style="display: none" id="showloadingim">
        <div class="loading-image"></div>
    </div>
        
    <form action="" id="frmOrderCheckOut" method="post">
        <input type="hidden" name="action" value="orderComplete" />
        <input type="hidden" name="token" value="<?php echo random_string(); ?>" />
               
        <div class="addrWhole">
            <div class="confirmCol" style="width:295px">
                <div class="redTitle">Thông tin khách hàng</div>
                <div class="typeInfo mb10" id="billing">
                    <input data-val="true" data-val-email="Sai email" 
                           data-val-required="Vui lòng nhập email" 
                           id="email" name="email" type="hidden" 
                           value="<?php echo $current_user->user_email; ?>" readonly="readonly" />
                    
                    <input class="textBox textbox247" data-val="true" 
                           data-val-required="Vui lòng nhập họ" 
                           id="lastname"name="lastname" placeholder="Họ" type="text" 
                           value="<?php echo $current_user->user_lastname; ?>" />
                    <span class="required">*</span>
                    <span class="field-validation-valid required" 
                          data-valmsg-for="lastname" data-valmsg-replace="true"></span>
                    
                    <input class="textBox textbox247" data-val="true" 
                           data-val-required="Vui lòng nhập tên" 
                           id="firstname" name="firstname" placeholder="Tên" type="text" 
                           value="<?php echo $current_user->user_firstname; ?>" />
                    <span class="required">*</span>
                    <span class="field-validation-valid required" 
                          data-valmsg-for="firstname" data-valmsg-replace="true"></span>
                        
                    <input class="textBox textbox247" data-val="true" 
                           data-val-regex="Vui lòng nhập kiểu số" data-val-regex-pattern="^\d+$" 
                           data-val-required="Vui lòng nhập số điện thoại" 
                           id="phone" name="phone" placeholder="Điện thoại" type="text" 
                           value="<?php echo $current_user->user_phone; ?>" />
                    <span class="required">*</span>
                    <span class="field-validation-valid required" 
                          data-valmsg-for="phone" data-valmsg-replace="true"></span>
                                                
                    <input class="textBox textbox247" data-val="true" 
                           data-val-required="Vui lòng nhập địa chỉ" 
                           id="address" name="address" placeholder="Số nhà, đường…" type="text" 
                           value="<?php echo $current_user->user_address; ?>" />
                    <span class="required">*</span>
                    <span class="field-validation-valid required" 
                          data-valmsg-for="address" data-valmsg-replace="true"></span>
                    
                    <select class="textBoxSelect" data-val="true" 
                          data-val-required="Cần phải chọn tỉnh/thành" 
                          id="city" name="city" style="width: 267px;">
                        <option value="">- Chọn Tỉnh/Thành</option>
                        <?php
                        $cities = get_city_list();
                        foreach ($cities as $city) {
                            echo '<option value='.$city.'>'.$city.'</option>';
                        }
                        ?>
                    </select>
                    <span class="required">*</span>
                    <span class="field-validation-valid required" 
                          data-valmsg-for="city" data-valmsg-replace="true"></span>
                    
                    <input class="textBox textbox247" data-val="true" 
                           data-val-required="Vui lòng nhập quận/huyện"
                           id="district" name="district" 
                           placeholder="Quận/Huyện" type="text" value="" />
                    <span class="required">*</span>
                    <span class="field-validation-valid required" 
                          data-valmsg-for="district" data-valmsg-replace="true"></span>
                    
                    <input class="textBox textbox247" data-val="true" 
                           data-val-required="Vui lòng nhập phường/xã" 
                           id="ward" name="ward" 
                           placeholder="Phường/Xã" type="text" value="" />
                    <span class="required">*</span>
                    <span class="field-validation-valid required" 
                          data-valmsg-for="ward" data-valmsg-replace="true"></span>
                </div>
                <div class="clearBoth"></div>
            </div>
            
            <div class="confirmCol" style="width: 260px">
                <div class="redTitle">Hình thức thanh toán</div>
                <div class="payWay">
                    <div id="CheckoutPaymentMethod_0">
                        <div class="picPayWay">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/pay1.png" />
                        </div>
                        <div class="textPayWay">
                            <input Checked="checked" id="PaymentMethod_0" name="payment_method" type="radio" value="Thanh toán khi nhận hàng (COD)" data-val="Payments.CashOnDelivery" />                        
                            <b>
                                <label for="PaymentMethod_0">Thanh toán khi nhận hàng (COD)</label>
                            </b>
                        </div>
                        <div class="clearBoth">
                        </div>      
                        <div class="ck" style="display:block; text-align: left;" id="Payments.CashOnDelivery">
                            <?php echo stripslashes(get_option('payment_cashOnDelivery')); ?>
                        </div>
                        <div class="clearBoth">
                        </div>
                    </div>
                    <div id="CheckoutPaymentMethod_1">
                        <div class="picPayWay">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/pay3.png" />
                        </div>
                        <div class="textPayWay">
                            <input id="PaymentMethod_1" name="payment_method" type="radio" value="Chuyển khoản qua tài khoản ATM" data-val="Payments.ATM" />                        
                            <b>
                                <label for="PaymentMethod_1">Chuyển khoản qua tài khoản ATM</label>
                            </b>
                        </div>
                        <div class="clearBoth">
                        </div>      
                        <div class="ck" id="Payments.ATM" style="text-align:left">
                            <?php echo stripslashes(get_option('payment_atm')); ?>
                        </div>
                        <div class="clearBoth">
                        </div>
                    </div>
                    <div id="CheckoutPaymentMethod_2">
                        <div class="picPayWay">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/pay4.png" />
                        </div>
                        <div class="textPayWay">
                            <input id="PaymentMethod_2" name="payment_method" type="radio" value="Thanh toán qua đường Bưu điện" data-val="Payments.PostOffice" />                        
                            <b>
                                <label for="PaymentMethod_2">Thanh toán qua đường Bưu điện</label>
                            </b>
                        </div>
                        <div class="clearBoth">
                        </div>      
                        <div class="ck t_left" id="Payments.PostOffice">
                            <?php echo stripslashes(get_option('payment_postOffice')); ?>
                        </div>
                        <div class="clearBoth">
                        </div>
                    </div>
                    <div id="CheckoutPaymentMethod_3">
                        <div class="picPayWay">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/pay2.png" />
                        </div>
                        <div class="textPayWay">
                            <input id="PaymentMethod_3" name="payment_method" type="radio" value="Trực tiếp qua ATM nội địa (OnePAY)" data-val="Payments.OnePAYStandard" />                        
                            <b>
                                <label for="PaymentMethod_3">Trực tiếp qua ATM nội địa (OnePAY)</label>
                            </b>
                        </div>
                        <div class="clearBoth">
                        </div>      
                        <div class="ck" id="Payments.OnePAYStandard">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/bankList.png"/><br>
                            <i>* Thẻ của Quý khách phải được đăng ký và kích hoạt chức năng thanh toán trực tuyến
                            </i>
                        </div>
                        <div class="clearBoth">
                        </div>
                    </div>
                    <div id="CheckoutPaymentMethod_4">
                        <div class="picPayWay">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/pay5.png" />
                        </div>
                        <div class="textPayWay">
                            <input id="PaymentMethod_4" name="payment_method" type="radio" value="Trực tiếp qua thẻ Visa, Master Card (OnePAY)" data-val="Payments.OnePAY" />                        
                            <b>
                                <label for="PaymentMethod_4">Trực tiếp qua thẻ Visa, Master Card (OnePAY)</label>
                            </b>
                        </div>
                        <div class="clearBoth">
                        </div>      
                        <div class="ck" id="Payments.OnePAY">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/visa.png">
                        </div>
                        <div class="clearBoth">
                        </div>
                    </div>
                </div>
                <!--payWay-->
            </div>
            <div id="cartInfo">    
                <div class="confirmCol" style="width: 430px; float:right;">
                    <div class="redTitle">Xác nhận đơn hàng</div>
                        
                    <div class="editOrder">      
                        <a onclick="ShowPoupEditOrder()" style="cursor:pointer">Sửa đơn hàng</a>
                    </div>
                    <div class="payTable">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <th style="width:260px">Sản phẩm</th>
                                <th style="width:30px">SL </th>
                                <th style="width:120px">Giá tiền</th>
                            </tr>
                            <?php 
                            if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])): 
                            $cart = $_SESSION['cart'];
                            $maxQuantity = get_option($shortname . '_maxQuantity');
                            if($maxQuantity == "") $maxQuantity = 10;
                            $totalAmount = 0;
                            foreach ($cart as $product) : 
                                $totalAmount += $product['amount'];
                                $permalink = get_permalink($product['id']);
                            ?>
                            <tr>
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
                                <td><span><?php echo $product['quantity']; ?></span></td>
                                <td><?php echo number_format($product['price'],0,',','.'); ?> đ</td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </table>                   
                            
                        <div class="total-info">
                            <table class="cart-total" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td class="sumPay"><span class="nobr">Tổng tiền:</span></td>
                                        <td class="sumPay"><?php echo number_format($totalAmount,0,',','.'); ?> đ</td>
                                    </tr>
                                    <!--<tr style="background:#F5F5F5">                
                                        <td  style="width:176px">
                                            <div id="DiscountBox" class="code">
                                                <div class="coupon-code">
                                                    <input id="discountCode" name="discountcouponcode" type="text" class="textBox" style="width:77px" placeholder="Mã giảm giá" />&nbsp;
                                                    <input type="button" onclick="ApplyDiscountCode($('#discountCode').val());" name="applydiscountcouponcode" class="btnSudung" value = " " />
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>-->
                                    <tr>
                                        <td colspan="2" class="cart-total-left">
                                            <span class="nobr">Phí vận chuyển tạm tính:</span>
                                        </td>
                                        <td class="cart-total-right">
                                            <span class="nobr" style="font-weight:bold;">
                                                <span style="font-weight:bold">Miễn phí</span>
                                            </span>
                                        </td>
                                    </tr>
                                        
                                    <tr style="font-size:16px;color:#d41212;font-weight:bold">
                                        <td class="cart-total-left" colspan="2">
                                            <span class="nobr">Tổng cộng:</span>
                                        </td>
                                        <td class="cart-total-right">
                                            <span class="nobr">
                                                <span class="product-price order-total">
                                                    <strong><?php echo number_format($totalAmount,0,',','.'); ?> đ</strong>
                                                </span>
                                            </span>
                                            <input type="hidden" name="total_amount" value="<?php echo number_format($totalAmount,0,',','.'); ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>  
                    </div> <!--payTable-->
                        
                </div><!--confirmCol-->
            </div> 
            <div class="xnBtn">
                <input type="button" class="btnMuaHang" value=" " />
            </div>
        </div>
        <!--addrWhole-->
        <div class="clearBoth"></div>
    </form>
    <script type="text/javascript">
        $(function() {
            AjaxCart.orderComplete();
            
            $('input[id^=PaymentMethod]').change(function() {
                $('.ck').hide();
                var el = document.getElementById($(this).attr('data-val'));
                $(el).slideDown();
            });
        })
        
        if ($("#PaymentMethod_0").is(":checked") == true) {
            $('#PaymentMethodDiv_0').addClass('active');
        }
        
        $("#PaymentMethod_0").click(function() {
            $('#PaymentMethodDiv_0').addClass('active');
        });
        
        $("#PaymentMethod_1").click(function() {
            $('#PaymentMethodDiv_0').removeClass('active');
        });
        
        $("#PaymentMethod_2").click(function() {
            $('#PaymentMethodDiv_0').removeClass('active');
        });
        
        $("#PaymentMethod_3").click(function() {
            $('#PaymentMethodDiv_0').removeClass('active');
        });
        
        $("#PaymentMethod_4").click(function() {
            $('#PaymentMethodDiv_0').removeClass('active');
        });
    </script>
</div>
    
</div>
</div>

<?php get_footer(); ?>