<?php if(get_settings('ad_floating_active') == 1): ?>
<script type="text/javascript">
    //Alert MsgAd
    clicksor_enable_MsgAlert = true;
    //default pop-under house ad url
    clicksor_enable_pop = true; clicksor_frequencyCap = 0.1;
    durl = '';
    //default banner house ad url
    clicksor_default_url = '';
    clicksor_banner_border = '#000f30'; clicksor_banner_ad_bg = '#FFFFFF';
    clicksor_banner_link_color = '#0c15ff'; clicksor_banner_text_color = '#da0041';
    clicksor_banner_image_banner = true; clicksor_banner_text_banner = true;
    clicksor_layer_border_color = '';
    clicksor_layer_ad_bg = ''; clicksor_layer_ad_link_color = '';
    clicksor_layer_ad_text_color = ''; clicksor_text_link_bg = '';
    clicksor_text_link_color = '#0c59ff'; clicksor_enable_text_link = true;
    clicksor_enable_VideoAd = true;
</script>
<div id="fl813691">
    <div id="eb951855">
        <div id="cob263512">
            <div id="coh963846">
                <ul id="coc67178">
                    <li id="pf204652hide"><a class="min" href="javascript:pf204652clickhide();" title="Ẩn đi">Ẩn</a></li>
                    <li id="pf204652show" style="display: none;"><a class="max" href="javascript:pf204652clickshow();" title="Hiện lại">Xem </a></li>

                    <li id="pf204652close"><a class="close" href="javascript:pf204652clickclose();" title="Đóng lại">Đóng</a></li>
                </ul>
                &nbsp;LIÊN HỆ MUA HÀNG
            </div>
            <div id="co453569">
                <?php echo stripslashes(get_option('ad_floating'));  ?>
            </div>
        </div>
    </div>
</div>
<script>
    pf204652bottomLayer = document.getElementById('fl813691');
    var pf204652IntervalId = 0;
    var pf204652maxHeight = 102;//Chieu cao khung quang cao
    var pf204652minHeight = 20;
    var pf204652curHeight = 0;
    function pf204652show( ){
        pf204652curHeight += 2;
        if (pf204652curHeight > pf204652maxHeight){
            clearInterval ( pf204652IntervalId );
        }
        pf204652bottomLayer.style.height = pf204652curHeight+'px';
    }
    function pf204652hide( ){
        pf204652curHeight -= 3;
        if (pf204652curHeight < pf204652minHeight){
            clearInterval ( pf204652IntervalId );
        }
        pf204652bottomLayer.style.height = pf204652curHeight+'px';
    }
    pf204652IntervalId = setInterval ( 'pf204652show()', 5 );
    function pf204652clickhide(){
        document.getElementById('pf204652hide').style.display='none';
        document.getElementById('pf204652show').style.display='inline';
        pf204652IntervalId = setInterval ( 'pf204652hide()', 5 );
    }
    function pf204652clickshow(){
        document.getElementById('pf204652hide').style.display='inline';
        document.getElementById('pf204652show').style.display='none';
        pf204652IntervalId = setInterval ( 'pf204652show()', 5 );
    }
    function pf204652clickclose(){
        document.body.style.marginBottom = '0px';
        pf204652bottomLayer.style.display = 'none';
    }
</script>
<?php endif; ?>