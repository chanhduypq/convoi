<script type="text/javascript">    
    
    /**
     * 
     * @returns {Boolean}
     */
    function validate() {        
        $('input[name="quantity[]"]').removeClass("error_goods");
        $('input[name="price_has_tax[]"]').removeClass("error_goods");
        $('input[name="price_not_tax[]"]').removeClass("error_goods");
        $('input[name="sxdv_name[]"]').removeClass("error_goods");
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
        
        tien_tam_ung=$("#tien_tam_ung").val();
        tong_tien=parseInt($('input[name="sum"]').val())+parseInt($('input[name="tax_sum"]').val());
        if(tong_tien<tien_tam_ung){
            $("div.error").html("Tổng tiền nhỏ hơn số tiền đã tạm ứng. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        
        flag=true;
        quantitys=$('input[name="quantity[]"]');
        price_has_taxs=$('input[name="price_has_tax[]"]');
        sxdv_names=$('input[name="sxdv_name[]"]');
        for(i=0;i<quantitys.length;i++){
            if($(quantitys[i]).val()==""||$(quantitys[i]).val()=="0"){
                $(quantitys[i]).addClass("error_goods");
                flag=false;
            }
        }
        for(i=0;i<price_has_taxs.length;i++){
            if($(price_has_taxs[i]).val()==""||$(price_has_taxs[i]).val()=="0"){
                $(price_has_taxs[i]).addClass("error_goods");
                $(price_has_taxs[i]).parent().next().find("input").eq(0).addClass("error_goods");
                flag=false;
            }
        }
        for(i=0;i<sxdv_names.length;i++){
            if($.trim($(sxdv_names[i]).val())==""){
                $(sxdv_names[i]).addClass("error_goods");
                flag=false;
            }
        }
        if(flag==false){
            $("div.error").html("Thông tin hàng hóa chưa được nhập đầy đủ.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
        }
        return flag;
    }
</script>