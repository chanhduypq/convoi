<script type="text/javascript">    
    
    /**
     * 
     * @returns {Boolean}
     */
    function validate() {
        $("div.error").hide();
        $('#gia_tri_hang_hoa_usd').removeClass("error_goods");
        $('#gia_tri_hang_hoa_vnd').removeClass("error_goods");
        $("button[type='button']").css("border","none");
        if ($('input[name="branch_id"]').val() == ''){
            $("div.error").html("Thông tin <?php echo lcfirst (Yii::app()->params['label_for_supplier']);?> chưa được nhập.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
//        $("button[type='button']").css("background-color","initial");
        if ($('input[name="branch_id"]').val() == ''&&$.trim($("#mst").val())!="") {//&&($.trim($("#mst").val())==""||$.trim($("#branch_full_name").val())==""||$.trim($("#branch_address").val())=="")){        
            $("div.error").html("Mã số thuế <?php echo lcfirst (Yii::app()->params['label_for_supplier']);?> chưa chính xác. Vui lòng nhập lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($.trim($('#mst').val()) != global_tax_code) {
            $("div.error").html("Mã số thuế <?php echo lcfirst (Yii::app()->params['label_for_supplier']);?> chưa chính xác. Vui lòng nhập lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($.trim($('#bill_number').val()) == "") {
            $("div.error").html("Vui lòng nhập số tờ khai.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }     
        if (($.trim($('#bill_number').val())).length>20) {
            $("div.error").html("Số tờ khai không được vượt quá 20 chữ số. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }   
        if ($.trim($('#description').val()) == "") {
            $("div.error").html("Vui lòng nhập ghi chú hóa đơn.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }   
        if(exist_bill_input_number($.trim($('#bill_number').val()),"")==true){
            $("div.error").html("Đã tồn tại số tờ khai này.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($('#gia_tri_hang_hoa_usd').val() == '' || $('#gia_tri_hang_hoa_usd').val() == '0') { 
            $('#gia_tri_hang_hoa_usd').addClass("error_goods");
            $("div.error").html("Giá trị hàng hóa (USD) chưa được nhập.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($('#gia_tri_hang_hoa_vnd').val() == '' || $('#gia_tri_hang_hoa_vnd').val() == '0') {  
            $('#gia_tri_hang_hoa_vnd').addClass("error_goods");
            $("div.error").html("Giá trị hàng hóa (VND) chưa được nhập.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ($('input[name="sum"]').val() == '' || $('input[name="sum"]').val() == '0') {
            if(confirm("Bạn đang nhập hàng với số hợp đồng "+$.trim($('#bill_number').val()))){
                return true;
            }
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
        
        tien_tam_ung=$("#gia_tri_hang_hoa").val();
        temp=$('input[name="gia_tri_hang_hoa_vnd"]').val();
        if (temp.indexOf(".") != -1) {
            temp = temp.split(".").join("");
        }
        tong_tien=parseInt(temp);
        if(tong_tien<tien_tam_ung){
            $("div.error").html("Giá trị hàng hóa (VND) nhỏ hơn số tiền đã tạm ứng. Vui lòng nhập lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        
        tien_tam_ung=$("#chi_phi_ngan_hang").val();
        temp=$('input[name="chi_phi_ngan_hang_vnd"]').val();
        if(temp==''){
            temp='0';
        }
        if (temp.indexOf(".") != -1) {
            temp = temp.split(".").join("");
        }
        tong_tien=parseInt(temp);
        if(tong_tien<tien_tam_ung){
            $("div.error").html("Chi phí ngân hàng (VND) nhỏ hơn số tiền đã tạm ứng. Vui lòng nhập lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        
        tien_tam_ung=$("#tien_thue").val();        
        temp=$('input[name="tien_thue_vnd"]').val();
        if(temp==''){
            temp='0';
        }
        if (temp.indexOf(".") != -1) {
            temp = temp.split(".").join("");
        }
        tong_tien=parseInt(temp);
        if(tong_tien<tien_tam_ung){
            $("div.error").html("Tiền thuế (VND) nhỏ hơn số tiền đã tạm ứng. Vui lòng kiểm tra lại.").show();
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }

        return true;
    }
</script>