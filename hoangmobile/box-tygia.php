<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/thuvien.js"></script>

<table border="0" cellpadding="0" cellspacing="0" class="block_green1" width="190">
    <tr>
        <td width="90%"><div class="redtitle">Tỷ giá ngoại tệ</div></td>
        <td width="10%">
            <img id="AdTyGia" src="<?php bloginfo('stylesheet_directory'); ?>/images/AdImgUp.gif" border="0" 
                 onclick='SetViewTableDiv(this.id);' style="cursor: pointer;"/>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="AdTyGiaLoc">
                <?php
                include_once 'includes/tygia.php';
                $rate = new Rate();
                $rate->source = 'http://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx';
                $rate->show("short", array("AUD", "EUR", "GBP", "JPY", "USD"));
                ?>
            </div>
        </td>
    </tr>
</table>
<!--
<img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/spacer.gif" width="190" height="15" />

<table border="0" class="block_blue1" cellpadding="0" cellspacing="0" width="190">
    <tr>
        <td width="90%">
            <div class="redtitle">Thời tiết</div>
        </td>
        <td width="10%">
            <img id="AdThoiTiet" src="<?php bloginfo('stylesheet_directory'); ?>/images/AdImgUp.gif" border="0" onclick='SetViewTableDiv(this.id);'/>    
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="AdThoiTietLoc">
                <script type="text/javascript" language="JavaScript" src="http://www.vnexpress.net/Service/Weather_Content.js"></script>
                <script type="text/javascript" language="JavaScript" src="<?php bloginfo('stylesheet_directory'); ?>/js/thoitiet.js"></script>
            </div>
        </td>
    </tr>
</table>
-->