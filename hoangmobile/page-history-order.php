<?php
/*
  Template Name: Page History order
 */
?>
<?php
if(!isset($_SESSION['current_user_login'])) {
    header("location: " . wp_login_url(getCurrentRquestUrl()));
}
if(!defined('UNAPPROVED')) define ('UNAPPROVED', 0);
if(!defined('APPROVED')) define ('APPROVED', 1);
if(!defined('CANCELLED')) define ('CANCELLED', 2);
?>
<?php get_header(); ?>

<?php include 'left_menu_product.php'; ?>

<div class="center">
    <div class="ajax-loading-block-window" style="display: none;" id="showloadingim">
        <div class="loading-image">
        </div>
    </div>
    
    <div style="margin-top:0" class="col1020">
        <div class="colBig">
            <div class="donhang">
                <div class="redTitle"> Lịch sử đặt hàng</div>
                <table>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th> Tình trạng đơn hàng</th>
                        <th>Ngày đặt hàng</th>
                        <th> Tổng tiền</th>
                        <th></th>
                    </tr>
                    <?php
                    $records = get_history_order();
                    $page = 1;
                    foreach ($records as $key => $row) :
                        $status = "Đang chờ";
                        $transfer = "Chưa chuyển hàng";
                        if($row->status == APPROVED){
                            $status = "Đã xong";
                            $transfer = "Đã chuyển hàng";
                        }elseif($row->status == CANCELLED){
                            $status = "Bỏ qua";
                            $transfer = "Không chuyển hàng";
                        }
                        $customer_info = json_decode($row->customer_info);
                        $products = json_decode($row->products);
                        echo <<<HTML
                    <tr page="{$page}">
                        <td>{$row->ID}</td>
                        <td>{$status}</td>
                        <td>{$row->created_at}</td>
                        <td>{$row->total_amount} đ</td>
                        <td>
                            <input type="button" value=" " class="btnXem" />
                            <div id="view_order_<?php echo $row->ID; ?>" style="display: none;">
                                <div class="addrRowTitle">Chi tiết đơn hàng</div>
                                <div class="donhangBg">
                                    <div class="donhangBgTop"></div>
                                    <div class="donhangBgCenter">
                                        <div class="donhangDetail1">
                                        <b>Đơn hàng: #{$row->ID}</b><br />
                                         Ngày đặt hàng: <i>{$row->created_at}</i><br />
                                        Tình trạng:<i> {$status}</i><br />
                                         Tổng tiền: <i>{$row->total_amount} đ</i><br><br />
                                        <b>Tình trạng chuyển hàng: </b><br />
                                         {$transfer}
                                        </div>
                                        <div class="donhangDetail2">
                                            <b> Địa chỉ thanh toán</b><br>
                                            {$customer_info->lastname} {$customer_info->firstname}<br>
                                             Email: {$row->user_email} | Điện thoại: {$customer_info->phone}<br>
                                            Địa chỉ: {$customer_info->address} - {$customer_info->ward}, {$customer_info->district}, {$customer_info->city}<br />
                                            <br>
                                        <b>Phương thức thanh toán</b><br />
                                        {$row->payment_method}
                                       </div>
                                    </div>
                                    <div class="donhangBgBottom"></div>
                                </div>
                                
                                <div class="donhangBg" style="margin-top:20px">
                                    <div class="addrRowTitle">Chi tiết sản phẩm đã mua</div>
                                    <table>
                                        <tbody><tr>
                                                <th style="width: 300px"> Tên sản phẩm</th>
                                                <th> Giá</th>
                                                <th style="width: 70px">  Số lượng</th>
                                                <th>  Tổng tiền</th>
                                            </tr>
HTML;
                                    $totalAmount = 0;
                                    foreach ($products as $product) :
                                        $pos = strpos($product->amount, '.');
                                        if($pos){
                                            $amount = str_replace('.', '', $product->amount);
                                            $totalAmount += $amount;
                                        }else{
                                            $totalAmount += $product->amount;
                                        }
                                        $permalink = get_permalink($product->id);
                                        echo <<<HTML
                                            <tr>
                                                <td>
                                                    <em><a title="View details" href="{$permalink}" target="_blank" style="color:#373737;">{$product->title}</a></em>
                                                </td>
                                                <td>{$product->price} đ</td>
                                                <td>{$product->quantity}</td>
                                                <td>{$product->amount} đ</td>
                                            </tr>
HTML;
                                    endforeach;
                                    
                                    $totalAmount = number_format($totalAmount,0,',','.');
                                    echo <<<HTML
                                            <tr>
                                                <td colspan="3" style="text-align:right;"><strong>Thành tiền</strong></td>
                                                <td>{$totalAmount} đ</td>
                                            </tr>
                                            <tr>
                                                <td class="cart-total-left" colspan="3">
                                                    <strong>Phí vận chuyển tạm tính:</strong>
                                                </td>
                                                <td class="cart-total-right">Miễn phí</td>
                                            </tr>
                                            <tr>
                                                <td class="cart-total-left" colspan="3">
                                                    <strong>Tổng tiền:</strong>
                                                </td>
                                                <td class="cart-total-right">
                                                    <span class="nobr"><strong>{$totalAmount} đ</strong></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </td>
                    </tr>
HTML;
                    endforeach; 
                    ?>
                </table>
                <div id="clearBoth">

                    <script type="text/javascript">
                        $(function() {
                            $(".btnXem").click(function(){
                                ShowPoupOrderDetail($(this).next().html());
                            });
                            
                            //--------------------------------
                            
                            var numItem = $('tr[page]').length;
                            var numpage = 1;
                            var currpage = 1;

                            for (var i = 0; i < numItem; i++) {
                                if (i % 10 == 0) {
                                    $('#page').append(numpage == 1 ? "<a rel='1' class='active'>" + numpage + "</a>" : "<a rel='" + numpage + "'>" + numpage + "</a>");
                                    numpage++;
                                }
                            }
                            if (numpage > 1) {
                                $('#page').append("<a rel='2'>Next</a>").prepend("<a rel='1'>Preview</a>");
                                $('#page a').first().hide();
                            }
                            $('#page a').click(function() {
                                currpage = parseInt($(this).attr('rel'));
                                $('tr[page=' + $(this).attr('rel') + ']').show();
                                $('tr[page][page!=' + $(this).attr('rel') + ']').hide();
                                $('#page a').removeClass('active');
                                if ($(this).html() != 'Next' || $(this).html() != 'Preview')
                                    $(this).addClass('active');
                                if (currpage < numpage) {
                                    $('#page a').last().attr('rel', parseInt(currpage + 1));
                                }
                                if (currpage >= 1) {
                                    $('#page a').first().attr('rel', currpage);
                                }
                                if (currpage == numpage - 1)
                                    $('#page a').last().hide();
                                else
                                    $('#page a').last().show();
                                if (currpage == 1)
                                    $('#page a').first().hide();
                                else
                                    $('#page a').first().show();
                            })
                        })
                    </script>
                    <div id="page" style="margin-top:0px" class="paging"></div>
                    
                </div>
                
                <div class="address-list"></div>
            </div>
        </div>
    </div>
    <div class="clearBoth"></div>

    <!--San pham vua xem-->
    <!--<div class="block">
        <div id="featured-recently"></div>
    </div>-->

</div>

</div>

<?php get_footer(); ?>