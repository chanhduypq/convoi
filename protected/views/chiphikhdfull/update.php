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
<h1></h1>
<div class="error"></div>
<form id="update_bill" method="POST">

    <div id="mauHD">

        <li class="clearfix"></li>
        <!-- Div Customer info -->
        <div class="div-margin">
            
            <li class="cus-info cus-info16">STT</li>
            <li class="cus-auto18"><input disabled="disabled" id="bill_number" name="bill_number" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->stt;?>"></li>
            <li class="clearfix"></li>
            
            <li class="cus-info cus-info16">&nbsp;</li>
            <li class="cus-tax18"><input placeholder="Nhập số tiền tại đây" id="sum_and_sumtax" name="sum_and_sumtax" type="text" class="cus-auto18-input numeric" value="<?php echo $invoicefull_model->sum_and_sumtax; ?>"></li>
            <li class="clearfix"></li>
            
            <li class="cus-info cus-info16">Ngày</li>
            <li class="cus-auto18" style="background-color: #ffffff;"><input readonly="readonly" id="created_at" name="created_at" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->created_at; ?>"></li>
            <li class="clearfix"></li> 

            <li class="cus-info cus-info16">Nội dung</li>
            <li class="cus-cty84"><textarea name="description" id="description" name="" cols="" rows="2" class="cus-cty84-input"><?php echo $invoicefull_model->description; ?></textarea></li>
            <div class="clearfix"></div>

            <li class="cus-info cus-info16">&nbsp;</li>
            <li style="float: left;width: 84%;">                
<!--                <select name="payment_method" style="width: 200px;">
                    <option value="<?php // echo LaiSuat::TIEN_MAT;?>"<?php // if(LaiSuat::TIEN_MAT==$invoicefull_model->payment_method_id) echo ' selected="selected"'; ?>>Tiền mặt</option>
                    <option value="<?php // echo LaiSuat::CHUYEN_KHOAN_ACB;?>"<?php // if(LaiSuat::CHUYEN_KHOAN_ACB==$invoicefull_model->payment_method_id) echo ' selected="selected"'; ?>>Chuyển khoản ACB</option>
                    <option value="<?php // echo LaiSuat::CHUYEN_KHOAN_VIETCOMBANK;?>"<?php // if(LaiSuat::CHUYEN_KHOAN_VIETCOMBANK==$invoicefull_model->payment_method_id) echo ' selected="selected"'; ?>>Chuyển khoản Vietcombank</option>
                    <option value="<?php // echo LaiSuat::OTHER;?>"<?php // if(LaiSuat::OTHER==$invoicefull_model->payment_method_id) echo ' selected="selected"'; ?>>Khác</option>
                </select>-->
                <select name="payment_method" id="payment_method" disabled="disabled">
                    <option value="">--Chọn phương thức thanh toán--</option>
                    <?php
                    foreach ($payment_method as $value) {
                        $selected='';
                        if($value->id==$invoicefull_model->payment_method_id){
                            $selected=' selected="selected"';
                        }
                        ?>
                    <option<?php echo $selected;?> value="<?php echo $value->id;?>"><?php echo $value->method;?></option>
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
        <div id="div_loading_validate" style="position: absolute;z-index: 99999;display: none;">
            <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
        </div> 
        <li class="clearfix"></li>        
        <?php $this->renderPartial('//chiphikhdfull/update/update_print_history',array('update_histoty_array'=>$update_histoty_array,'created_user'=>$created_user));?>
    </div>

    
    <input type="hidden" name="id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="bill_id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="reason"/>
    <input type="hidden" id="sum_socai" value="<?php echo $sum_socai;?>"/>
    
</form>


<div class="clearfix"></div>

<script type="text/javascript"> 
    
    jQuery(function($) {  
        
        $('#created_at').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0
        });

        
        
    });
</script>

<?php 
$this->renderPartial('//chiphikhdfull/validate');
$this->renderPartial('//chiphikhdfull/update/event_submit');
?>
