<?php global $shortname; ?>

<div class="clearBoth"></div>
<div class="footer">
    <div class="about">
        <div class="aboutBgTop"></div>
        <div class="aboutBgCenter">
            <div class="aboutContent"><?php echo stripslashes(get_settings('footer_notes')); ?></div>
        </div>
        <div class="aboutBgBottom"></div>
    </div>
    <!--about-->

    <!--tienIch-->
    <div class="clearBoth"></div>
    <div class="companyArea">
        <div class="companyContent">
            <div class="contact">
                <?php 
                if ( function_exists('dynamic_sidebar') ) { 
                    dynamic_sidebar('footer-links-column-1'); 
                    dynamic_sidebar('footer-links-column-2');
                }
                ?>
                <div class="companySingle">
                    <div class="companyTitle">kết nối với <?php bloginfo('name'); ?></div>
                    <div class="line4"></div>
                    <div class="companyList">
                        <ul id="col3">
                            <li>
                                <a rel="nofollow" target="_blank" href="<?php echo get_option($shortname . '_fbURL'); ?>">
                                    <img width="15px" height="15px" alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/icons/facebook.png">
                                </a>
                                <a rel="nofollow" title="Facebook" target="_blank" href="<?php echo get_option($shortname . '_fbURL'); ?>">Facebook</a> 
                            </li>
                            <li>
                                <a rel="nofollow" target="_blank" href="<?php echo get_option($shortname . '_googlePlusURL'); ?>">
                                    <img width="15px" height="15px" alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/icons/google-plus.png">
                                </a>
                                <a rel="nofollow" title="Google Plus" target="_blank" href="<?php echo get_option($shortname . '_googlePlusURL'); ?>">Google Plus</a> 
                            </li>
                            <li>
                                <a rel="nofollow" target="_blank" href="skype:<?php echo get_option($shortname . '_skypeID'); ?>?call">
                                    <img width="15px" height="15px" alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/icons/skype.png">
                                </a>
                                <a rel="nofollow" title="Skype" target="_blank" href="skype:<?php echo get_option($shortname . '_skypeID'); ?>?call">Skype</a> 
                            </li>
                        </ul>
                    </div>
                </div>
                <!--<div class="SupportArea">
                    <div class="supportPage">          
                        <div class="mobile"> Tư vấn Online 19006616</div>
                        (Từ 8h - 22h tất cả các ngày)                 
                    </div>
                    <div class="supportPage iconphone">  
                        <div class="mobile"> Giải quyết khiếu nại 19006616</div>
                        (Từ 8h - 22h tất cả các ngày)                 
                    </div>
                </div>-->
                
                <div class="clearBoth"></div>
            </div>
        </div>
        
        <div class="regBg">
            <div id="newsletter-subscribe-blockf" class="regFormArea">
                <div class="listEx">
                    <div class="ssl">
                        <a class="sslico"></a>                                   
                    </div>
                    <div class="ssl">
                        <span><a class="visa"></a></span>
                        <span><a class="b"></a></span><br>
                        <span><a class="master"></a></span>
                        <span><a class="onepay"></a></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="clearBoth"></div>
    </div>

    <!--companyContent-->

    <!--End Support Center-->    
    
    <div class="lineX mb0"></div>
    <div style="width: 100%; background: #111; color: #ffffff;margin-bottom: 50px;">
        <div class="copyright">
            <div class="cp-content-left"></div>
            <div class="cp-content-middle">
                <div>
                    <?php echo stripslashes(get_settings('footer_copyright')); ?>
                </div>
                <p>Copyright &copy; 2013 Hoang Mobile. All rights reserved. Powered by <a href="http://ppo.vn" title="Thiết kế web chuyên nghiệp">PPO.VN</a>.</p>
            </div>
            <div class="cp-content-right"></div>
        </div>
    </div>

    <div class="fixed_div">
        <div style="float:right;margin-right:8px">
            <div class="backToTop" id="topcontrol" style="display: none;"></div>
        </div>
        <div class="clearBoth"></div>    
        <div class="banner-footer-right"></div>     
    </div>
    <div style="left:0;right:auto" class="fixed_div banner-footer-left"></div>
</div>
<div class="clearBoth"></div>

<?php if(get_settings('message_floating_active') == 1): ?>
<div class="message_floating_popup">
    <div class="head">
        <div class="title">Thông báo từ <?php bloginfo('name'); ?></div>
        <div id="min_max" class="min"></div>
        <div class="clearfix"></div>
    </div>
    <div class="content">
        <?php echo stripslashes(get_settings('message_floating')); ?>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        var clickMin = true;
        var msg_sticky = $(".message_floating_popup");
        $("#min_max").click(function(){
            if(!msg_sticky.hasClass('minimize')){
                msg_sticky.addClass('minimize');
                $(this).addClass('max').removeClass('min');
            }else{
                msg_sticky.removeClass('minimize');
                $(this).addClass('min').removeClass('max');
            }
            clickMin = false;
        });
        $(window).load(function(){
            setTimeout(function(){
                if(clickMin){
                    if(!msg_sticky.hasClass('minimize')){
                        msg_sticky.addClass('minimize');
                        $("#min_max").addClass('max').removeClass('min');
                    }
                }
            }, 10000);
        });
    });
</script>
<?php endif; ?>

<?php include 'ad_floating.php'; ?>
<div class="bottombar" id="bottombar">		<table width="100%" cellspacing="0" cellpadding="0" border="0" style="MARGIN: 0px auto;">		<tbody>		<tr height="25">						<td width="420"><span class="text_pscroller1"><img border="0" align="absmiddle" src="<?php bloginfo('stylesheet_directory'); ?>/images/CallUs.png" alt="Gọi để mua hàng"> Gọi để mua hàng: <font size="4" color="red"><?php echo get_option($shortname . "_hotline"); ?></font></span></td>			<td width="400"><span class="text_pscroller1"><img border="0" align="absmiddle" src="<?php bloginfo('stylesheet_directory'); ?>/images/credit_card.png" alt="Thanh toán bằng thẻ"> Nhận thanh toán bằng thẻ Master, Visa...</span></td>			<td><span class="text_pscroller1"><img border="0" align="absmiddle" src="<?php bloginfo('stylesheet_directory'); ?>/images/giaohang.png" alt="Giao Hàng Miễn Phí Trong Nội Thành">Giao Hàng Miễn Phí Trong Nội Thành</span></td>		</tr>		</tbody></table></div>
<?php wp_footer(); ?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>