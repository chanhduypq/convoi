<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/autocomplete/jquery.auto-complete.css" />

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/autocomplete/jquery.auto-complete.js"></script>


<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery.multiselect.filter.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery-ui.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery.multiselect.form.create.update.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.ui.datepicker-vi.min.js"></script>
<div class="back_button" title="Quay lại">
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/back.png" alt=""/>
</div>
<h1>Thông tin hóa đơn</h1>
<div class="error"></div>
<form id="update_bill" method="POST">

    <div id="mauHD">
        <h1>
            Thông tin khách hàng      
            <li class="add_child two_img" id="add_customer" title="Thêm mới khách hàng">
                <a>
                    <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
                    <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-khach-hang-over.png">
                </a>
            </li>
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
        <!-- add new -->
        <li id="add_new_hanghoa" class="add-new" style="cursor: pointer;color: blue;">+ thêm mới</li>
        <!-- Total -->
        <?php 
        $this->renderPartial('//render_partial/common/detail_bill_footer_input_output',array('invoicefull_model'=>$invoicefull_model));
        ?>

        <li class="clearfix"></li>
        <div class="div-margin">
            <li class="buttonHD1" id="print_bill2">
                <a>Hóa đơn liên 2</a>                
            </li>
            <li class="buttonHD1" id="print_bill1"><a>Hóa đơn liên 1</a></li>            
            <li class="buttonHDsave" id="submit"><a>Lưu</a></li>
        </div>
        <li class="clearfix"></li>        
        <?php $this->renderPartial('//sxdvfull/update/update_print_history',array('histoty_array'=>$histoty_array,'created_user'=>$created_user));?>
    </div>

    <input type="hidden" name="branch_id" value="<?php echo $invoicefull_model->branch_id; ?>"/>
    <input type="hidden" name="sum" value="<?php echo str_replace(".", "", $invoicefull_model->sum); ?>"/>
    <input type="hidden" name="tax_sum" value="<?php echo str_replace(".", "", $invoicefull_model->tax_sum); ?>"/>
    <input type="hidden" name="id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="bill_id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="reason"/>
    <input type="hidden" id="edit_lien1_lien2"/>
    <input type="hidden" name="print" id="print"/>
    <input type="hidden" id="sum_socai" value="<?php echo $sum_socai;?>"/>
</form>


<div class="clearfix"></div>

<script type="text/javascript"> 
    var global_tax_code='<?php echo $invoicefull_model->tax_code;if($invoicefull_model->tax_code_chinhanh!='') echo ' - '.$invoicefull_model->tax_code_chinhanh;?>';
    jQuery(function($) {   
        $("select.goods").multiselect({
            show: {effect: "slide", duration: 500},
            hide: {effect: "slide", duration: 500},
            noneSelectedText: "---Chọn hàng hóa---",
            selectedText: "# Hàng hóa được chọn",
                        multiple:false
        }).multiselectfilter();
        goods_array=$("select.goods");
        for(i=0;i<goods_array.length;i++){
            $(goods_array[i]).next().find("span").eq(1).html($(goods_array[i]).next().next().val());
        }
        

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
$array=array();
$array[]=array(
            'selector'=>'mst',
            'controller_action_name'=>'/ajax/getbranchtaxcode',
            'function_on_select'=>"setFullName($('#mst').val(),$('#branch_full_name'),$('#branch_address'),$('input[name=\"branch_id\"]'));",
);
$array[]=array(
            'selector'=>'branch_full_name',
            'controller_action_name'=>'/ajax/getbranchfullname',
            'function_on_select'=>"setTaxCode($('#branch_full_name').val(),$('#mst'),$('#branch_address'),$('input[name=\"branch_id\"]'));",
);
$this->renderPartial('//render_partial/common/autocomplete',array('array'=>$array));
$this->renderPartial('//render_partial/common/function_js_bill_input_output');
$this->renderPartial('//sxdvfull/update/validate');
$this->renderPartial('//sxdvfull/event/event_click');
$this->renderPartial('//sxdvfull/event/event_keyup');
$this->renderPartial('//sxdvfull/update/event_submit',
                       array(
                            'bill_number'=>$invoicefull_model->bill_number,
                            'count_print_lien1_histoty_date'=>$count_print_lien1_histoty_date,
                            'count_print_lien2_histoty_date'=>$count_print_lien2_histoty_date
                            )
        );
$this->renderPartial('//render_partial/create_customer',array('type'=>Branch::CUSTOMER));
$this->renderPartial('//sxdvfull/update/invoice_history_detail');     
$this->renderPartial('//render_partial/common/loading_print'); 
?>