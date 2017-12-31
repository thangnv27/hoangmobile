var ajaxUrl = siteUrl + '/wp-admin/admin-ajax.php';
var reload = false;
function displayPopupNotification(n,t,i){
    var f,r,u,e;
    if(f=t=="success"?$("#dialog-notifications-success"):t=="error"?$("#dialog-notifications-error"):$("#dialog-notifications-success"),r="",typeof n=="string")r="<p>"+n+"<\/p>";else for(u=0;u<n.length;u++)r=r+"<p>"+n[u]+"<\/p>";
    f.html(r),e=i?!0:!1,f.dialog({
        modal:e
    })
}
function displayBarNotification(n,t,i){
    var u,r,f;
    if(clearTimeout(barNotificationTimeout),u="success",t=="success"?u="success":t=="error"&&(u="error"),$("#bar-notification").removeClass("success").removeClass("error"),$("#bar-notification .content").remove(),r="",typeof n=="string")r='<p class="content">'+n+"<\/p>";else for(f=0;f<n.length;f++)r=r+'<p class="content">'+n[f]+"<\/p>";
    $("#bar-notification").append(r).addClass(u).fadeIn("slow").mouseenter(function(){
        clearTimeout(barNotificationTimeout)
    }),$("#bar-notification .close").unbind("click").click(function(){
        $("#bar-notification").fadeOut("slow")
    }),i>0&&(barNotificationTimeout=setTimeout(function(){
        $("#bar-notification").fadeOut("slow")
    },i))
}
function displayAjaxLoading(n){
    n?$(".ajax-loading-block-window").show():$(".ajax-loading-block-window").hide("slow");
}
function ShowPoupEditOrder(){
    displayAjaxLoading(true);
    $.get(ajaxUrl, {
        'action':'loadCartEditOrder'
    }, function(data) {
        $.colorbox({
            html:data, 
            overlayClose: false,
            onClosed:function(){
                if(reload){
                    window.location.reload();
                }
            }
        });
        displayAjaxLoading(false);
    });
}
function ShowPoupOrderDetail(html){
    displayAjaxLoading(true);
    $.colorbox({
        width: 840,
        html:html
    });
    displayAjaxLoading(false);
}
    
