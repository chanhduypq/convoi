<div id="div_loading_validate" style="position: absolute;z-index: 99999;display: none;">
    <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
</div> 
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
//        if ($('#payment_method').val() == '') {
//            $("div.error").html("Vui lòng chọn phương thức thanh toán.").show();
//            $("html, body").animate({scrollTop: 0}, "slow");
//            return false;
//        }
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
        
        goods_ids = $('select[name="goods_id[]"]').map(function() {
            return $(this).val();
        }).get();
        
        quantities = $('input[name="quantity[]"]').map(function() {
            return $(this).val();
        }).get();
        error_quantity=is_error_quantity(goods_ids,quantities,$('input[name="quantity[]"]'),$('input[name="bill_id"]').val());
        
        
        if(error_quantity==true){
            $("div.error").html("Số lượng bán đã vượt số lượng tồn kho. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        
        sum_socai=$("#sum_socai").val();
        tong_tien=parseInt($('input[name="sum"]').val())+parseInt($('input[name="tax_sum"]').val());
        if(tong_tien<sum_socai){
            $("div.error").html("Tổng tiền nhỏ hơn số tiền đã thanh toán bên sổ cái. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }

        return true;
    }
    
    function is_error_quantity(goods_ids,quantities,quantity_node_array,bill_id){
        $("#div_loading_validate").show();  
        result_bool=false;
        $.ajax({ 
            async: false,
            cache: false,
            type: "POST",             
            data: {goods_ids: goods_ids,quantities:quantities,bill_id:bill_id},
            url: '<?php echo $this->createUrl("/ajax/checkerrorquantity"); ?>',
            success: function(data, textStatus, jqXHR) {                  
                $("#div_loading_validate").hide();    
                if($.trim(data)!=""&&$.trim(data)!="[]"){                      
                    data=$.parseJSON(data);
                    for (i = 0; i < data.length; i++) {
                        if(data[i].error_quantity=='1'){
                            result_bool=true;
                            $(quantity_node_array[i]).addClass('error_goods');
                        }
                        

                    }
                }                
            }

        });
        return result_bool;
    }
</script>