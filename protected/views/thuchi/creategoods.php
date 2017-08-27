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
$start_date=date($DATE_FORMAT);
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
<h1>Bán hàng</h1>
<div class="error"></div>
<form id="create_bill" method="POST">
    <div id="mauHD">


        <li class="clearfix"></li>

        <!-- Div Customer info -->
        <div class="div-margin">
            

            <li class="cus-info" style="padding-left: 0;width: 8%;">Ngày</li>
            <li class="cus-auto18" style="background-color: #ffffff;"><input readonly="readonly" id="created_at" name="created_at" type="text" class="cus-auto18-input" value="<?php echo $start_date; ?>"></li>

            <li class="clearfix"></li>
            <li class="cus-info cus-info16" style="padding-left: 0;width: 8%;">Nội dung</li>
            <li class="cus-cty84"><textarea name="content" id="description" name="" cols="" rows="2" class="cus-cty84-input"></textarea></li>
            <div class="clearfix"></div>
            
            <li class="cus-info cus-info16" style="padding-left: 0;width: 8%;">&nbsp;</li>
            <li style="float: left;width: 84%;">                
                <select name="type" style="width: 200px;">
                    <option value="<?php echo ThuChi::TIEN_MAT;?>">Tiền mặt</option>
                    <option value="<?php echo ThuChi::CHUYEN_KHOAN;?>">Chuyển khoản</option>
                    <option value="<?php echo ThuChi::OTHER;?>">Khác</option>
                </select>
            </li>
            <div class="clearfix"></div>
        </div>

        <h1>
            Thông tin hàng hóa bán
            
        </h1>
        <li class="clearfix"></li>


        


        <!-- add new -->
        <li id="add_new_hanghoa" class="add-new" style="cursor: pointer;color: blue;">+ thêm mới</li>

        <!-- Total -->
        <div id="div-margin">
            <div class="div-pro1">
                <li class="all-total1" style="width: 40%;">Tổng cộng</li>
                <li class="all-total2" id="sum_sum" style="width: 30%;">
                    <label>0</label>
                    <div style="float: right;">
                        <img class="add" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/add_sum.png">
                        <img class="minus" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/minus_sum.png">
                    </div>
                </li>
                <li class="all-total2" id="sum_sum_tax" style="width: 30%;">
                    <label>0</label>
                    <div style="float: right;">
                        <img class="add" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/add_sum.png">
                        <img class="minus" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/minus_sum.png">
                    </div>
                </li>
                <li class="clearfix"></li>

                <li class="all-total1" style="height: 48px;width: 40%;">Tổng tiền thanh toán</li>
                <li class="all-total3" style="width: 60%;">
                    <span class="p_left" id="sum_sum_and_tax">0</span> 
                    <div style="float: left;display: table-cell;vertical-align: middle;padding-left: 0;padding-right: 0;padding-top: 5px;">
                        <img id="refresh" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/refresh.png">                        
                    </div>
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

    <input type="hidden" name="sum"/>
    <input type="hidden" name="tax_sum"/>
<input type="hidden" name="print" id="print"/>

</form>
<?php 

$this->renderPartial('//render_partial/common/function_js_bill_input_output');
$this->renderPartial('//thuchi/creategoods/validate');
$this->renderPartial('//thuchi/creategoods/event_click');
$this->renderPartial('//thuchi/creategoods/event_keyup');
?>
<script type="text/javascript">

    /**
     * 
     * 2 biến init_sum, init_tax_sum để nhớ trạng thái mới nhất lúc user chưa click 2 button cộng/trừ tại 2 ô tổng/tổng thuế
     */
    var init_sum=0;
    var init_tax_sum=0;

    jQuery(function($) {  
        
        $('#created_at').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0,
            minDate: '<?php echo $min_date;?>'
        });
        
        $("#div_loading_common").css({top:'50%',left:'50%',margin:'-'+($('#div_loading_common').height() / 2)+'px 0 0 -'+($('#div_loading_common').width() / 2)+'px'}).show();
        $.ajax({ 
            async: false,
            cache: false,
            url: '<?php echo Yii::app()->baseUrl . "/ajax/getallgoods"; ?>/for_bill/1',
            success: function(data, textStatus, jqXHR) {
                $("#div_loading_common").hide();
                if($.trim(data)!=''){
                    $(data).insertBefore($("#add_new_hanghoa"));
                    
                    setSumForHiddenInputs($(".div-pro1"),$('input[name="sum"]'),$('input[name="tax_sum"]'));
                    setTong($(".div-pro1"),$("#sum_sum label"),$("#sum_sum_tax label"),$("#sum_sum_and_tax"));              
                    showGoodsOrderLabel($(".div-margin li.prostt input.cus-auto18-input"));
                    $("select.goods").multiselect({
                        show: {effect: "slide", duration: 500},
                        hide: {effect: "slide", duration: 500},
                        noneSelectedText: "---Chọn hàng hóa---",
                        selectedText: "# Hàng hóa được chọn",
                        multiple:false
                    }).multiselectfilter();
                }
            }
        });
        
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

    })
</script>

<?php 
$this->renderPartial('//thuchi/creategoods/event_submit');
$this->renderPartial('//thuchi/creategoods/event_select_change');
$this->renderPartial('//render_partial/common/loading_print'); 
$this->renderPartial('//render_partial/common/popup_for_select_noidia_quocte'); 
?>