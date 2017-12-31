var ShowMessage = function(){
    return {
        open: function(msg){
            var msgContent = $("#msg .msg-content");
            if($("#msg").is(":hidden")){
                $("#msg").fadeIn('normal');
            }
            msgContent.html(msg);
            this.resize();
            this.close();
        },
        close: function(){
            setTimeout(function(){
                $("#msg").slideUp('slow').fadeOut('slow');
                setTimeout(function(){
                    $("#msg .msg-content").html('');
                }, 1000);
            }, 4000);
        },
        resize:function(){
            var msgContent = $("#msg .msg-content");
            var top = ($(window).height() - msgContent.height()) / 2;
            var left = ($(window).width() - msgContent.width()) / 2;
            msgContent.css({
                'top': top,
                'left': left
            });
        },
        setup: function(){
            $("#msg").css({
                'width': $(window).width(),
                'height': $(window).height()
            });
            $("#msg .msg-mask").click(function(){
                $("#msg").fadeOut('slow');
                $("#msg .msg-content").html('');
            });
            $(window).resize(function(){
                ShowMessage.resize();
            });
        },
        init:function(){
            $("head").append('<style type="text/css">\
            #msg { height: 100%; width: 100%; position: fixed; top: 0; z-index: 9999; display: none;}\
            #msg .msg-mask{ width: 100%; height: 100%; background: #ffffff; opacity: 0.6;}\
            #msg .msg-content{ border: 1px solid #fff; background: #F39500; padding: 15px; top: 50px; left: 50%; position: absolute; font-size: 15px; color: #ffffff; }\
            </style>');
            $("body").append('<div id="msg"><div class="msg-mask"></div><div class="msg-content"></div></div>');
            ShowMessage.setup();
        }
    }
}(); 

function setLocation(n){
    window.location.href=n
}

/**
 * Scrollable
 */
function scrollToElement(id){
    $('body,html').animate({
        scrollTop: $(id).offset().top - $(id).outerHeight(true)
    }, 800);
}

/**
 * Check valid an email
 */
function isValidEmail(email) {
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return filter.test(email);
}

/**
 * get clock
 * 
 * @param int type
 */
function getClock(disp){
    var curTime=new Date();
    var nhours=curTime.getHours();
    var nmins=curTime.getMinutes();
    var nsecn=curTime.getSeconds();
    var nday=curTime.getDay();
    var nmonth=curTime.getMonth();
    var ntoday=curTime.getDate();
    var nyear=curTime.getYear();
    var AMorPM=" ";
    if (nhours>=12)AMorPM="";
    else AMorPM="";
    if (nhours>=13)nhours-=12;
    if (nhours==0)nhours=12;
    if (nsecn<10)nsecn="0"+nsecn;
    if (nmins<10)nmins="0"+nmins;
    if (nday==0)nday="Chủ nhật";
    if (nday==1)nday="Thứ hai";
    if (nday==2)nday="Thứ ba";
    if (nday==3)nday="Thứ tư";
    if (nday==4)nday="Thứ năm";
    if (nday==5)nday="Thứ sáu";
    if (nday==6)nday="Thứ bảy";
    nmonth+=1;
    if (nyear<=99)nyear= "19"+nyear;
    if ((nyear>99) && (nyear<2000))nyear+=1900;
    var d;
    var Str0="";
    if (nhours>9) Str0 = "";
    else Str0 = "0";
    
    d= document.getElementById("theClock");
    var ouput = "";
    if(disp == 0){
        ouput = ntoday + "/" + nmonth + "/" + nyear + " " + Str0 + nhours + ":" + nmins + ":" + nsecn;
    }else{
        ouput = " <span>" + Str0 + nhours+" giờ "+nmins+" phút "+nsecn + " giây - " + AMorPM + "</span>" + nday+" - Ngày " + ntoday +" tháng " + nmonth + " năm " + nyear;
    }
    
    d.innerHTML = ouput;
    setTimeout('getClock(1)',1000);
}

/**
 * Set Bookmark
 */
function setBookmark(url, title){
    if (window.sidebar) // firefox
        window.sidebar.addPanel(title, url, "");
    else if(window.opera && window.print){ // opera
        var elem = document.createElement('a');
        elem.setAttribute('href',url);
        elem.setAttribute('title',title);
        elem.setAttribute('rel','sidebar');
        elem.click();
    }
    else if(document.all)// ie
        window.external.AddFavorite(url, title);
}

$(function(){
    // Read a page's GET URL variables and return them as an associative array.
    $.extend({
        getUrlVars: function(){
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        },
        getUrlVar: function(name){
            return $.getUrlVars()[name];
        }
    });
    
    $(window).load(function(){
        ShowMessage.init();
    });
});