<?php
add_action('init', 'add_custom_js');
add_action( 'wp_ajax_nopriv_' . getRequest('action'), getRequest('action') );  
add_action( 'wp_ajax_' . getRequest('action'), getRequest('action') ); 

function add_custom_js() {
    // code to embed th  java script file that makes the Ajax request  
    //wp_enqueue_script('ajax.js', get_bloginfo('template_directory') . "/js/ajax.js", array('jquery'), false, true);
    wp_enqueue_script('ajax.js', get_bloginfo('template_directory') . "/js/ajax.js");
    wp_enqueue_script('jquery.tooltip.js', get_bloginfo('template_directory') . "/js/jquery.tooltip.js");
    // code to declare the URL to the file handling the AJAX request 
    //wp_localize_script( 'ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); 
}

/* ----------------------------------------------------------------------------------- */
# Cart
/* ----------------------------------------------------------------------------------- */

function addToCart(){
    if(strlen(getRequest('token')) != 32){
        exit();
    }
    
    $price = getRequest('price');
    $quantity = getRequest('quantity');
    $amount = $price * $quantity;
    $product = array(
        'id' => getRequest('id'),
        'thumb' => getRequest('thumb'),
        'title' => getRequest('title'),
        'price' => $price,
        'quantity' => $quantity,
        'amount' => $amount,
    );
    
    if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])){
        $addToCart = TRUE;
        $cart = $_SESSION['cart'];
        foreach ($cart as $k => $v) {
            if(getRequest('id') == $v['id']){
                if($v['quantity'] == $quantity){
                    $addToCart = FALSE;
                }else{
                    unset($cart[$k]);
                }
                break;
            }
        }
        if($addToCart == TRUE){
            array_push($cart, $product);
            $_SESSION['cart'] = $cart;
        }
    }else{
        $cart = array();
        array_push($cart, $product);
        $_SESSION['cart'] = $cart;
    }

    $cart = $_SESSION['cart'];

    // Response message
    Response(json_encode(array(
        'status' => 'success',
        'countcart' => count($cart),
        'message' => "Đã thêm vào giỏ hàng",
    )));
    exit();
}

function loadCartAjax(){
    $message = "";
    
    if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        global $shortname;
        
        $cart = $_SESSION['cart'];
        $pageCart = get_page_link(get_option($shortname . '_pageCartID'));
        $pageCheckout = get_page_link(get_option($shortname . '_pageCheckoutID'));;
        
        $message .= <<<HTML
        <div class="mini-shopping-cart">
            <div class="items">
                <table>
HTML;
        $totalAmount = 0;
        $counter = 1;
        foreach ($cart as $product) :
            $themeurl = get_bloginfo('stylesheet_directory');
            $trfirst = ($counter == 1) ? "trfirst" : "";
            $title = $product['title'];
            $permalink = get_permalink($product['id']);
            $price = number_format($product['price'],0,',','.');
            $totalAmount += $product['amount'];
            $message .= <<<HTML
            <tr class="item {$trfirst}">
                <td class="picture">
                    <a title="Hiển thị thông tin chi tiết cho {$title}" href="{$permalink}">
                        <img title="Hiển thị thông tin chi tiết cho {$title}" src="{$themeurl}/timthumb.php?src={$product['thumb']}&w=47&h=47" alt="Hình ảnh của {$title}">
                    </a>
                </td>
                <td class="product">
                    <div class="name"><a href="{$permalink}">{$title}</a></div>
                    <div class="clearBoth"></div>
                    <div class="price">Đơn giá: {$price} đ</div>
                    <div class="clearBoth"></div>
                    <div class="quantity">Số lượng: {$product['quantity']}</div>
                </td>
            </tr>
HTML;
            $counter ++;
        endforeach;
        
        $totalAmount = number_format($totalAmount,0,',','.');
        $message .= <<<HTML
                </table>
            </div>
            <div class="clearBoth"></div>
            <div class="totals">Tổng tiền: {$totalAmount} đ</div>
            <div class="clearBoth"></div>
            <div class="buttons">
                <input type="button" onclick="window.location = '$pageCart'" class="button-1 cart-button btnViewCart" value=" " />
                <input type="button" onclick="window.location = '$pageCheckout'" class="button-1 checkout-button btnThanhToan" value=" " />
                <div class="clearBoth"></div>
            </div>
        </div>
HTML;
    }else{
        $message .= <<<HTML
        <div class="mini-shopping-cart">Bạn không có sản phẩm nào trong giỏ hàng</div>
HTML;
    }
    
    // Response message
    Response(json_encode(array(
        'status' => 'success',
        'message' => $message,
    )));
    exit();
}

