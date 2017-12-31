var TSlider = function(){
    return {
        productThumbnail:function(){
            var list=4;
            function PrevNext(a) {

                var ulWidth = $('#thumb li').length;
                var left = $('#thumb').position().left;
                if (a == 1) {
                    if (ulWidth > 4 && list < ulWidth) {
                        $('#thumb').animate({ left: '-=75' }, 500);
                        list++;
                        $('.thumbprev').show();                    
                    }
                    else {
                        $('.thumbnext').hide();
                        $('.thumbprev').show();
                    }
                } else {
                    if (list > 4) {
                        $('#thumb').animate({ left: '+=75' }, 500);
                        list--;
                        $('.thumbnext').show();                    

                    }
                    else {
                        $('.thumbprev').hide();
                        $('.thumbnext').show();
                    }
                }
            }

            $('#thumb li a').click(function () {
                $('#thumb li a img').removeClass('selecImg');
                $('#imgView').attr('src', $(this).attr('href')).parent('a').attr('href', $(this).attr('href'));
                $(this).children('img').addClass('selecImg');
                return false;
            });
            if ($('#thumb li').length <= 4) {
                $('.thumbnext').hide();
            }
            $('.thumbnext').click(function () { PrevNext(1); });
            $('.thumbprev').click(function () { PrevNext(0); });
        }
    }
}();
var User = function(){
    return {
        login:function(){
            $("form#loginform").submit(function(){
                var valid = true;
                var msg = "<p>Các trường có dấu * là bắt buộc!</p>";
                
                $("form#loginform input[type='text'], form#loginform input[type='password']").each(function(){
                    if($(this).val().length == 0){
                        $(this).css({
                            'border': '1px solid red'
                        });
                        valid = false;
                    }else{
                        $(this).css({
                            'border': '1px solid #d0d0d0'
                        });
                    }
                });
                
                if(!valid){
                    $("#message").html(msg).addClass('warning');
                    return false;
                }
            });
        },
        register:function(){
            $("form#registerform").submit(function(){
                var valid = true;
                var msg = "<p>Các trường có dấu * là bắt buộc!</p>";
                var emailField = $("form#registerform #user_email");
                var pwd1 = $("form#registerform #user_pass");
                var pwd2 = $("form#registerform #user_pass2");
                
                $("form#registerform input[type='text'], form#registerform input[type='password']").each(function(){
                    if($(this).val().length == 0){
                        $(this).css({
                            'border': '1px solid red'
                        });
                        valid = false;
                    }else{
                        $(this).css({
                            'border': '1px solid #d0d0d0'
                        });
                    }
                });
                if(pwd2.val() != pwd1.val()){
                    pwd2.css({
                        'border': '1px solid red'
                    });
                    valid = false;
                    msg += "<p>Xác nhận mật khẩu không chính xác!</p>";
                }else{
                    pwd2.css({
                        'border': '1px solid #d0d0d0'
                    });
                }
                if(!isValidEmail(emailField.val())){
                    emailField.css({
                        'border': '1px solid red'
                    });
                    valid = false;
                    msg += "<p>Địa chỉ email không hợp lệ!</p>";
                }else{
                    emailField.css({
                        'border': '1px solid #d0d0d0'
                    });
                }
                
                if(!valid){
                    $("#message").html(msg).addClass('warning');
                    return false;
                }
            });
        }
    }
}();
var FixedColumn = function(){
    return {
        firstLine:function(){
            var summaries = $('.firstLine');
            summaries.each(function(i) {
                var summary = $(summaries[i]);
                var next = summaries[i + 1];

                summary.scrollToFixed({
                    marginTop: $('#wpadminbar').outerHeight(true),
                    limit: function() {
                        var limit = 0;
                        if (next) {
                            limit = $(next).offset().top - $(this).outerHeight(true) - 10;
                        } else {
                            // footer offset top
                            limit = $('.footer').offset().top - $(this).outerHeight(true) - 10;
                        }
                        return limit;
                    },
                    zIndex: 999
                });
            });
        }
    }
}();
var Lightbox = function(){
    return {
        singleProduct:function(){
//            $('.productViewImg a[lightbox=lightbox]').colorbox({
//                maxHeight: $(window).outerHeight(true) - $('#wpadminbar').outerHeight(true)
//            });
            $('.productView1 a.lightbox').colorbox({
                maxHeight: $(window).outerHeight(true) - $('#wpadminbar').outerHeight(true),
                rel: 'lightbox'
            });
            
            $('.article img').each(function(){
                $(this).attr('href', $(this).attr('src')).css({
                    'cursor': 'pointer'
                });
            }).addClass('article-group-img').colorbox({rel:'article-group-img'});
        }
    }
}();

// Run
$(function(){
    //FixedColumn.firstLine();
    
    if($('#wpadminbar').length > 0){
        $(".back-top").css({
            'top': $('#wpadminbar').outerHeight(true) //+ $(".firstLine").outerHeight(true)
        });
    }
    /*
    function tick(){
        $('#ticker_01frt li:first').slideUp( function () { 
            $(this).appendTo($('#ticker_01frt')).slideDown(); 
        });
    }
    setInterval(function(){
        tick ()
    }, 3000);
    */
    // Search form
    $('#search-catText').html($(this).find('option:selected').html());
    $('#Cid').change(function() {
        $('#search-catText').html($(this).find('option:selected').html());
    });
    $('#txtSearch').keydown(function(event) {
        if (event.keycode == 13){
            $('#frmSearch').submit();
        }
    }).focus(function(){
    });
    
    function check_small_search_form() {
        var search_terms = $("#txtSearch");
        if (search_terms.val() == "") {
            search_terms.focus();
            return false;
        }
        return true;
    }
    
    // footer columns
    $(".companyContent .contact .companySingle ul.menu").each(function(index){
        $(this).removeClass('menu').addClass('col' + (index + 1));
    });
    
    // single product tabs
    $("#tabs").tabs();
});