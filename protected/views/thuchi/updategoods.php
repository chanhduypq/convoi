<?php
$this->renderPartial('//render_partial/common/function_php_create_combobox_goods_goodsunit');
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
        
        <li class="clearfix"></li>

        <div class="div-margin">
            

            <li class="cus-info" style="padding-left: 0;width: 8%;">Ngày</li>
            <li class="cus-auto18" style="background-color: #ffffff;"><input readonly="readonly" id="created_at" name="created_at" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->created_at; ?>"></li>

            <li class="clearfix"></li>
            <li class="cus-info cus-info16" style="padding-left: 0;width: 8%;">Nội dung</li>
            <li class="cus-cty84"><textarea name="content" id="description" name="" cols="" rows="2" class="cus-cty84-input"><?php echo $invoicefull_model->content; ?></textarea></li>
            <div class="clearfix"></div>
            
            <li class="cus-info cus-info16" style="padding-left: 0;width: 8%;">&nbsp;</li>
            <li style="float: left;width: 84%;">                
                <select name="type" style="width: 200px;">
                    <option<?php if($invoicefull_model->type==ThuChi::TIEN_MAT) echo ' selected="selected"';?> value="<?php echo ThuChi::TIEN_MAT;?>">Tiền mặt</option>
                    <option<?php if($invoicefull_model->type==ThuChi::CHUYEN_KHOAN) echo ' selected="selected"';?> value="<?php echo ThuChi::CHUYEN_KHOAN;?>">Chuyển khoản</option>
                    <option<?php if($invoicefull_model->type==ThuChi::OTHER) echo ' selected="selected"';?> value="<?php echo ThuChi::OTHER;?>">Khác</option>
                </select>
            </li>
            <div class="clearfix"></div>
        </div>

        <h1>
            Thông tin hàng hóa bán           
        </h1>
        <li class="clearfix"></li>
        <?php 
        $this->renderPartial('//thuchi/updategoods/detail_bill_input_output',array('bill_details'=>$bill_details,'goods'=>$goods));
        ?>
        <!-- add new -->
        <li id="add_new_hanghoa" class="add-new" style="cursor: pointer;color: blue;">+ thêm mới</li>
        <!-- Total -->
        <?php 
        $this->renderPartial('//thuchi/updategoods/detail_bill_footer_input_output',array('sum'=>$sum,'tax_sum'=>$tax_sum,'sum_all'=>$sum_all));
        ?>

        <li class="clearfix"></li>
        <div class="div-margin">
            <li class="buttonHDsave" id="submit"><a>Lưu</a></li>
        </div>
        <li class="clearfix"></li>        
        
    </div>

    <input type="hidden" name="sum" value="<?php echo $sum;?>"/>
    <input type="hidden" name="tax_sum" value="<?php echo $tax_sum;?>"/>
    <input type="hidden" name="id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="bill_id" value="<?php echo $invoicefull_model->id; ?>"/>
    <input type="hidden" name="reason"/>
    <input type="hidden" id="edit_lien1_lien2"/>
    <input type="hidden" name="print" id="print"/>
</form>


<div class="clearfix"></div>

<script type="text/javascript"> 
    
    jQuery(function($) {  
        $('#created_at').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0,
            minDate: '<?php echo $min_date;?>'
        });
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

$this->renderPartial('//render_partial/common/function_js_bill_input_output');
$this->renderPartial('//thuchi/updategoods/validate');
$this->renderPartial('//thuchi/creategoods/event_click');
$this->renderPartial('//thuchi/updategoods/event_select_change');
$this->renderPartial('//thuchi/creategoods/event_keyup');
$this->renderPartial('//thuchi/updategoods/event_submit');

//$this->renderPartial('//thuchi/updategoods/invoice_history_detail');     
?>