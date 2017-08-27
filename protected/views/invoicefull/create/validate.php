<script type="text/javascript">    
    
    /**
     * 
     * @returns {Boolean}
     */
    function validate() {        
        $('input[name="quantity[]"]').removeClass("error_goods");
        $("div.error").hide();
                $("button[type='button']").css("border","none");
//        $("button[type='button']").css("background-color","initial");
        if ($('input[name="branch_id"]').val() == ''&&$.trim($("#mst").val())!="") {//&&($.trim($("#mst").val())==""||$.trim($("#branch_full_name").val())==""||$.trim($("#branch_address").val())=="")){        
            $("div.error").html("Mã số thuế khách hàng chưa chính xác. Vui lòng nhập lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($('input[name="branch_id"]').val() == ''&&$.trim($("#mst").val())=="") {//&&($.trim($("#mst").val())==""||$.trim($("#branch_full_name").val())==""||$.trim($("#branch_address").val())=="")){        
            $("div.error").html("Thông tin khách hàng chưa được nhập.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($.trim($('#mst').val()) != global_tax_code) {
            $("div.error").html("Mã số thuế khách hàng chưa chính xác. Vui lòng nhập lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($('#payment_method').val() == '') {
            $("div.error").html("Vui lòng chọn phương thức thanh toán.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
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
        tien_tam_ung=$("#tien_tam_ung").val();
        tong_tien=parseInt($('input[name="sum"]').val())+parseInt($('input[name="tax_sum"]').val());
        if(tong_tien<tien_tam_ung){
            $("div.error").html("Tổng tiền nhỏ hơn số tiền đã tạm ứng. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }

        return true;
    }
</script>