var AjaxCart = {
    addToCart:function(){
        var form = $("#product-details-form");

        $("#addCart").click(function(){
            displayAjaxLoading(true);

            $.ajax({  
                type: 'POST',
                url: ajaxUrl,
                data: form.serialize(),
                dataType: 'json',
                cache: false,
                success: function(response, textStatus, XMLHttpRequest){
                    $('body,html').animate({
                        scrollTop: 0
                    }, 800);
                    
                    if(response && response.status == 'success'){
                        $("#cart-qity").html("(" + response.countcart + ")");
                        $('#flyout-cart').html('').show();
                        setTimeout(function(){
                            AjaxCart.loadCart();
                        }, 1000);
                    }else if(response.status == 'error'){
                    }
                },  
                error: function(MLHttpRequest, textStatus, errorThrown){
                },
                complete:function(){
                    displayAjaxLoading(false);
                }
            }); 

            return false;
        });
    },
    loadCart:function(){
        var flyout_cart = $("#flyout-cart");

        flyout_cart.html("<img style='margin-left:240px;' src='" + themeUrl + "/images/loadingSmall.gif' />");

        $.ajax({
            type: 'GET',
            url: ajaxUrl,
            data: {
                action: 'loadCartAjax'
            },
            dataType: 'json',
            cache: false,
            success: function(response, textStatus, XMLHttpRequest){
                if(response && response.status == 'success'){
                    flyout_cart.html(response.message);
                    flyout_cart.show();
                }else if(response.status == 'error'){
                }
            },  
            error: function(MLHttpRequest, textStatus, errorThrown){
            },
            complete:function(){
            }
        }); 
    },
    deleteItem:function(product_id){
        displayAjaxLoading(true);
        
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'deleteCartItem',
                id: product_id
            },
            dataType: 'json',
            cache: false,
            success: function(response, textStatus, XMLHttpRequest){
                if(response && response.status == 'success'){
                    $("#cart-qity").html("(" + response.countcart + ")");
                    $("#product_item_" + product_id).remove();
                    $(".cartList .sum").html(response.total_amount);
                    reload = true;
                }else if(response.status == 'error'){
                }
            },  
            error: function(MLHttpRequest, textStatus, errorThrown){
            },
            complete:function(){
                displayAjaxLoading(false);
            }
        }); 
    },
    updateItem:function(product_id, quantity){
        displayAjaxLoading(true);
        
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'updateCartItem',
                id: product_id,
                quantity: quantity
            },
            dataType: 'json',
            cache: false,
            success: function(response, textStatus, XMLHttpRequest){
                if(response && response.status == 'success'){
                    $("#product_item_" + product_id + " .product-subtotal").html(response.item_amount);
                    $(".cartList .sum").html(response.total_amount);
                    reload = true;
                }else if(response.status == 'error'){
                }
            },  
            error: function(MLHttpRequest, textStatus, errorThrown){
            },
            complete:function(){
                displayAjaxLoading(false);
            }
        }); 
    },
    orderComplete:function(){
        $("input.btnMuaHang").click(function(){
            var form = $("form#frmOrderCheckOut");
            var valid = true;
            
            $("#billing input[data-val='true'], #billing select[data-val='true']").each(function(){
                if($(this).val().trim().length == 0){
                    valid = false;
                    $(this).next().next('span').text($(this).attr('data-val-required'));
                }
            });

            if(valid){
                displayAjaxLoading(true);
                
                $.ajax({
                    //this is the php file that processes the data and send mail
                    url: ajaxUrl,
                    //GET method is used
                    type: "POST",
                    //pass the data
                    data: form.serialize(),
                    //Data type
                    dataType: "json",
                    //Do not cache the page
                    cache: false,
                    //success
                    success: function (response) {
                        if(response && response.status == 'success'){
                            window.location = siteUrl;
                        }else if(response.status == 'error'){
                            alert(response.message);
                        }
                    },
                    error: function(MLHttpRequest, textStatus, errorThrown){
                    },
                    complete: function(){
                        displayAjaxLoading(false);
                    }
                });
            }

            return false;
        });
    }
};
var AjaxFavorite = {
    addProduct:function(product_id, quantity, token){
        displayAjaxLoading(true);
        
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'addProductToFavorite',
                product_id: product_id,
                quantity: quantity,
                token: token
            },
            dataType: 'json',
            cache: false,
            success: function(response, textStatus, XMLHttpRequest){
                if(response && response.status == 'success'){
                    $('body,html').animate({
                        scrollTop: 0
                    }, 800);
                    
                    $("a.wishlist-qty").html("(" + response.favor_count + ")");
                    alert(response.message);
                }else if(response.status == 'error'){
                    alert(response.message);
                }
            },  
            error: function(MLHttpRequest, textStatus, errorThrown){
            },
            complete:function(){
                displayAjaxLoading(false);
            }
        });
    },
    deleteProduct:function(favorite_id){
        displayAjaxLoading(true);
        
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'deleteFavoriteProduct',
                favorite_id: favorite_id
            },
            dataType: 'json',
            cache: false,
            success: function(response, textStatus, XMLHttpRequest){
                if(response && response.status == 'success'){
                    $("#product_item_" + favorite_id).remove();
                    $("a.wishlist-qty").html("(" + response.favor_count + ")");
                }else if(response.status == 'error'){
                    alert(response.message);
                }
            },  
            error: function(MLHttpRequest, textStatus, errorThrown){
            },
            complete:function(){
                displayAjaxLoading(false);
            }
        }); 
    },
    updateProduct:function(favorite_id, product_id, quantity){
        displayAjaxLoading(true);
        
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'updateFavoriteProduct',
                favorite_id: favorite_id,
                product_id: product_id,
                quantity: quantity
            },
            dataType: 'json',
            cache: false,
            success: function(response, textStatus, XMLHttpRequest){
                if(response && response.status == 'success'){
                    $("#product_item_" + favorite_id + " .product-subtotal").html(response.subtotal);
                }else if(response.status == 'error'){
                    alert(response.message);
                }
            },  
            error: function(MLHttpRequest, textStatus, errorThrown){
            },
            complete:function(){
                displayAjaxLoading(false);
            }
        }); 
    },
    addToCart:function(id, thumb, title, price, quantity, token){
        displayAjaxLoading(true);

        $.ajax({  
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'addToCart',
                id: id,
                thumb: thumb,
                title: title,
                price: price,
                quantity: quantity,
                token: token
            },
            dataType: 'json',
            cache: false,
            success: function(response, textStatus, XMLHttpRequest){
                $('body,html').animate({
                    scrollTop: 0
                }, 800);

                if(response && response.status == 'success'){
                    $("#cart-qity").html("(" + response.countcart + ")");
                    $('#flyout-cart').html('').show();
                    setTimeout(function(){
                        AjaxCart.loadCart();
                    }, 1000);
                }else if(response.status == 'error'){
                }
            },  
            error: function(MLHttpRequest, textStatus, errorThrown){
            },
            complete:function(){
                displayAjaxLoading(false);
            }
        });
    }
};

jQuery(document).ready(function($) {
    $('#cart,#carttext,#flyout-cart').live('mouseenter', function() {
        if ($("#flyout-cart").html().trim().length == 0){
            AjaxCart.loadCart();
        }
        $('#flyout-cart').show();
    });
    $('#cart,#carttext,#flyout-cart').live('mouseleave', function() {
        $('#flyout-cart').hide();
    });
}); 