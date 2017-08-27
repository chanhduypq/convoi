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

        <li class="all-total1" style="height: 48px;width: 40%">Tổng tiền thanh toán</li>
        <li class="all-total3" style="width: 60%">
            <span class="p_left" id="sum_sum_and_tax"><?php echo $invoicefull_model->sum_all; ?></span>  
            <div style="float: left;display: table-cell;vertical-align: middle;padding-left: 0;padding-right: 0;padding-top: 5px;">
                <img id="refresh" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/refresh.png">                        
            </div>
        </li>
        <li class="clearfix"></li>
    </div>
    <div class="clearfix"></div>
</div>
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
            }
            sum = numberWithCommas(sum);
            $(this).parent().prev().html(sum);
       });
    });
</script>