function deleteCartItem(){
    if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $product_id = intval(getRequest('id'));
        if($product_id > 0){
            $cart = $_SESSION['cart'];
            $totalAmount = 0;
            foreach ($cart as $key => $product) {
                if($product['id'] == $product_id){
                    unset($cart[$key]);
                }else{
                    $totalAmount += $product['amount'];
                }
            }
            array_values($cart);
            $_SESSION['cart'] = $cart;

            Response(json_encode(array(
                'status' => 'success',
                'countcart' => count($cart),
                'total_amount' => number_format($totalAmount,0,',','.') . " đ",
                'message' => "Đã xóa sản phẩm {id:$product_id}",
            )));
        }
    }else{
        Response(json_encode(array(
            'status' => 'error',
            'message' => 'Bạn không có sản phẩm nào trong giỏ hàng',
        )));
    }
    exit();
}

function updateCartItem(){
    if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $product_id = intval(getRequest('id'));
        $quantity = intval(getRequest('quantity'));
        if($product_id > 0 and $quantity > 0){
            $cart = $_SESSION['cart'];
            $totalAmount = 0;
            $item_amount = 0;
            foreach ($cart as $key => $product) {
                if($product['id'] == $product_id){
                    $amount = $product['price'] * $quantity;
                    $new_product = $product;
                    $new_product['quantity'] = $quantity;
                    $new_product['amount'] = $amount;
                    unset($cart[$key]);
                    array_push($cart, $new_product);
                    $item_amount = $amount;
                    $totalAmount += $amount;
                }else{
                    $totalAmount += $product['amount'];
                }
            }
            array_values($cart);
            $_SESSION['cart'] = $cart;

            Response(json_encode(array(
                'status' => 'success',
                'item_amount' => number_format($item_amount,0,',','.') . " đ",
                'total_amount' => number_format($totalAmount,0,',','.') . " đ",
                'message' => "Đã cập nhật sản phẩm {id:$product_id}",
            )));
        }
    }else{
        Response(json_encode(array(
            'status' => 'error',
            'message' => 'Bạn không có sản phẩm nào trong giỏ hàng',
        )));
    }
    exit();
}

function loadCartEditOrder(){
    global $shortname;
    
    $siteurl = get_bloginfo( 'siteurl' );
    $themeurl = get_bloginfo('stylesheet_directory');
    $cart = $_SESSION['cart'];
    $maxQuantity = get_option($shortname . '_maxQuantity');
    
    $html = <<<HTML
    <div id="popup" class="simplemodal-data">
        <div class="fix cartList">
            <table>
                <tbody>
                    <tr style="background:#f7f7f7">
                        <td style="text-align:left;border:none;font-weight:bold;" colspan="4">
                            CẬP NHẬT ĐƠN HÀNG
                        </td>
                        <td style="border:none"></td>
                    </tr>
                    <tr>
                        <th style="width:400px">Sản phẩm</th>
                        <th style="width:180px">Đơn giá</th>
                        <th>Số lượng</th>
                        <th style="width:200px">Thành tiền</th>
                        <th style="width:50px">Xóa</th>
                    </tr>
HTML;
    $totalAmount = 0;
    foreach ($cart as $product) :
        $title = $product['title'];
        $price = number_format($product['price'],0,',','.');
        $amount = number_format($product['amount'],0,',','.');
        $totalAmount += $product['amount'];
        $quantity = "" ;
        for ($i = 1; $i <= $maxQuantity; $i++){
            if($i == $product['quantity'])
                $quantity .= '<option selected="selected">' . $i . '</option>';
            else 
                $quantity .= '<option>' . $i . '</option>';
        }
        $html .= <<<HTML
                    <tr valign="top" id="product_item_{$product['id']}">
                        <td>
                            <div class="cartPic1">
                                <img title="Hiển thị thông tin chi tiết cho {$title}" src="{$themeurl}/timthumb.php?src={$product['thumb']}&w=80&h=80" alt="Hình ảnh của {$title}">
                            </div>
                            <div class="cartName2">{$title}</div>
                        </td>
                        <td>{$price} đ</td>
                        <td>
                            <select name="quantity[{$product['id']}]" onchange="AjaxCart.updateItem({$product['id']}, this.value); ">
                                {$quantity}
                            </select>
                        </td>
                        <td style="font-weight:bold"><span class="product-subtotal">{$amount} đ</span></td>
                        <td>
                            <div class="trash">
                                <a href="#" onclick="if (confirm('Xóa sản phẩm này khỏi giỏ hàng')) {
                                    AjaxCart.deleteItem({$product['id']}); 
                                 } return false;" id="trash"></a>
                             </div>
                        </td>
                    </tr>
