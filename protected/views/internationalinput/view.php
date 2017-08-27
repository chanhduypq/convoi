<?php
$this->renderPartial('//render_partial/common/function_php_create_combobox_goods_goodsunit');
?>

<h1>Thông tin tờ khai</h1>
<div class="error"></div>
    <div id="mauHD">
        <h1>
            Thông tin <?php echo lcfirst (Yii::app()->params['label_for_supplier']);?>  
            
        </h1>
        <li class="clearfix"></li>
        
        <?php 
        $this->renderPartial('//internationalinput/update/supplier_info_html',array('invoicefull_model'=>$invoicefull_model));
        ?>
        <h1>
            Thông tin hàng hóa nhập           
        </h1>
        <li class="clearfix"></li>
        <?php 
        $this->renderPartial('//render_partial/common/detail_bill_input_output',array('bill_details'=>$bill_details,'goods'=>$goods));
        $this->renderPartial('//render_partial/common/detail_bill_footer_input_output_for_to_khai',array('invoicefull_model'=>$invoicefull_model,'payment_method'=>$payment_method));
        ?>

        <li class="clearfix"></li>            
        <?php $this->renderPartial('//internationalinput/update/update_print_history',array('update_histoty_array'=>$update_histoty_array,'created_user'=>$created_user));?>
    </div>    



<div class="clearfix"></div>

<script type="text/javascript"> 
    var global_tax_code='<?php echo $invoicefull_model->tax_code;?>';
    jQuery(function($) {       
        $("input,select,textarea").attr("disabled","disabled");
        $(".pronametitle img").remove();
        quantities = $("input[name='quantity[]']");
        for (i = 0; i < quantities.length; i++) {
            tax_ttdb = $(quantities[i]).parent().parent().find(".tax").eq(0).val();
            tax_nk = $(quantities[i]).parent().parent().find(".tax").eq(1).val();
            tax = $(quantities[i]).parent().parent().find(".tax").eq(2).val();           
            if(tax=='/'){
                tax=0;
            }
            tax = parseInt(tax);
            if(tax_ttdb=='/'){
                tax_ttdb=0;
            }
            tax_ttdb = parseInt(tax_ttdb);
            if(tax_nk=='/'){
                tax_nk=0;
            }
            tax_nk = parseInt(tax_nk);
            sum_node = $(this).parent().parent().find(".pro-qtemoney-th").eq(0);
            tax_ttdb_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.li-right").eq(0);            
            tax_nk_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.li-right").eq(1);            
            tax_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.li-right").eq(2);
            price_has_tax_node = $(quantities[i]).parent().next().next().find("input").eq(0);
            //
            price = $(price_has_tax_node).val();
            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            price_not_tax_double=temp.toFixed(2);
            //            
            setTienHangAndTax($(quantities[i]).val(), price_not_tax_double, tax_ttdb,tax_nk,tax, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node);
            <?php
            //nếu admin đang setting kiểu làm tròn
            if(Yii::app()->session['calculate_way']=='1'){
                echo "editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node, tax);";
            }
            ?>  
        }
    });
</script>
<?php 
$this->renderPartial('//render_partial/common/function_js_bill_input_output');
$this->renderPartial('//internationalinput/update/invoice_history_detail');  
?>
