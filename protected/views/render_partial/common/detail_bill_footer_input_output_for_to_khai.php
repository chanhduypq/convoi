<div id="div-margin">
    <div class="div-pro1">
        <li class="all-total1" style="width: 40%">Tổng cộng</li>
        <li class="all-total2" id="sum_sum" style="width: 30%">
            <label><?php echo $invoicefull_model->sum; ?></label>
            <div style="float: right;">
                <img class="add" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/add_sum.png">
                <img class="minus" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/minus_sum.png">
            </div>
        </li>
        <li class="all-total2" id="sum_sum_tax" style="width: 30%">
            <label><?php echo $invoicefull_model->tax_sum; ?></label>
            <div style="float: right;">
                <img class="add" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/add_sum.png">
                <img class="minus" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/minus_sum.png">
            </div>
        </li>
        <li class="clearfix"></li>

        <li class="all-total1" style="height: 48px;width: 40%">Tổng giá trị trên tờ khai</li>
        <li class="all-total3" style="width: 60%">
            <span class="p_left" id="sum_sum_and_tax"><?php echo $invoicefull_model->sum_all; ?></span>  
            <div style="float: left;display: table-cell;vertical-align: middle;padding-left: 0;padding-right: 0;padding-top: 5px;">
                <img id="refresh" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/refresh.png">                        
            </div>
        </li>
        <li class="clearfix"></li>
        
        <li class="all-total1" style="height: 48px;width: 40%;">Giá trị thanh toán thực tế + thuế</li>
        <li class="all-total3" style="width: 60%;">
            <span class="p_left" id="gia_tri_thanh_toan_qua_ngan_hang">
                <?php if($invoicefull_model->chi_phi_ngan_hang_vnd=='') $invoicefull_model->chi_phi_ngan_hang_vnd='0'; echo number_format($invoicefull_model->gia_tri_hang_hoa_vnd+$invoicefull_model->chi_phi_ngan_hang_vnd+str_replace(".", "", $invoicefull_model->tax_sum), 0, ",", ".");?>
            </span>                   
        </li>
        <li class="clearfix"></li>
    </div>
    <div class="clearfix"></div>
</div>
<br><br>
<li class="clearfix"></li>
<li class="cus-info cus-info16">Giá trị hàng hóa (USD)</li>
<li class="cus-cty84" style="width: 20%;">
    <input value="<?php echo $invoicefull_model->gia_tri_hang_hoa_usd; ?>" id="gia_tri_hang_hoa_usd" name="gia_tri_hang_hoa_usd" type="text" class="cus-auto18-input numeric">                
</li>
<select style="margin-left:20px;" name="payment_method_id1" id="payment_method_id1">
    <option value="">--Chọn phương thức thanh toán--</option>
    <?php
    foreach ($payment_method as $value) {
        if($value->id==$invoicefull_model->payment_method_id1){
            $selected=' selected="selected"';
        }
        else{
            $selected='';
        }
        ?>
    <option<?php echo $selected;?> value="<?php echo $value->id;?>"><?php echo $value->method;?></option>
    <?php    
    }
    ?>
</select>
<div class="clearfix"></div>

<li class="clearfix"></li>
<li class="cus-info cus-info16">Giá trị khấu trừ (USD)</li>
<li class="cus-cty84" style="width: 20%;"><input value="<?php echo $invoicefull_model->gia_tri_khau_tru_usd; ?>" id="gia_tri_khau_tru_usd" name="gia_tri_khau_tru_usd" type="text" class="cus-auto18-input numeric"></li>
<select style="margin-left:20px;" name="payment_method_id2" id="payment_method_id2">
    <option value="">--Chọn phương thức thanh toán--</option>
    <?php
    foreach ($payment_method as $value) {
        if($value->id==$invoicefull_model->payment_method_id2){
            $selected=' selected="selected"';
        }
        else{
            $selected='';
        }
        ?>
    <option<?php echo $selected;?> value="<?php echo $value->id;?>"><?php echo $value->method;?></option>
    <?php    
    }
    ?>
</select>
<div class="clearfix"></div>

<li class="clearfix"></li>
<li class="cus-info cus-info16">Giá trị hàng hóa (VND)</li>
<li class="cus-cty84" style="width: 20%;"><input value="<?php echo $invoicefull_model->gia_tri_hang_hoa_vnd; ?>" id="gia_tri_hang_hoa_vnd" name="gia_tri_hang_hoa_vnd" type="text" class="cus-auto18-input numeric"></li>
<select style="margin-left:20px;" name="payment_method_id3" id="payment_method_id3" disabled="disabled">
    <option value="">--Chọn phương thức thanh toán--</option>
    <?php
    foreach ($payment_method as $value) {
        if($value->id==$invoicefull_model->payment_method_id3){
            $selected=' selected="selected"';
        }
        else{
            $selected='';
        }
        ?>
    <option<?php echo $selected;?> value="<?php echo $value->id;?>"><?php echo $value->method;?></option>
    <?php    
    }
    ?>
</select>
<div class="clearfix"></div>

