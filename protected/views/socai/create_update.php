<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/autocomplete/jquery.auto-complete.css" />
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/autocomplete/jquery.auto-complete.js"></script>
<?php 
$DATE_FORMAT = Yii::app()->session['date_format'];
if ($DATE_FORMAT == 'Y.m.d') { 
    $date =date("Y.m.d");
} elseif ($DATE_FORMAT == 'Y-m-d') {
    $date =date("Y.m.d");
} elseif ($DATE_FORMAT == 'Y/m/d') {
    $date =date("Y.m.d");
} elseif ($DATE_FORMAT == 'Ymd') {
    $date =date("Y.m.d");
}
$payment_method=  PaymentMethod::model()->findAll("id<>".PaymentMethod::TAM_UNG." and id<>".PaymentMethod::CHUA_THANH_TOAN);//"id<>".PaymentMethod::CHUA_THANH_TOAN);
?>
<div style="display: none;" id="dialog-modal-customer">
    <div class="edit-KH" id="khachhang">
        <!--<form id="form_customer" method="POST">-->
            <div id="div_loading_customer" style="display: none;position: absolute;z-index: 99999;">
                <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
            </div>
            

            <li class="e_title">Thu/chi</li>
            <li style="float: left;width: 200px;border:1px solid #bbbdbe;height: 33px;padding-top: 5px;padding-left: 0px;">
                
                <label>
                    <input id="thu" checked="checked" type="radio" name="thu_chi" value="1"/>Thu
                </label>
                <label>
                    <input id="chi" type="radio" name="thu_chi" value="0"/>Chi
                </label>
                
            </li>
            <li class="clearfix"></li>
            
            <li class="e_title">&nbsp;</li>
            <li class="e_content1" style="margin-left: 0px;width: 200px;padding-left: 0px;">
                <label>
                    <input name="tam_ung" id="tam_ung" disabled="disabled" checked="checked" type="checkbox">
                    Tạm ứng
                </label>
            </li>
            <li class="clearfix"></li>
            
            <li class="e_title">Phương thức thanh toán</li>
            <li class="e_content1" style="width: 300px;">
                <select name="thanh_toan" style="width: 300px;">
                    <option value="">--Chọn phương thức thanh toán--</option>
                    <?php
                    foreach ($payment_method as $value) {?>
                    <option value="<?php echo $value->id;?>"><?php echo $value->method;?></option>
                    <?php    
                    }
                    ?>
                </select>
            </li>  
            <li class="clearfix"></li>
            <div class="error thanh_toan"></div>
            
            <li class="e_title">Giao dịch</li>
            <li class="e_content1" style="width: 300px;">
                <select name="giao_dich" style="width: 300px;">
                    <option value="">--Chọn giao dịch--</option>
                    <option id="hdtm" value="Hóa đơn thương mại">Hóa đơn thương mại</option>
                    <option id="sxdv" value="HĐ Sản xuất & dịch vụ">HĐ Sản xuất & dịch vụ</option>
                    <option id="kxhd" value="Không xuất hóa đơn">Không xuất hóa đơn</option>
                    <option id="ls" value="Lãi suất">Lãi suất</option>
                    <option id="tk" style="display: none;" value="Tờ khai">Tờ khai</option>
                    <option id="nkkd" style="display: none;" value="Nhập kho kinh doanh">Nhập kho kinh doanh</option>
                    <option id="cpdvchd" style="display: none;" value="Chi phí dịch vụ có hóa đơn">Chi phí dịch vụ có hóa đơn</option>
                    <option id="cpdvkhd" style="display: none;" value="Chi phí dịch vụ không hóa đơn">Chi phí dịch vụ không hóa đơn</option>
                </select>
            </li>  
            <li class="clearfix"></li>
            <div class="error giao_dich"></div>
            
            <li class="e_title" style="display: none;">Tham chiếu</li>
            <li class="e_content1" style="margin-left: 0px;width: 200px;padding-left: 0px;display: none;">
                <input name="tham_chieu" id="tham_chieu" type="text" class="cus-auto18-input">
            </li>
            <li class="clearfix"></li>
            <div class="error tham_chieu"></div>
            
            <li class="e_title" style="display: none;" id="to_khai1">&nbsp;</li>
            <li class="e_content1" style="width: 300px;display: none;" id="to_khai2">
                <select name="to_khai" style="width: 300px;">
                    <option value="Giá trị hàng hóa (VND)">Giá trị hàng hóa (VND)</option>
                    <option value="Chi phí ngân hàng (VND)">Chi phí ngân hàng (VND)</option>
                    <option value="Tiền thuế (VND)">Tiền thuế (VND)</option>
                </select>
            </li>  
            <li class="clearfix" id="to_khai3"></li>

            <li class="e_title">&nbsp;</li>        
            <li class="e_content2" style="margin-left: 0px;width: 200px;padding-left: 0px;"><input placeholder="Nhập số tiền tại đây" name="tien" type="text" value="" class="cus-auto18-input"></li>
            <li class="clearfix"></li>        
            <div class="error tien"></div>

            <li class="e_title">Nội dung</li>
            <li class="e_content1">
                <textarea name="content" id="content11" rows="2" style="margin-left: 0;width: 100%;background-color: #FFF;float: left;padding: 5px 25px 25px 8px;"></textarea>
            </li>
            <li class="clearfix"></li>
            <div class="error content"></div>
            
            <li class="e_title">Ngày</li>
            <li class="e_content1" style="margin-left: 0px;width: 200px;padding-left: 0px;">
                <input name="created_at" readonly="readonly" id="thuchi_created_at" type="text" value="<?php echo $date;?>" class="cus-auto18-input">
            </li>
            <li class="clearfix"></li>

            
            
            <input type="hidden" name="id"/>

        <!--</form>-->
    </div>