HTML;
    endforeach;
    
    $totalAmount = number_format($totalAmount,0,',','.');
    
    $html .= <<<HTML
                    <tr style="border:none">
                        <td style="border:none" rowspan="2" colspan="3">
                            <input type="button" onclick="window.location.href = '$siteurl';" value="&gt;&gt; chọn thêm sản phẩm" class="greyBtn170">
                        </td>
                        <td style="text-align:right;border:none" colspan="2">
                            <b style="color:#d41212;font-size:16px">
                                <span> Tổng tiền:<br />
                                <span class="sum">{$totalAmount} đ</span><br />
                                <small>(Đã bao gồm VAT)</small></span>
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none" colspan="2"><b><i>Chỉ 3 bước đơn giản</i></b>
                            <input type="button" style="float:right;margin-top:-5px" value="Thanh toán" onclick="$.colorbox.close();" id="btnThanhtoan" class="redBtn">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
HTML;
    
    Response($html);
    exit();
}

function orderComplete() {
    if(strlen(getRequest('token')) != 32){
        exit();
    }
    
    global $wpdb;

    if (isset($_SESSION['current_user_login'])) {
        if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];

            if (is_array($cart) and !empty($cart)) {
                foreach ($cart as $k => $v) {
                    unset($v['thumb']);
                    $product = $v;
                    $product['price'] = number_format($v['price'], 0, ',', '.');
                    $product['amount'] = number_format($v['amount'], 0, ',', '.');
                    unset($cart[$k]);
                    array_push($cart, $product);
                }

                $current_user = $_SESSION['current_user_login'];
                $current_user = TConvertObjectToArray($current_user);
                $customer_id = $current_user['ID'];
                $customer_info = json_encode(array(
                    'firstname' => getRequest('firstname'),
                    'lastname' => getRequest('lastname'),
                    'phone' => getRequest('phone'),
                    'address' => getRequest('address'),
                    'city' => getRequest('city'),
                    'district' => getRequest('district'),
                    'ward' => getRequest('ward'),
                        ));
                $payment_method = getRequest('payment_method');
                $total_amount = getRequest('total_amount');
                $products = json_encode($cart);
                
                $result = $wpdb->query($wpdb->prepare("INSERT INTO wp_orders SET customer_id = %d, customer_info = '%s', 
                    payment_method = '%s', total_amount = '%s', products = '%s'
                    ", $customer_id, $customer_info, $payment_method, $total_amount, $products));
                
                if($result){
                    unset($_SESSION['cart']);
                
                    Response(json_encode(array(
                            'status' => 'success',
                            'message' => "Đặt hàng thành công! Chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất!",
                        )));
                }
            } else {
                Response(json_encode(array(
                            'status' => 'error',
                            'message' => "Đơn hàng không có sản phẩm, vui lòng chọn sản phẩm trước.",
                        )));
            }
        } else {
            Response(json_encode(array(
                        'status' => 'error',
                        'message' => "Đơn hàng không có sản phẩm, vui lòng chọn sản phẩm trước.",
                    )));
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => "Vui lòng đăng nhập!",
                )));
    }
    
    exit();
}

/* ----------------------------------------------------------------------------------- */
# Favorite Products
/* ----------------------------------------------------------------------------------- */

function addProductToFavorite() {
    if(strlen(getRequest('token')) != 32){
        exit();
    }
    
    global $wpdb, $current_user;
    get_currentuserinfo();

    if (is_user_logged_in()) {
        $product_id = getRequest('product_id');
        $quantity = getRequest('quantity');
        $tblFavor = $wpdb->prefix . 'favorite_products';
        
        $favor_count = $wpdb->get_var( "SELECT COUNT(*) FROM $tblFavor WHERE $tblFavor.user_id = $current_user->ID" );
        if($favor_count > 0){
            Response(json_encode(array(
                    'status' => 'error',
                    'message' => "Sản phẩm này đã tồn tại trong danh sách yêu thích của bạn rồi!",
                )));
            exit();
        }
        
        $result = $wpdb->query($wpdb->prepare("INSERT INTO $tblFavor SET user_id = %d, product_id = %d, quantity = %d", 
                $current_user->ID, $product_id, $quantity));
        if($result){
            $favor_count = $wpdb->get_var( "SELECT COUNT(*) FROM $tblFavor WHERE $tblFavor.user_id = $current_user->ID" );
            
            Response(json_encode(array(
                    'status' => 'success',
                    'favor_count' => $favor_count,
                    'message' => "Sản phẩm này đã được thêm vào danh sách yêu thích của bạn!",
                )));
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => "Vui lòng đăng nhập trước!",
                )));
    }
    
    exit();
}

