<?php
$this->renderPartial('//render_partial/common/function_php_create_combobox_goods_goodsunit');
?>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/autocomplete/jquery.auto-complete.css" />

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/autocomplete/jquery.auto-complete.js"></script>


<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery.multiselect.filter.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery-ui.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery.multiselect.form.create.update.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.ui.datepicker-vi.min.js"></script>
<?php 
$DATE_FORMAT = Yii::app()->session['date_format'];
if ($DATE_FORMAT == 'Y.m.d') { 
    $DATE_FORMAT = "yy.mm.dd";    
} elseif ($DATE_FORMAT == 'Y-m-d') {
    $DATE_FORMAT = "yy-mm-dd";   
} elseif ($DATE_FORMAT == 'Y/m/d') {
    $DATE_FORMAT = "yy/mm/dd";    
} elseif ($DATE_FORMAT == 'Ymd') {
    $DATE_FORMAT = "yymmdd";    
}
?>
<div class="back_button" title="Quay lại">
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/back.png" alt=""/>
</div>
<h1>Thông tin tờ khai</h1>
<div class="error"></div>
<form id="update_bill" method="POST">

    <div id="mauHD">
        <h1>
            Thông tin <?php echo lcfirst (Yii::app()->params['label_for_supplier']);?>  
            <li class="add_child two_img" id="add_customer" title="Thêm mới <?php echo lcfirst (Yii::app()->params['label_for_supplier']);?>">
                <a>
                    <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
                    <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-ncc-over.png">
                </a>
            </li>
        </h1>
        <li class="clearfix"></li>
        
        <?php 
        $this->renderPartial('//internationalinput/update/supplier_info_html',array('invoicefull_model'=>$invoicefull_model));
        ?>

        

        <h1>
            Thông tin hàng hóa nhập
            <li id="add_goods" class="add_child two_img" title="Thêm mới hàng hóa">
                <a>
                    <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
                    <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-hang-hoa-over.png">
                </a>
            </li>
        </h1>
        <li class="clearfix"></li>

        <?php 
        $this->renderPartial('//internationalinput/update/detail_bill_input_output',array('bill_details'=>$bill_details,'goods'=>$goods));
        ?>


        <!-- add new -->
        <li id="add_new_hanghoa" class="add-new" style="cursor: pointer;color: blue;">+ thêm mới</li>


        <!-- Total -->
        <?php 
        $this->renderPartial('//render_partial/common/detail_bill_footer_input_output_for_to_khai',array('invoicefull_model'=>$invoicefull_model,'payment_method'=>$payment_method));
        ?>

        <li class="clearfix"></li>
        <div class="div-margin">                      
            <li class="buttonHDsave" id="submit"><a>Lưu</a></li>
        </div>
        <div id="div_loading_validate" style="position: absolute;z-index: 99999;display: none;">
            <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
        </div> 
        <li class="clearfix"></li>        
        <?php $this->renderPartial('//internationalinput/update/update_print_history',array('update_histoty_array'=>$update_histoty_array,'created_user'=>$created_user));?>
    </div>

    <input type="hidden" name="branch_id" value="<?php echo $invoicefull_model->branch_id; ?>"/>
    <input type="hidden" name="sum" value="<?php echo str_replace(".", "", $invoicefull_model->sum); ?>"/>
    <input type="hidden" name="tax_sum" value="<?php echo str_replace(".", "", $invoicefull_model->tax_sum); ?>"/>
    <input type="hidden" name="id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="bill_id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="reason"/>
    <input type="hidden" id="sum_socai_payment_method3" value="<?php echo $sum_socai_payment_method3;?>"/>
    <input type="hidden" id="sum_socai_payment_method4" value="<?php echo $sum_socai_payment_method4;?>"/>
    <input type="hidden" id="sum_socai_payment_method5" value="<?php echo $sum_socai_payment_method5;?>"/>
    
</form>


<div class="clearfix"></div>

<script type="text/javascript"> 
    var global_tax_code='<?php echo $invoicefull_model->tax_code;?>';
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
        
        $('#created_at').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0
        });

        
        quantities = $("input[name='quantity[]']");
        for (i = 0; i < quantities.length; i++) {
            if($(quantities[i]).parent().parent().find(".tax_ttdb").eq(0).length>0){
                tax_ttdb = $(quantities[i]).parent().parent().find(".tax_ttdb").eq(0).val();
            }
            else{
                tax_ttdb=0;
            }
            if($(quantities[i]).parent().parent().find(".tax_nk").eq(0).length>0){
                tax_nk = $(quantities[i]).parent().parent().find(".tax_nk").eq(0).val();
            }
            else{
                tax_nk=0;
            }           
           
            tax = $(quantities[i]).parent().parent().find(".tax_vat").eq(0).val();           
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
            sum_node = $(this).parent().parent().find(".pro-qtemoney-th").eq(0).find("input").eq(0);
            if($(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_ttdb").eq(0).length>0){
                tax_ttdb_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_ttdb").eq(0);            
            }
            else{
                tax_ttdb_sum_node=null;
            }
            if($(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_nk").eq(0).length>0){
                tax_nk_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_nk").eq(0);            
            }
            else{
                tax_nk_sum_node=null;
            }
            
            
            tax_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_vat").eq(0);
            price_has_tax_node = $(quantities[i]).parent().next().find("input").eq(0);
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
            if(Yii::app()->session['calculate_way']=='1'){
                echo "editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node, tax);";
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
$this->renderPartial('//internationalinput/function_js_bill_input_output');
$this->renderPartial('//internationalinput/update/validate');
$this->renderPartial('//internationalinput/event/event_click');
$this->renderPartial('//internationalinput/event/event_select_change');
$this->renderPartial('//internationalinput/event/event_keyup');
$this->renderPartial('//internationalinput/update/event_submit');
$this->renderPartial('//internationalinput/create_international',array('type'=>Branch::SUPPLIER)); 
$this->renderPartial('//internationalinput/create_goods');
$this->renderPartial('//internationalinput/update/invoice_history_detail');  
?>