</div>



<script type="text/javascript"> 
    giao_dich='';
//    so_tien_con_lai=0;
    giao_dich_id='';
    jQuery(function($) {

        
        $('select[name="to_khai"]').change(function (){
            to_khai='0';
            if($('select[name="to_khai"]').is(":visible")){
                if($('select[name="to_khai"]').val()=='Giá trị hàng hóa (VND)'){
                    to_khai='3';
                }
                else if($('select[name="to_khai"]').val()=='Chi phí ngân hàng (VND)'){
                    to_khai='4';
                }
                else if($('select[name="to_khai"]').val()=='Tiền thuế (VND)'){
                    to_khai='5';
                }
            }
            $.ajax({ 
                async: false,
                cache: false,                                
                url: '<?php echo $this->createUrl('/ajax/getcontentthamchieu/giao_dich/');?>/tk/tham_chieu/'+$('#tham_chieu').val()+'/to_khai/'+to_khai,
                success: function(data, textStatus, jqXHR) {
                    if($.trim(data)!=""){
                        data=$.parseJSON(data);
                        so_tien_con_lai=data.so_tien_con_lai;
                        
                    }
                    else{
                        $("#content11").html('');
                        so_tien_con_lai=0;
                        giao_dich_id='';
                    }
                    

                }
            });
        });

        $('select[name="giao_dich"]').change(function (){
            if($(this).val()=='Tờ khai'){
                $("#to_khai1").show();
                $("#to_khai2").show();
                $("#to_khai3").show();
            }
            else{
                $("#to_khai1").hide();
                $("#to_khai2").hide();
                $("#to_khai3").hide();
            }

        });

        $('input[name="thu_chi"]').change(function() {
            if ($(this).val() == '1') {
                $("#hdtm").show();
                $("#sxdv").show();
                $("#kxhd").show();
                $("#ls").show();                
                $("#tk").hide();
                $("#nkkd").hide();
                $("#cpdvchd").hide();
                $("#cpdvkhd").hide();
            }
            else if ($(this).val() == '0') {
                $("#hdtm").hide();
                $("#sxdv").hide();
                $("#kxhd").hide();
                $("#ls").hide();                
                $("#tk").show();
                $("#nkkd").show();
                $("#cpdvchd").show();
                $("#cpdvkhd").show();
                
            }
            $("#to_khai1").hide();
            $("#to_khai2").hide();
            $("#to_khai3").hide();
            $('select[name="giao_dich"]').val('');
        });
        
        $('input[name="tien"]').number(true);
        $("#add_customer,#add_customer1").click(function() {
            if($(this).prev().find("input").eq(0).is(':checked')){   
                window.location='<?php echo $this->createUrl('/socai/creategoods/'); ?>';               
            }
            else{
                jQuery("#dialog-modal-customer").dialog({
                    title:'Thêm mới',
                    create: function(event, ui) {
                      $("body").css({ overflow: 'hidden' });
                      $('.title-HD.sort').css('z-index','1');
                     },
                     beforeClose: function(event, ui) {
                      $("body").css({ overflow: 'inherit' });
                     },

                    position: ['top', 110],                
                    height: 500,
                    width: 900,
                    show: {effect: "slide", duration: 500},
                    hide: {effect: "slide", duration: 500},
                    modal: true,
                    open: function(event, ui) {
                        $("div.error").html('').hide();
                        $("#tam_ung").parent().parent().prev().show();
                        $("#tam_ung").parent().parent().show();
                        $("#thu").prop("checked","checked");
                        $('#khachhang input[name="tham_chieu"]').hide();
                        $('#khachhang select[name="giao_dich"]').val('');
                        $('#khachhang input[name="tien"]').replaceWith('<input placeholder="Nhập số tiền tại đây" name="tien" type="text" value="" class="cus-auto18-input">');
                        $('input[name="tien"]').number(true);
                        $('#khachhang input[name="content"]').val('');
                        $('#khachhang input[name="created_at"]').val('<?php echo $date;?>');                          

                        $('#khachhang input[name="id"]').val('');
                        $("#to_khai1").hide();
                        $("#to_khai2").hide();
                        $("#to_khai3").hide();
                        $("#thu").removeAttr("disabled");
                        $("#chi").removeAttr("disabled");
                        $('#khachhang select[name="giao_dich"]').removeAttr("disabled");
                        $('#khachhang select[name="to_khai"]').removeAttr("disabled");
                        $('#khachhang select[name="thanh_toan"]').removeAttr("disabled").val('');
                        $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                        $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                        so_tien_con_lai=0;
                    },
                    buttons: {
                        "<?php echo Yii::app()->params['text_for_button_save'];?>": save_thu_chi,
                        "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                          jQuery("#dialog-modal-customer").dialog('close');
                          $(".ui-dialog-buttonset").html('');
                        }
                    }  
                });
            }         
        });

    });

</script>
