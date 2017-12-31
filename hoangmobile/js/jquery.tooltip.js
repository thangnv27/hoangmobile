/*Create QUANGNT 4/10/2012*/
var tooltip = {}, isLoad = 0;
function showToolTip(id) {
    
var strHTML=GetDataTooltip(id);
    Tip(strHTML, WIDTH, 300, ABOVE, true);
}

function GetDataTooltip(id) {
    var el = $("div[onmouseover=\"showToolTip('" + id + "')\"]");
    /*truntp modified 10/12/2012*/
    if (tooltip[id] == undefined || tooltip[id] == null) {
        el.unbind('onmouseover');
        if (isLoad != id) {
            isLoad = id;
            $.ajax({
                url: siteUrl + '/wp-admin/admin-ajax.php',
                data:{
                    action: 'product_tooltip',
                    product_id: id
                },
                dataType: 'json',
                cache: true,
                async: false,
                type: 'GET',
                success: function (data) {
                    tooltip[id] = data;
                    el.mousemove(function (e) {
                        el.bind('onmouseover', showToolTip(id));
                        el.unbind('mousemove');
                    });
                    isLoad = 0;
                }
            });
        }
    }
    return getStringTooltip(id);
}

function getStringTooltip(id) {
    if (tooltip[id] == undefined || tooltip[id] == null)
        return "";
    var data = tooltip[id];
    var strhtml = "<div id=\"mystickytooltip\" class=\"mytooltip\">";
    strhtml += "<div id=\"sticky" + data.id + "\" class=\"atip\">";
    strhtml += "<div class=\"tipname\">" + data.n + "</div>";
    strhtml += "<div class=\"tipprice\">";
    // strhtml += "<span class=\"giaol\">" +data.p+"</span>";
    if(data.p!=null)
        strhtml += "<span class=\"giaol\">" + data.p + "</span>";
    //    strhtml +=  data.pold == "null" || data.pold == "0" || data.pold == "" ? "" : "</br>Giá shop:<span class=\"giaol\">" +data.pold+ "</span>";
    //strhtml += data.sv == "" ? "" : "</br>Ti?t ki?m:<span class=\"giaol\">" +data.sv+ "</span>";
    strhtml += "</div>";
    //strhtml += "<div class=\"status\">" + data.st + "</div>"; // "<div class=\"statusEnd\" >H?t hàng</div>";
  
    if(data.availableforpreorder != null &&data.availableforpreorder){
        strhtml += "<div class=\"status\">Đặt hàng</div><br/>";
    }else if (data.frtType == 20) {
        strhtml += "<div class=\"status\">Sản phẩm không kinh doanh</div><br/>";
    }else {
         strhtml += "<div class=\"status\">" + data.st + "</div>";
    }
    strhtml += "<hr class=\"line\"/>";
    //if (data.numdis > 0)
        //strhtml += "<span class='tip-giamgia'>Giảm giá: <span class=\"giaol\">" + data.numdis + "%</span></span>";
    if ((data.dis != null && data.dis!="<li></li>") || data.sv != null || data.text != null||data.discountofweb!=null) {
        strhtml += "<div class=\"promotion-tooltip\"><span class=\"promotionText\">Khuyến mãi</span>";
        strhtml += "<ul class=\"listPromotion\">";
        if (data.dis != null)
            strhtml += data.dis;
        if (data.sv != null)
            strhtml += "<li>Phiếu mua hàng trị giá " + data.sv + "</li>";
        if (data.text != null)
            strhtml += "<li>" + data.text + "</li>";
        if (data.discountofweb != null)
            strhtml += "<li>Mua online giảm thêm " + data.discountofweb + "</li>";
        strhtml += "</ul>";       
        strhtml += "</div>";
    }
    if (data.tang_kem != "" && data.tang_kem != null) {
        strhtml += "<div class=\"promotion-tooltip\"><span class=\"promotionText\">Tặng</span>";
        strhtml += "<ul class=\"listPromotion\">";
        strhtml += data.tang_kem;
        strhtml += "</ul>";       
        strhtml += "</div>";
    }
    strhtml += "<div class=\"content\">" + data.sdes + "</div>";
    strhtml += "</div>";
    strhtml += "</div>";
    strhtml += "</div>";

    return strhtml;
}