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
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery-ui.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.ui.datepicker-vi.min.js"></script>
<?php

/**
 * @return html
 * @param array $bill_type_array
 */
function echo_bill_type_combobox($bill_type_array) {
    ?>
    <select name="bill_type_id" class="selecthd">
        <option value="">---Chọn mẫu hóa đơn---</option>
        <?php foreach ($bill_type_array as $bill_type) { ?>
            <option value="<?php echo $bill_type['id']; ?>"><?php echo $bill_type['text']; ?></option>
            <?php
        }
        ?>        
    </select>
    <?php
}
?>
<div class="div-text">
    <li class="hd1 w22">Mẫu hoá đơn:</li>
    <li class="hd2 w28">
<?php echo_bill_type_combobox($bill_type_array); ?>
        
    </li>
    <li class="clearfix"></li>
    
    <li class="hd1 w22 hide error_row0">&nbsp;</li>
    <li class="hd2 w28 hide error_row0"><div class="error bill_type_id"></div></li>          
    <li class="clearfix hide error_row0"></li>

    <li class="hd1 w22">Ngày bắt đầu:</li>
    <li class="hd2 w28"><input name="start_date" class="save-hoadon text_input" type="text"></li>
    <li class="hd1 w22">Số bắt đầu:</li>
    <li class="hd2 w28"><input name="init_bill_number" class="save-hoadon" type="text"></li>
    <li class="clearfix"></li>
    
    <li class="hd1 w22 hide error_row1">&nbsp;</li>
    <li class="hd2 w28 hide error_row1"><div class="error start_date"></div></li>    
    <li class="hd1 w22 hide error_row1">&nbsp;</li>
    <li class="hd2 w28 hide error_row1"><div class="error init_bill_number"></div></li>    
    <li class="clearfix hide error_row1"></li>

    <li class="hd1 w22">Mẫu số:</li>
    <li class="hd2 w28"><input name="mau_so" class="save-hoadon" type="text"></li>
    <li class="hd1 w22">Ký hiệu:</li>
    <li class="hd2 w28"><input name="sign" class="save-hoadon" type="text"></li>
    <input type="hidden" name="bill_sign_id"/>
    <li class="clearfix"></li>
    

    
    <li class="hd1 w22 hide error_row2">&nbsp;</li>
    <li class="hd2 w28 hide error_row2"><div class="error mau_so"></div></li>    
    <li class="hd1 w22 hide error_row2">&nbsp;</li>
    <li class="hd2 w28 hide error_row2"><div class="error sign"></div></li>    
    <li class="clearfix hide error_row2"></li>
    
    <li class="but-save" id="save_bill_type">
        <a><?php echo Yii::app()->params['text_for_button_save']; ?></a>        
    </li>
    <div class="middle saved">
        <label>Lưu thành công</label>
    </div>
<!--<img style="width: 35px;height: 35px;margin-left: 30px;margin-top: 20px;display: none;" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/checked_icon.png"/>-->
    <li class="clearfix"></li>
</div>
<!--<div class="div-pichd">
    <li><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/pic-mauhd.png" width="175" height="175" alt="" /></a></li>
