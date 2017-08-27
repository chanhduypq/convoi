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
    $start_date = date("Y.m.d");
} elseif ($DATE_FORMAT == 'Y-m-d') {
    $DATE_FORMAT = "yy-mm-dd";
    $start_date = date("Y-m-d");
} elseif ($DATE_FORMAT == 'Y/m/d') {
    $DATE_FORMAT = "yy/mm/dd";
    $start_date = date("Y/m/d");
} elseif ($DATE_FORMAT == 'Ymd') {
    $DATE_FORMAT = "yymmdd";
    $start_date = date("Ymd");
}
?>
<div class="error_connection">
    <?php
    if(isset(Yii::app()->session['error_mysql'])&&Yii::app()->session['error_mysql']=='1'){
        echo "Đường truyền bị lỗi. Vui lòng làm lại.";
        unset(Yii::app()->session['error_mysql']);
    }
    ?>
</div>
<div class="back_button" title="Quay lại">
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/back.png" alt=""/>
</div>
<h1>Nhập hóa đơn mới</h1>
<div class="error"></div>
<form id="create_bill" method="POST">
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

        <!-- Div Customer info -->
        <div class="div-margin">
            <li class="cus-info cus-info16">Số hóa đơn</li>
            <li class="cus-tax18"><input id="bill_number" name="bill_number" type="text" class="cus-auto18-input" value=""></li>

            <li class="cus-info cus-info15">Ngày</li>
            <li class="cus-auto18" style="background-color: #ffffff;"><input readonly="readonly" id="created_at" name="created_at" type="text" class="cus-auto18-input" value="<?php echo $start_date; ?>"></li>

            <li class="cus-info cus-info15" title="Mã số thuế">MST</li>
            <li class="cus-tax18"><input id="mst" name="" type="text" class="cus-auto18-input"></li>

            <li class="clearfix"></li>
            <li class="cus-info cus-info16">Đơn vị bán hàng</li>
            <li class="cus-cty84"><input id="branch_full_name" name="" type="text" class="cus-auto18-input"></li>

            <li class="clearfix"></li>
            <li class="cus-info cus-info16">Địa chỉ</li>
            <li class="cus-cty84"><textarea id="branch_address" name="" cols="" rows="2" class="cus-cty84-input"></textarea></li>
            
            <li class="clearfix"></li>
            <li class="cus-info cus-info16">Ghi chú hóa đơn</li>
            <li class="cus-cty84"><textarea name="description" id="description" name="" cols="" rows="2" class="cus-cty84-input"></textarea></li>
            <div class="clearfix"></div>
            
            <li class="cus-info cus-info16">&nbsp;</li>
            <li style="float: left;width: 84%;">                
                <select name="payment_method" id="payment_method" disabled="disabled">
                    <option value="">--Chọn phương thức thanh toán--</option>
                    <?php
                    foreach ($payment_method as $value) {?>
                    <option<?php if($value->id==PaymentMethod::CHUA_THANH_TOAN) echo ' selected="selected"';?> value="<?php echo $value->id;?>"><?php echo $value->method;?></option>
                    <?php    
                    }
                    ?>
                </select>
                <label class="cursor">
                    <input type="checkbox" id="cb_is_monthly"/>Chi phí cố định hàng tháng
                </label>
            </li>
            <div class="clearfix"></div>
            
            
        </div>

        <li class="clearfix"></li>

        <!-- Total -->
        <div id="div-margin">
            <div class="div-pro1">

                <li class="all-total1" style="width: 40%;">Tổng cộng</li>
                <li class="all-total2" id="sum_sum" style="width: 30%;">
                    <input type="text" class="numeric" id="sum1"/>
                    
                </li>
                <li class="all-total2" id="sum_sum_tax" style="width: 30%;">
                    <input type="text" class="numeric" id="sum2"/>
                    
                </li>
                <li class="clearfix"></li>

                <li class="all-total1" style="height: 48px;width: 40%;">Tổng tiền thanh toán</li>
                <li class="all-total3" style="width: 60%;">
                    <span class="p_left" id="sum_sum_and_tax">0</span> 
                    
                </li>
                <li class="clearfix"></li>
            </div>
            <div class="clearfix"></div>
        </div>

        <li class="clearfix"></li>
        <div class="div-margin">                       
            <li class="buttonHDsave" id="submit"><a>Lưu</a></li>
            
        </div>
        <li class="clearfix"></li>
 

    </div>
    <input type="hidden" name="branch_id"/>
    <input type="hidden" name="sum"/>
    <input type="hidden" name="tax_sum"/>
    <input type="hidden" name="is_monthly" id="is_monthly"/>
    <input type="hidden" name="socai_id" value="<?php echo $socai_id;?>"/>
    <input type="hidden" id="tien_tam_ung" value="<?php echo $tien_tam_ung;?>"/>


</form>
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
$this->renderPartial('//invoicechiphifull/create/validate');
$this->renderPartial('//invoicechiphifull/event/event_keyup');
?>
<script type="text/javascript">
    
    var global_tax_code='';
    

    jQuery(function($) {
        $("#cb_is_monthly").click(function (){
            if($(this).is(':checked')){   
                $("#is_monthly").val('1');                
            }
            else{
                $("#is_monthly").val('0');                
            }
        });
        $('#created_at').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0
        });

        
//        $("#bill_number").keydown(function (e) {
//            // Allow: backspace, delete, tab, escape, enter and .
//            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
//                 // Allow: Ctrl+A, Command+A
//                (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
//                 // Allow: home, end, left, right, down, up
//                (e.keyCode >= 35 && e.keyCode <= 40)) {
//                     // let it happen, don't do anything
//                     return;
//            }
//            // Ensure that it is a number and stop the keypress
//            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
//                e.preventDefault();
//            }
//        });
        
        

    })
</script>

<?php 
$this->renderPartial('//invoicechiphifull/create/event_submit');
$this->renderPartial('//render_partial/create_customer',array('type'=>Branch::SUPPLIER));
?>
