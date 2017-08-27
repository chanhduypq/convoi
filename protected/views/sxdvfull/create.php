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
$DATE_FORMAT=Yii::app()->session['date_format']; 
/**
 * nếu ngày hiện tại nhỏ hơn ngày bắt đầu sử dụng hóa đơn dc admin setting
 * thi lưu vào database là ngày bắt đầu sử dụng hóa đơn
 * còn không thi lưu vào ngày hiện tại
 */
$count=Yii::app()->db->createCommand("select count(*) from bill_sign where date(now())<date(start_date)")->queryScalar();
if($count=='1'){
    $start_date=Yii::app()->db->createCommand("select date(start_date) from bill_sign")->queryScalar();
    if ($DATE_FORMAT == 'Y.m.d') {
        $start_date= str_replace("-", ".", $start_date);
    } elseif ($DATE_FORMAT == 'Y-m-d') {
        $start_date= $start_date;
    } elseif ($DATE_FORMAT == 'Y/m/d') {
        $start_date= str_replace("-", "/", $start_date);
    } elseif ($DATE_FORMAT == 'Ymd') {
        $start_date= substr($start_date, 0,4).substr($start_date, 5,2).substr($start_date, 8,2);            
    } 
}
else{
    $start_date=date($DATE_FORMAT);
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
<h1>Xuất hóa đơn mới</h1>
<div class="error"></div>
<form id="create_bill" method="POST">
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
        <div class="div-margin">
            <li class="cus-info cus-info16">Số hóa đơn</li>
            <li class="cus-auto18"><input disabled="disabled" name="" type="text" class="cus-auto18-input" value="<?php echo $bill_number; ?>"></li>

            <li class="cus-info cus-info15">Ngày</li>
            <li class="cus-auto18"><input readonly="readonly" name="created_at" type="text" class="cus-auto18-input" value="<?php echo $start_date; ?>"></li>

            <li class="cus-info cus-info15" title="Mã số thuế">MST</li>
            <li class="cus-tax18"><input id="mst" name="" type="text" class="cus-auto18-input"></li>

            <li class="clearfix"></li>
            <li class="cus-info cus-info16">Đơn vị mua hàng</li>
            <li class="cus-cty84"><input id="branch_full_name" name="" type="text" class="cus-auto18-input"></li>

            <li class="clearfix"></li>
            <li class="cus-info cus-info16">Địa chỉ</li>
            <li class="cus-cty84"><textarea id="branch_address" name="" cols="" rows="2" class="cus-cty84-input"></textarea></li>
            
            <li class="clearfix"></li>
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

        <h1>
            Thông tin hàng hóa xuất
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
            <li class="buttonHD1" id="print_bill2"><a>Xuất hóa đơn liên 2</a></li>
            <li class="buttonHD1" id="print_bill1"><a>Xuất hóa đơn liên 1</a></li>            
            <li class="buttonHDsave" id="submit"><a>Lưu</a></li>
            
        </div>
        <li class="clearfix"></li>
 

    </div>
    <input type="hidden" name="branch_id"/>
    <input type="hidden" name="sum"/>
    <input type="hidden" name="tax_sum"/>
<input type="hidden" name="print" id="print"/>
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
$this->renderPartial('//sxdvfull/create/validate');
$this->renderPartial('//sxdvfull/event/event_click');
$this->renderPartial('//sxdvfull/event/event_keyup');
?>
<script type="text/javascript">
    
    var global_tax_code='';
    /**
     * 
     * 2 biến init_sum, init_tax_sum để nhớ trạng thái mới nhất lúc user chưa click 2 button cộng/trừ tại 2 ô tổng/tổng thuế
     */
    var init_sum=0;
    var init_tax_sum=0;

    jQuery(function($) {          
        
        $("#div_loading_common").css({top:'50%',left:'50%',margin:'-'+($('#div_loading_common').height() / 2)+'px 0 0 -'+($('#div_loading_common').width() / 2)+'px'}).show();
        $.ajax({ 
            async: false,
            cache: false,
            url: '<?php echo Yii::app()->baseUrl . "/ajax/getallgoods1"; ?>',
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
$this->renderPartial('//sxdvfull/create/event_submit',array('bill_number'=>$bill_number));
$this->renderPartial('//render_partial/create_customer',array('type'=>  Branch::CUSTOMER));
$this->renderPartial('//render_partial/common/loading_print'); 
?>
