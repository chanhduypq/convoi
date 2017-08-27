<script type="text/javascript">
    
    
    /**
     * 
     * @returns {Boolean}
     */
    function validate() {
    
    
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
        if ($.trim($('#description').val()) == '') {
            $("div.error").html("Vui lòng nhập nội dung.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
//        if ($('#payment_method').val() == '') {
//            $("div.error").html("Vui lòng chọn phương thức thanh toán.").show();
//            $("html, body").animate({scrollTop: 0}, "slow");
//            return false;
//        }
        if ($('input[name="sum"]').val() == '' || $('input[name="sum"]').val() == '0') {
            $("div.error").html("Thông tin số tiền mua hàng chưa được nhập đầy đủ.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            $('input[name="sum"]').css('border','1px solid red');
            return false;
        }
        
        sum_socai=$("#sum_socai").val();
        tong_tien=parseInt($('input[name="sum"]').val());
        if($('input[name="tax_sum"]').val()!=""){
            tong_tien+=parseInt($('input[name="tax_sum"]').val());
        }      
        if(tong_tien<sum_socai){
            $("div.error").html("Tổng tiền nhỏ hơn số tiền đã thanh toán bên sổ cái. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        

        

        return true;
    }
</script>