<li class="clearfix"></li>
<li class="cus-info cus-info16">Chi phí ngân hàng (VND)</li>
<li class="cus-cty84" style="width: 20%;"><input value="<?php echo $invoicefull_model->chi_phi_ngan_hang_vnd; ?>" id="chi_phi_ngan_hang_vnd" name="chi_phi_ngan_hang_vnd" type="text" class="cus-auto18-input numeric"></li>
<select style="margin-left:20px;" name="payment_method_id4" id="payment_method_id4" disabled="disabled">
    <option value="">--Chọn phương thức thanh toán--</option>
    <?php
    foreach ($payment_method as $value) {
        if($value->id==$invoicefull_model->payment_method_id4){
            $selected=' selected="selected"';
        }
        else{
            $selected='';
        }
        ?>
    <option<?php echo $selected;?> value="<?php echo $value->id;?>"><?php echo $value->method;?></option>
    <?php    
    }
    ?>
</select>
<div class="clearfix"></div>

<li class="clearfix"></li>
<li class="cus-info cus-info16">Tiền thuế (VND)</li>
<li class="cus-cty84" style="width: 20%;"><input readonly="readonly" value="<?php echo $invoicefull_model->tien_thue_vnd; ?>" id="tien_thue_vnd" name="tien_thue_vnd" type="text" class="cus-auto18-input numeric"></li>
<select style="margin-left:20px;" name="payment_method_id5" id="payment_method_id5" disabled="disabled">
    <option value="">--Chọn phương thức thanh toán--</option>
    <?php
    foreach ($payment_method as $value) {
        if($value->id==$invoicefull_model->payment_method_id5){
            $selected=' selected="selected"';
        }
        else{
            $selected='';
        }
        ?>
    <option<?php echo $selected;?> value="<?php echo $value->id;?>"><?php echo $value->method;?></option>
    <?php    
    }
    ?>
</select>
<div class="clearfix"></div>
<script type="text/javascript">
    /**
     * 
     * 2 biến init_sum, init_tax_sum để nhớ trạng thái mới nhất lúc user chưa click 2 button cộng/trừ tại 2 ô tổng/tổng thuế
     */
    var init_sum=<?php echo str_replace(".", "", $invoicefull_model->sum) ; ?>;
    var init_tax_sum=<?php echo str_replace(".", "", $invoicefull_model->tax_sum); ?>;

    jQuery(function ($){
        $("#refresh").click(function (){
            if(init_sum==''){
                init_sum=0;
            }
            if(init_tax_sum==''){
                init_tax_sum=0;
            }
            temp=parseInt(init_sum)+parseInt(init_tax_sum);
            $("#sum_sum label").html(numberWithCommas(init_sum));
            $("#sum_sum_tax label").html(numberWithCommas(init_tax_sum));
            $("#sum_sum_and_tax").html(numberWithCommas(temp));
            $("#tien_thue_vnd").val(init_tax_sum);
        });
       $("#sum_sum .add,#sum_sum_tax .add").click(function (){
            sum_sum_and_tax=$("#sum_sum_and_tax").html();
            if (sum_sum_and_tax.indexOf(".") != -1) {
                sum_sum_and_tax = sum_sum_and_tax.split(".").join("");
            }
            sum_sum_and_tax = parseInt(sum_sum_and_tax);
            sum_sum_and_tax++;
            sum_sum_and_tax = numberWithCommas(sum_sum_and_tax);
            $("#sum_sum_and_tax").html(sum_sum_and_tax);
            //
            sum=$(this).parent().prev().html();
            if (sum.indexOf(".") != -1) {
                sum = sum.split(".").join("");
            }
            sum = parseInt(sum);
            sum++;
            if($(this).parent().parent().attr("id")=='sum_sum'){
                $('input[name="sum"]').val(sum);
            }
            else{
                $('input[name="tax_sum"]').val(sum);
                $("#tien_thue_vnd").val(sum);
            }
            sum = numberWithCommas(sum);
            $(this).parent().prev().html(sum);
       });
       $("#sum_sum .minus,#sum_sum_tax .minus").click(function (){
            sum_sum_and_tax=$("#sum_sum_and_tax").html();
            if (sum_sum_and_tax.indexOf(".") != -1) {
                sum_sum_and_tax = sum_sum_and_tax.split(".").join("");
            }
            sum_sum_and_tax = parseInt(sum_sum_and_tax);
            sum_sum_and_tax--;
            sum_sum_and_tax = numberWithCommas(sum_sum_and_tax);
            $("#sum_sum_and_tax").html(sum_sum_and_tax);
            //
            sum=$(this).parent().prev().html();
            if (sum.indexOf(".") != -1) {
                sum = sum.split(".").join("");
            }
            sum = parseInt(sum);
            sum--;
            if($(this).parent().parent().attr("id")=='sum_sum'){
                $('input[name="sum"]').val(sum);
            }
            else{
                $('input[name="tax_sum"]').val(sum);
                $("#tien_thue_vnd").val(sum);
            }
            sum = numberWithCommas(sum);
            $(this).parent().prev().html(sum);
       });
    });
</script>