function deleteFavoriteProduct(){
    global $wpdb, $current_user;
    get_currentuserinfo();
    
    if (is_user_logged_in()) {
        $favorite_id = getRequest('favorite_id');
        $tblFavor = $wpdb->prefix . 'favorite_products';
        
        $where = array(
            'user_id' => $current_user->ID,
            'ID' => $favorite_id,
        );
        
        $result = $wpdb->delete($tblFavor, $where);

        if ($result > 0) {
            $favor_count = $wpdb->get_var( "SELECT COUNT(*) FROM $tblFavor WHERE $tblFavor.user_id = $current_user->ID" );
            
            Response(json_encode(array(
                        'status' => 'success',
                        'favor_count' => $favor_count,
                        'message' => "",
                    )));
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => "Vui lòng đăng nhập trước!",
                )));
    }
    
    exit();
}

function updateFavoriteProduct(){
    global $wpdb, $current_user;
    get_currentuserinfo();
    
    if (is_user_logged_in()) {
        $favorite_id = getRequest('favorite_id');
        $product_id = getRequest('product_id');
        $quantity = intval(getRequest('quantity'));
        $tblFavor = $wpdb->prefix . 'favorite_products';
        
        $favor_count = $wpdb->get_var( "SELECT COUNT(*) FROM $tblFavor 
            WHERE user_id = $current_user->ID AND ID = $favorite_id" );
        if($favor_count == 1){
            $data = array(
                'quantity' => $quantity
            );
            $where = array(
                'product_id' => $product_id,
                'user_id' => $current_user->ID,
                'ID' => $favorite_id,
            );
            $result = $wpdb->update($tblFavor, $data, $where);
            if($result > 0){
                $price = trim(get_post_meta($product_id, "gia_moi", true)); 
                $subtotal = floatval($price) * $quantity;

                Response(json_encode(array(
                        'status' => 'success',
                        'subtotal' => number_format($subtotal,0,',','.'),
                        'message' => "",
                    )));
            }
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => "Vui lòng đăng nhập trước!",
                )));
    }
    
    exit();
}

/* ----------------------------------------------------------------------------------- */
# Tooltip
/* ----------------------------------------------------------------------------------- */

function product_tooltip(){
    $product_id = getRequest('product_id');
    $price = trim(get_post_meta($product_id, "gia_moi", true)); 
    $status = get_post_meta($product_id, "tinh_trang", true);
    $dis = get_post_meta($product_id, "khuyen_mai", true);
    $tang_kem = get_post_meta($product_id, "tang_kem", true);
    $sdes = get_post_meta($product_id, "gioi_thieu", true);
    
    $response = array(
        'id' => $product_id,
        'n' => get_the_title($product_id),
        'p' => number_format(floatval($price),0,',','.') . " đ",
        'sv' => null,
        'st' => "<div class=\"status\">{$status}</div>",
        'numdis' => 0,
        'dis' => "<li>{$dis}</li>",
        'sdes' => $sdes,
        'text' => null,
        'discountofweb' => null,
        'availableforpreorder' => FALSE,
        'frtType' => 0,
        'tang_kem' => $tang_kem,
    );
    
    Response(json_encode($response));
    
    exit();
}

function get_product_meta(){
    $product_id = getRequest('product_id');
    $response = array(
        'gia_cu' => get_post_meta( $product_id, 'gia_cu', true ),
        'gia_moi' => get_post_meta( $product_id, 'gia_moi', true ),
        'giam_gia' => get_post_meta( $product_id, 'giam_gia', true ),
        'tinh_trang' => get_post_meta( $product_id, 'tinh_trang', true ),
        'sp_displayorder' => get_post_meta( $product_id, 'sp_displayorder', true ),
    );
    
    Response(json_encode($response));
    
    exit();
}