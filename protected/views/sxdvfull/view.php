<h1>Thông tin hóa đơn</h1>
<div class="error"></div>
    <div id="mauHD">
        <h1>
            Thông tin khách hàng      
            
        </h1>
        <li class="clearfix"></li>

        <!-- Div Customer info -->
        <?php 
        $this->renderPartial('//sxdvfull/update/customer_info_html',array('invoicefull_model'=>$invoicefull_model,'payment_method'=>$payment_method,'payment_method_id'=>$invoicefull_model->payment_method_id));
        ?>

        <h1>
            Thông tin hàng hóa xuất
            
        </h1>
        <li class="clearfix"></li>
        <?php 
        $this->renderPartial('//sxdvfull/update/detail_bill_input_output',array('bill_details'=>$bill_details));
        ?>
        
        <?php 
        $this->renderPartial('//render_partial/common/detail_bill_footer_input_output1',array('invoicefull_model'=>$invoicefull_model));
        ?>

        <li class="clearfix"></li>
       
        <?php $this->renderPartial('//sxdvfull/update/update_print_history',array('histoty_array'=>$histoty_array,'created_user'=>$created_user));?>
    </div>
<div class="clearfix"></div>
<script type="text/javascript"> 
    var global_tax_code='<?php echo $invoicefull_model->tax_code;?>';
    jQuery(function($) {   
        $("input,select,textarea").attr("disabled","disabled");
        $(".pronametitle img").remove();
        quantities = $("input[name='quantity[]']");
        for (i = 0; i < quantities.length; i++) {
            tax = $(quantities[i]).parent().parent().find(".tax").eq(0).val();
            if(tax=='/'){
                tax=0;
            }
            tax = parseInt(tax);
            sum_node = $(quantities[i]).parent().parent().find(".pro-money").eq(0);
            tax_sum_node = $(quantities[i]).parent().parent().find(".pro-money").eq(1);
            price_has_tax_node = $(quantities[i]).parent().next().find("input").eq(0);
            
            //
            price = $(price_has_tax_node).val();
            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            price_not_tax_double=temp.toFixed(2);            
            //
            setTienHangAndTax($(quantities[i]).val(), price_not_tax_double, tax, sum_node, tax_sum_node);    
            <?php
            if(Yii::app()->session['calculate_way']=='1'){
                echo "editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_sum_node, tax);";
            }
            ?>   
            

        }        
    });
</script>

<?php 
$this->renderPartial('//render_partial/common/function_js_bill_input_output');
$this->renderPartial('//sxdvfull/update/invoice_history_detail');     
?>