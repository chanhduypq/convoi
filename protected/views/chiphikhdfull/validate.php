<script type="text/javascript">    
    
    /**
     * 
     * @returns {Boolean}
     */
    function validate() {
        $("div.error").hide();
                $("button[type='button']").css("border","none");
   $('input[name="sum_and_sumtax"]').css('border','none');
   $('#created_at').css('border','none');
   $('#description').css('border','none');
        
        if ($('input[name="sum_and_sumtax"]').val() == '' || $('input[name="sum_and_sumtax"]').val() == '0') {
            $("div.error").html("Số tiền chưa được nhập.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            $('input[name="sum_and_sumtax"]').css('border','1px solid red');
            return false;
        }
        if ($('#created_at').val() == '') {
            $("div.error").html("Vui lòng nhập ngày.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($.trim($('#description').val()) == '') {
            $("div.error").html("Vui lòng nhập nội dung.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        
        tien_tam_ung=$("#tien_tam_ung").val();
        temp=$('input[name="sum_and_sumtax"]').val();
        if (temp.indexOf(".") != -1) {
            temp = temp.split(".").join("");
        }
        tong_tien=parseInt(temp);
        if(tong_tien<tien_tam_ung){
            $("div.error").html("Số tiền nhỏ hơn số tiền đã tạm ứng. Vui lòng nhập lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        
        sum_socai=$("#sum_socai").val();
        if(tong_tien<sum_socai){
            $("div.error").html("Tổng tiền nhỏ hơn số tiền đã thanh toán bên sổ cái. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }

        return true;
    }
</script>