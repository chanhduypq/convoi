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
<h1></h1>
<div class="error"></div>
<form id="create_bill" method="POST">
    <div id="mauHD">
        

        <li class="clearfix"></li>

        <!-- Div Customer info -->
        <div class="div-margin">
            <li class="cus-info cus-info16">STT</li>
            <li class="cus-auto18"><input disabled="disabled" id="bill_number" name="bill_number" type="text" class="cus-auto18-input" value="<?php echo $stt;?>"></li>
            <li class="clearfix"></li>
            
            <li class="cus-info cus-info16">&nbsp;</li>
            <li class="cus-tax18"><input placeholder="Nhập số tiền tại đây" id="sum_and_sumtax" name="sum_and_sumtax" type="text" class="cus-auto18-input numeric"></li>
            <li class="clearfix"></li>
            
            <li class="cus-info cus-info16">Ngày</li>
            <li class="cus-auto18" style="background-color: #ffffff;"><input readonly="readonly" id="created_at" name="created_at" type="text" class="cus-auto18-input" value="<?php echo $start_date; ?>"></li>
            <li class="clearfix"></li> 

            <li class="cus-info cus-info16">Nội dung</li>
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
            </li>
            <div class="clearfix"></div>
            
            
        </div>

        <li class="clearfix"></li>

        
        

        <li class="clearfix"></li>
        <div class="div-margin">                       
            <li class="buttonHDsave" id="submit"><a>Lưu</a></li>
            
        </div>
        <li class="clearfix"></li>
 

    </div>

<input type="hidden" name="socai_id" value="<?php echo $socai_id;?>"/>
<input type="hidden" id="tien_tam_ung" value="<?php echo $tien_tam_ung;?>"/>

</form>

<script type="text/javascript">
    jQuery(function($) {
        
        $('#created_at').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0
        });

        
        

    })
</script>

<?php 
$this->renderPartial('//laisuatfull/validate');
$this->renderPartial('//laisuatfull/create/event_submit');
?>