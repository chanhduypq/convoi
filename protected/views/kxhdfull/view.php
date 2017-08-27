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
<h1>&nbsp;</h1>
<div class="error"></div>
<form id="update_bill" method="POST">

    <div id="mauHD">
        <h1>
            Thông tin khách hàng  
            <?php 
            if(FunctionCommon::get_role()==Role::QUAN_LY_BAN_HANG||FunctionCommon::get_role()==Role::ADMIN){?>
            <li class="add_child two_img" id="add_customer" title="Thêm mới khách hàng">
                <a>
                    <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
                    <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-ncc-over.png">
                </a>
            </li>
            <?php
            }?>
        </h1>
        <li class="clearfix"></li>
        
        <?php 
        $this->renderPartial('//kxhdfull/update/supplier_info_html',array('invoicefull_model'=>$invoicefull_model,'payment_method'=>$payment_method,'payment_method_id'=>$invoicefull_model->payment_method_id));
        ?>       

        <li class="clearfix"></li>

        <div id="div-margin">
            <div class="div-pro1">

                <li class="all-total1" style="width: 40%;">Tổng cộng</li>
                <li class="all-total2" id="sum_sum" style="width: 30%;">
                    <input type="text" class="numeric" id="sum1" value="<?php echo $invoicefull_model->sum; ?>"/>
                    
                </li>
                <li class="all-total2" id="sum_sum_tax" style="width: 30%;">
                    <input type="text" class="numeric" id="sum2" value="<?php echo $invoicefull_model->tax_sum; ?>"/>
                    
                </li>
                <li class="clearfix"></li>

                <li class="all-total1" style="height: 48px;width: 40%;">Tổng tiền thanh toán</li>
                <li class="all-total3" style="width: 60%;">
                    <span class="p_left" id="sum_sum_and_tax"><?php echo $invoicefull_model->sum_all; ?></span> 
                    
                </li>
                <li class="clearfix"></li>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Total -->
        

        <li class="clearfix"></li>
        
        <div id="div_loading_validate" style="position: absolute;z-index: 99999;display: none;">
            <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
        </div> 
        <li class="clearfix"></li>        
        <?php $this->renderPartial('//kxhdfull/update/update_print_history',array('update_histoty_array'=>$update_histoty_array,'created_user'=>$created_user));?>
    </div>

    <input type="hidden" name="branch_id" value="<?php echo $invoicefull_model->branch_id; ?>"/>
    <input type="hidden" name="sum" value="<?php echo $invoicefull_model->sum; ?>"/>
    <input type="hidden" name="tax_sum" value="<?php echo $invoicefull_model->tax_sum; ?>"/>
    <input type="hidden" name="id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="bill_id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="reason"/>
    
</form>


<div class="clearfix"></div>

<script type="text/javascript"> 
    var global_tax_code='<?php echo $invoicefull_model->tax_code;if($invoicefull_model->tax_code_chinhanh!='') echo ' - '.$invoicefull_model->tax_code_chinhanh;?>';
    jQuery(function($) {  
        $("input,select,textarea").attr("disabled","disabled");
        
        $('#created_at').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0
        });

        
        
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
$this->renderPartial('//kxhdfull/update/validate');
$this->renderPartial('//kxhdfull/event/event_keyup');
$this->renderPartial('//kxhdfull/update/event_submit');
$this->renderPartial('//render_partial/create_customer',array('type'=>Branch::CUSTOMER)); 


?>