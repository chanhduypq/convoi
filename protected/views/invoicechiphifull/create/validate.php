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
            $("div.error").html("Mã số thuế <?php echo lcfirst (Yii::app()->params['label_for_supplier']);?> chưa chính xác. Vui lòng nhập lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($('input[name="branch_id"]').val() == ''&&$.trim($("#mst").val())=="") {//&&($.trim($("#mst").val())==""||$.trim($("#branch_full_name").val())==""||$.trim($("#branch_address").val())=="")){        
            $("div.error").html("Thông tin <?php echo lcfirst (Yii::app()->params['label_for_supplier']);?> chưa được nhập.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($.trim($('#mst').val()) != global_tax_code) {
            $("div.error").html("Mã số thuế <?php echo lcfirst (Yii::app()->params['label_for_supplier']);?> chưa chính xác. Vui lòng nhập lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($.trim($('#bill_number').val()) == "") {
            $("div.error").html("Vui lòng nhập số hóa đơn.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }  
        if (($.trim($('#bill_number').val())).length>20) {
            $("div.error").html("Số hóa đơn quá dài. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        } 
        if ($.trim($('#description').val()) == "") {
            $("div.error").html("Vui lòng nhập ghi chú hóa đơn.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }  
        if(exist_bill_input_number1($.trim($('#bill_number').val()),"")==true){
            $("div.error").html("Đã tồn tại số hóa đơn này.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($('#payment_method').val() == '') {
            $("div.error").html("Vui lòng chọn phương thức thanh toán.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($('input[name="sum"]').val() == '' || $('input[name="sum"]').val() == '0') {
            $("div.error").html("Thông tin số tiền chưa được nhập đầy đủ.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        tien_tam_ung=$("#tien_tam_ung").val();
        tong_tien=parseInt($('input[name="sum"]').val());
        if($('input[name="tax_sum"]').val()!=""){
            tong_tien+=parseInt($('input[name="tax_sum"]').val());
        }        
        if(tong_tien<tien_tam_ung){
            $("div.error").html("Tổng tiền nhỏ hơn số tiền đã tạm ứng. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }

        return true;
    }
</script>