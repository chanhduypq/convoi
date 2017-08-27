<script type="text/javascript">    
    
    /**
     * 
     * @returns {Boolean}
     */
    function validate() {        
        $('input[name="quantity[]"]').removeClass("error_goods");
        $("div.error").hide();
        $("button[type='button']").css("border","none");
        
        if ($('input[name="sum"]').val() == '' || $('input[name="sum"]').val() == '0') {
            $("div.error").html("Thông tin hàng hóa chưa được nhập.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        

        goods_ids = $('select[name="goods_id[]"]').map(function() {
            return $(this).val();
        }).get();

        
        var sorted_arr = goods_ids.sort();

        var results = [];
        for (var i = 0; i < goods_ids.length - 1; i++) {
            if (sorted_arr[i + 1] == sorted_arr[i]) {
                results.push(sorted_arr[i]);
            }            
        }
        


        if (results.length > 0) {
            $("div.error").html("Đã có sự trùng lặp trong thông tin hàng hóa. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            goods = $(".goods");
            for (i = 0; i < results.length; i++) {
                color=getRandomColor();
                for (j = 0; j < goods.length; j++) {
                    if ($(goods[j]).val() == results[i]) {
//                        $(goods[j]).next().css("background-color", color);
                        $(goods[j]).next().css("border", "3px "+color+" solid");
                    }
                }
            }
            return false;
        }
        
        error_quantity=false;
        goods_ids = $('select[name="goods_id[]"]');
        for (var i = 0; i < goods_ids.length; i++) {   
            quantity=$(goods_ids[i]).next("input").val();
            if (quantity.indexOf(".") != -1) {
                quantity = quantity.split(".").join("");
            }
            quantity = parseInt(quantity);
            if(quantity<$(goods_ids[i]).parent().next("li").find("input").eq(0).val()){
                error_quantity=true;
                $(goods_ids[i]).parent().next("li").find("input").eq(0).addClass('error_goods');
            }
        }
        
        if(error_quantity==true){
            $("div.error").html("Số lượng bán đã vượt số lượng tồn kho. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }

        return true;
    }
</script>