</div>-->
<script type="text/javascript">
    jQuery(function($) {
        $("#save_bill_type").next("div").hide();
        function saveBillSign() {
            $("div.error").html('').hide();
            $(".error_row0,.error_row1,.error_row2").hide();
            $("#div_loading_common").css("top",$("#save_bill_type").offset().top-300).show(); 
            
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/ajax/savebillsign'); ?>',
                data: {
                    sign: $('.div-text input[name="sign"]').val(),
                    init_bill_number: $('.div-text input[name="init_bill_number"]').val(),
                    start_date: $('.div-text input[name="start_date"]').val(),
                    mau_so: $('.div-text input[name="mau_so"]').val(),
                    id: $('.div-text input[name="bill_sign_id"]').val(),
                    bill_type_id: $('.div-text select[name="bill_type_id"]').val(),
                    is_submit:'1'
                },
                success: function(data, textStatus, jqXHR) {
                    $("#div_loading_common").hide();                     
                    if($.trim(data)!=""&&data.indexOf("BillSign")!=-1){                    
                        data=$.parseJSON(data);
                        for (key in data) {                                 
                            temp=key.replace("BillSign_","");
                            selector="div.error."+temp; 
                            if(temp=='bill_type_id'){
                                $(".error_row0").show();
                            }
                            else if(temp=='start_date'||temp=='init_bill_number'){
                                $(".error_row1").show();
                            }
                            else{
                                $(".error_row2").show();
                            }
                            $(selector).html(data[key]).show();
                            
                            
                        }
                    }
                    else{                        
                        $("#save_bill_type").next("div").show(500,function(){
                            setTimeout(function (){
                                $("#save_bill_type").next("div").hide();
                            },2000);
                        }); 
                    }
                }
            });
        }
        
        function getBillSignInfo(bill_type_id) {
            $("#div_loading_common").css("top",$("#save_bill_type").offset().top-300).show();  
            
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/ajax/getbillsigninfo'); ?>',
                data: {
                    bill_type_id: bill_type_id
                },
                success: function(data, textStatus, jqXHR) {
                    if($.trim(data)!=""){
                        data = $.parseJSON(data);
                        $('.div-text input[name="sign"]').val(data.sign);
                        $('.div-text input[name="init_bill_number"]').val(data.init_bill_number);
//                        if(data.allow_edit_init_bill_number=='0'){
//                            $('.div-text input[name="init_bill_number"]').attr("disabled",true);
//                            $('.div-text input[name="start_date"]').attr("disabled",true);
//                            $('.div-text input[name="mau_so"]').attr("disabled",true);
//                            $('.div-text input[name="sign"]').attr("disabled",true);
//                        }
//                        else{
//                            $('.div-text input[name="init_bill_number"]').attr("disabled",false);
//                            $('.div-text input[name="start_date"]').attr("disabled",false);
//                            $('.div-text input[name="mau_so"]').attr("disabled",false);
//                            $('.div-text input[name="sign"]').attr("disabled",false);
//                        }
                        $('.div-text input[name="start_date"]').val(data.start_date);
                        $('.div-text input[name="mau_so"]').val(data.mau_so);                        
                        $('.div-text input[name="bill_sign_id"]').val(data.id);
                    }
                    else{
                        $('.div-text input[name="sign"]').val('');
                        $('.div-text input[name="init_bill_number"]').val('');
//                        $('.div-text input[name="init_bill_number"]').attr("disabled",false);
//                        $('.div-text input[name="start_date"]').attr("disabled",false);
//                        $('.div-text input[name="mau_so"]').attr("disabled",false);
//                        $('.div-text input[name="sign"]').attr("disabled",false);
                        $('.div-text input[name="start_date"]').val('');
                        $('.div-text input[name="mau_so"]').val('');
                        $('.div-text input[name="bill_sign_id"]').val('');
                        
                    }
                    $("#div_loading_common").hide();
                }
            });
        }
        
        $('.div-text input[name="start_date"]').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            onClose: function() {
                if ($.trim($(this).val()) != "") {
                    $("div.error.start_date").html('').hide();
                    if($("div.error.init_bill_number").html()==""){
                        $(".error_row1").hide();
                    }   
                }
            }
        });

        $("#save_bill_type").click(function() {
            saveBillSign();
        });
        
        $('.div-text select[name="bill_type_id"]').change(function() {
            
            $("div.error").html('').hide();
            $(".error_row1,.error_row2").hide();
            
            if($(this).val()==''){
                $(".error_row0").show();
                $("div.error").html('Vui lòng chọn Mẫu hóa đơn.').show();
                
                $('.div-text input[name="sign"]').val('');
                $('.div-text input[name="init_bill_number"]').val('');
//                $('.div-text input[name="init_bill_number"]').attr("disabled",false);
//                $('.div-text input[name="start_date"]').attr("disabled",false);
//                $('.div-text input[name="mau_so"]').attr("disabled",false);
//                $('.div-text input[name="sign"]').attr("disabled",false);
                $('.div-text input[name="start_date"]').val('');
                $('.div-text input[name="mau_so"]').val('');
                $('.div-text input[name="bill_sign_id"]').val('');
            }
            else{
                $(".error_row0").hide();
                getBillSignInfo($(this).val());
            }
        });
        $('.div-text input').on('input',function(e){
            name=$(this).attr("name");                  
            $("div.error."+name).html('').hide();
            if(name=='start_date'){
                if($("div.error.init_bill_number").html()==""){
                    $(".error_row1").hide();
                }                
            }
            else if(name=='init_bill_number'){
                if($("div.error.start_date").html()==""){
                    $(".error_row1").hide();
                }                
            }
            else if(name=='mau_so'){
                if($("div.error.sign").html()==""){
                    $(".error_row2").hide();
                }                
            }
            else if(name=='sign'){
                if($("div.error.mau_so").html()==""){
                    $(".error_row2").hide();
                }                
            }
            
            
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/ajax/savebillsign'); ?>',
                data: {
                    sign: $('.div-text input[name="sign"]').val(),
                    init_bill_number: $('.div-text input[name="init_bill_number"]').val(),
                    start_date: $('.div-text input[name="start_date"]').val(),
                    mau_so: $('.div-text input[name="mau_so"]').val(),
                    id: $('.div-text input[name="bill_sign_id"]').val(),
                    bill_type_id: $('.div-text select[name="bill_type_id"]').val()
                },
                success: function(data, textStatus, jqXHR) {
               
                    if($.trim(data)!=""&&data.indexOf("BillSign")!=-1){                    
                        data=$.parseJSON(data);
                        for (key in data) {                                 
                            temp=key.replace("BillSign_","");
                            selector="div.error."+temp;                             
                            if(selector=="div.error."+name&&temp!='bill_type_id'){
                                $(selector).html(data[key]).show();
                                if(temp=='start_date'||temp=='init_bill_number'){
                                    $(".error_row1").show();
                                }
                                else if(temp=='mau_so'||temp=='sign'){
                                    $(".error_row2").show();
                                }
                            }
                            
                            
                        }
                    }
                    
                }
            });
        
        });
        

        

    });

</script>

