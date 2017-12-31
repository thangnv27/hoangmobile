(function($) {
    
    $('#posts-filter a.editinline').each(function(){
        $(this).live('click', function() {
            var id = inlineEditPost.getId(this);
            
            $(".product-custom-fields span.spinner").show();
            
            $.ajax({
                type: 'GET',
                url: ajaxurl,
                data: {
                    action: 'get_product_meta',
                    product_id: id
                },
                dataType: 'json',
                cache: false,
                success: function(response, textStatus, XMLHttpRequest){
                    $("input[name='gia_cu']").val(response.gia_cu);
                    $("input[name='gia_moi']").val(response.gia_moi);
                    $("input[name='giam_gia']").val(response.giam_gia);
                    $("input[name='sp_displayorder']").val(response.sp_displayorder);
                    $("select[name='tinh_trang']").val(response.tinh_trang);
                    $("select[name='tinh_trang'] option[value='" + response.tinh_trang + "']").prop('selected','true');
                },  
                error: function(MLHttpRequest, textStatus, errorThrown){
                },
                complete:function(){
                    $(".product-custom-fields span.spinner").hide();
                }
            }); 
        });
    });
    
})(jQuery);