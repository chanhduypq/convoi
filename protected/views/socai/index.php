<style>
    button.ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-icon-only.ui-dialog-titlebar-close{
        display: block !important;
        background: none !important;
    }
    button.ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-icon-only.ui-dialog-titlebar-close span.ui-button-text{
        display: none !important;
    }
</style>
<?php 
$DATE_FORMAT = Yii::app()->session['date_format'];
if ($DATE_FORMAT == 'Y.m.d') { 
    $date1 =date("Y.m.d");
} elseif ($DATE_FORMAT == 'Y-m-d') {
    $date1 =date("Y.m.d");
} elseif ($DATE_FORMAT == 'Y/m/d') {
    $date1 =date("Y.m.d");
} elseif ($DATE_FORMAT == 'Ymd') {
    $date1 =date("Y.m.d");
}
?>
<h1>Thống kê sổ cái<?php if($all_time_common=='0') echo ": $start_date_common - $end_date_common";?></h1>
<?php if(FunctionCommon::get_role()==Role::ADMIN||FunctionCommon::get_role()==Role::QUAN_LY_BAN_HANG){?>
<li class="add_child two_img" id="add_customer" title="Thêm mới" style="margin-bottom: 5px;">
    <a>
        <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
    </a>
</li>
<li class="clearfix"></li>
<?php }?>
<style>
    .title-HDli1{
            float:left;
            height:50px;
            border-left:1px solid #00acac;
            padding:17px 0 0 15px;
            font-weight:500;
            text-align: center !important;
            vertical-align: middle !important;
            padding: 0 !important;
    }
    .title-HDli1.created_at{
        cursor: pointer;
    }
    .title-HDli1.created_at:hover{
            background-image: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-down.png);
            background-position: center bottom;
            background-repeat: no-repeat;
    }
    .title-HD.sort .title-HDli1.created_at.sort{
            background-position: center bottom;
            background-repeat: no-repeat;
    }
    .title-HD.sort .title-HDli1.created_at.sort.down{
            background-image: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-down.png);
    }
    .title-HD.sort .title-HDli1.created_at.sort.down:hover{
            background-image: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-up.png);
    }
    .title-HD.sort .title-HDli1.created_at.sort.up{
            background-image: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-up.png);
    }
    .title-HD.sort .title-HDli1.created_at.sort.up:hover{
            background-image: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-down.png);
    }
    .title-HD1.sort td.row0-KHli{
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
</style>

<table class="title-HD1 sort">
    <tbody id="listing_container">
        <tr class="title-HD sort">
            <td class="title-HDliw5" style="float:left;height:50px;border-left:1px solid #00acac;padding:0 0 0 15px;font-weight:500;width: 4%;">#</td>
            <td class="title-HDli1 title-HDliw8 created_at">Ngày</td>
            <td class="title-HDli1 title-HDliw16 content">Nội dung</td>
            <td class="title-HDli1 title-HDliw10 giao_dich">Giao dịch</td>
            <td class="title-HDli1 title-HDliw12 thu">Thu</td>
            <td class="title-HDli1 title-HDliw12 chi">Chi</td>
            <td class="title-HDli1 title-HDliw10 thanh_toan">Thanh toán</td>
            <td class="title-HDli1 title-HDliw10 tm">Số tiền</td>
            <td class="title-HDli1 title-HDliw8 tham_chieu">Tham chiếu</td>
            <td class="title-HDli1 title-HDliw10 trang_thai">Trạng thái</td>
        </tr>
        <?php $this->renderPartial('//socai/data_list',array('items'=>$items,'index'=>$index));?> 
    </tbody>
    <tfoot>
        <?php if(FunctionCommon::get_role()==Role::ADMIN||FunctionCommon::get_role()==Role::QUAN_LY_BAN_HANG){?>
        <tr>
            <td colspan="9">
                <li class="add_child two_img" id="add_customer1" title="Thêm mới" style="margin-top: 10px;">
                    <a>
                        <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
                    </a>
                </li>
                <li class="clearfix"></li>
            </td>
        </tr>
        <?php }?>
        <?php $this->renderPartial('//render_partial/common/distance_tbody_thead_for_list_page',array('colspan'=>'9'));?>
        
        <tr class="all-HD">
            <td class="all-HDli row0-HDliw5" style="width: 3%;">&nbsp;<?php echo $count;?></td>
            <td class="all-HDli row0-HDliw8">&nbsp;</td>
            <td class="all-HDli row0-HDliw16">&nbsp;</td>
            <td class="all-HDli row0-HDliw10">&nbsp;</td>
            <td class="all-HDli row0-HDliw12"><?php echo number_format($sum_thu, 0, ",", ".");?></td>
            <td class="all-HDli row0-HDliw12"><?php echo number_format($sum_chi, 0, ",", ".");?></td>
            <td class="all-HDli row0-HDliw10">&nbsp;</td>
            <td class="all-HDli row0-HDliw10">&nbsp;</td>
            <td class="all-HDli row0-HDliw8">&nbsp;</td>
            <td class="all-HDli row0-HDliw10">&nbsp;</td>
        </tr>
    </tfoot>
    
</table>
<input type="hidden" name="start_date_common" id="start_date_common" value="<?php echo $start_date_common;?>"/>
<input type="hidden" name="end_date_common" id="end_date_common" value="<?php echo $end_date_common;?>"/>
<input type="hidden" name="all_time_common" id="all_time_common" value="<?php echo $all_time_common;?>"/>
<script type="text/javascript">
    so_tien_con_lai=0;
    function validate_thu_chi(){
        $("div.error").html('').hide();
        flag=true;        
        if($('input[name="tien"]').val()==''||$('input[name="tien"]').val()=='0'){
            $("div.error.tien").html('Vui lòng nhập số tiền').show();
            flag=false;        
        }
        else{
            tien=$('input[name="tien"]').val();
            if (tien.indexOf(".") != -1) {
                tien = tien.split(".").join("");
            }
            if(tien==''){
                tien=0;
            }
            if(tien>so_tien_con_lai&&so_tien_con_lai>0){
                $("div.error.tien").html('Vui lòng kiểm tra lại số tiền, bạn đã nhập dư số tiền.').show();
                flag=false;
            }
        }
        if($.trim($('textarea[name="content"]').val())==''){
            $("div.error.content").html('Vui lòng nhập nội dung').show();
            flag=false;  
        }
        if($.trim($('select[name="giao_dich"]').val())==''){
            $("div.error.giao_dich").html('Vui lòng chọn giao dịch').show();
            flag=false;  
        }
        if($.trim($('select[name="thanh_toan"]').val())==''){
            $("div.error.thanh_toan").html('Vui lòng chọn phương thức thanh toán').show();
            flag=false;  
        }
        return flag;
    }
    function save_thu_chi() {
        if(validate_thu_chi()==false){
            return;
        }
        $("#div_loading_customer").show();
        if($("#thu").is(':checked')){   
            thu_chi='1';                
        }
        else{
            thu_chi='0';                  
        }

        $.ajax({ 
            async: false,
            cache: false,
            type: "POST",
            url: '<?php echo $this->createUrl('/socai/save'); ?>',
            data: {
                thuchi:thu_chi,
                tien:$('input[name="tien"]').val(),
                content:$('textarea[name="content"]').val(),
                giao_dich:$('select[name="giao_dich"]').val(),
                created_at:$('input[name="created_at"]').val(),
                thanh_toan:$('select[name="thanh_toan"]').val(),
                to_khai:$('select[name="to_khai"]').is(":visible")?$('select[name="to_khai"]').val():'',
                id:$('input[name="id"]').val()
            },
            success: function(data, textStatus, jqXHR) {
                $("#div_loading_customer").hide();
                if($.trim(data)!=""){
                    alert(data);
                }
                else{
                    submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');
                }
                
            }
        });
    }
    function saveTienmat() {
        $("div.error").html('').hide();
        if($('input[name="tienmat"]').val()==''||$('input[name="tienmat"]').val()=='0'){
            $("div.error.tienmat").html('Vui lòng nhập số tiền').show();
            return;
        }
        $("#div_loading_customer").show();        
        $.ajax({ 
            async: false,
            cache: false,
            type: "POST",
            url: '<?php echo $this->createUrl('/thuchi/editinit'); ?>',
            data: {
                tienmat:$('input[name="tienmat"]').val(),
                id:$('input[name="id1"]').val()
            },
            success: function(data, textStatus, jqXHR) {
                $("#div_loading_customer").hide();
                submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');
            }
        });
    }
    jQuery(function ($){
        
        
        $("body").delegate("tr.row-HD.edit", "dblclick", function() {
            thuchi_id=$(this).find('input[name="thuchi_id[]"]').eq(0).val();
            jQuery("#dialog-modal-customer").dialog({
                title:'Sửa',
                create: function(event, ui) {
                  $("body").css({ overflow: 'hidden' })
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
                    $.ajax({ 
                        async: false,
                        cache: false,                                
                        url: '<?php echo $this->createUrl('/ajax/getsocai/id');?>/'+thuchi_id,            
                        success: function(data, textStatus, jqXHR) {
                            if($.trim(data)!=''){
                                data=$.parseJSON(data);
                                so_tien_con_lai=data.so_tien_con_lai;
                                $("#tam_ung").parent().parent().prev().hide();
                                $("#tam_ung").parent().parent().hide();
                                
                                if(data.giao_dich=='Hóa đơn thương mại'||data.giao_dich=='HĐ Sản xuất & dịch vụ'||data.giao_dich=='Không xuất hóa đơn'||data.giao_dich=='Lãi suất'){
                                    tien=data.thu;
                                    $("#thu").prop("checked","checked");
                                    $("#hdtm").show();
                                    $("#sxdv").show();
                                    $("#kxhd").show();
                                    $("#ls").show();                
                                    $("#tk").hide();
                                    $("#nkkd").hide();
                                    $("#cpdvchd").hide();
                                    $("#cpdvkhd").hide();
                                }
                                else{      
                                    
                                    tien=data.chi;
                                    $("#chi").prop("checked","checked");
                                    $("#hdtm").hide();
                                    $("#sxdv").hide();
                                    $("#kxhd").hide();
                                    $("#ls").hide();                
                                    $("#tk").show();
                                    $("#nkkd").show();
                                    $("#cpdvchd").show();
                                    $("#cpdvkhd").show();
                                }
                                $('#khachhang input[name="tien"]').val(tien);
                                $('#khachhang textarea[name="content"]').val(data.content);
                                if(data.thanh_toan!='<?php echo PaymentMethod::CHUA_THANH_TOAN?>'){
                                    $('#khachhang select[name="thanh_toan"]').val(data.thanh_toan);
                                }
                                else{
                                    $('#khachhang select[name="thanh_toan"]').val('');
                                }
                                
                                
                                if(data.giao_dich.indexOf('Tờ khai')!=-1){
                                    $('#khachhang select[name="giao_dich"]').val('Tờ khai');
                                    $("#to_khai1").show();
                                    $("#to_khai2").show();
                                    $("#to_khai3").show();
                                    if(data.giao_dich.indexOf('Giá trị hàng hóa (VND)')!=-1){
                                        $('#khachhang select[name="to_khai"]').val('Giá trị hàng hóa (VND)');
                                    }
                                    else if(data.giao_dich.indexOf('Chi phí ngân hàng (VND)')!=-1){
                                        $('#khachhang select[name="to_khai"]').val('Chi phí ngân hàng (VND)');
                                    }
                                    else if(data.giao_dich.indexOf('Tiền thuế (VND)')!=-1){
                                        $('#khachhang select[name="to_khai"]').val('Tiền thuế (VND)');
                                    }
                                }
                                else{
                                    $('#khachhang select[name="giao_dich"]').val(data.giao_dich);
                                    $("#to_khai1").hide();
                                    $("#to_khai2").hide();
                                    $("#to_khai3").hide();
                                }
                                $('#khachhang input[name="created_at"]').val('<?php echo $date1;?>');                          

                                $('#khachhang input[name="id"]').val(data.id);
                                $('#khachhang select[name="giao_dich"]').attr("disabled","disabled");
                                $('#khachhang select[name="to_khai"]').attr("disabled","disabled");
//                                $('#khachhang textarea[name="content"]').attr("disabled","disabled");
                                $("#thu").attr("disabled","disabled");
                                $("#chi").attr("disabled","disabled");
                            }
                        }
                    });

                    $('div.error').html('').hide();
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                },
                buttons: {
                    "<?php echo Yii::app()->params['text_for_button_save'];?>": save_thu_chi,
                    "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                      jQuery("#dialog-modal-customer").dialog('close');
                      $(".ui-dialog-buttonset").html('');
                    }
                }  
            });
            
        });
        
        
        $("#all_time").click(function (){
            if($(this).is(':checked')){   
                $("#all_time_common").val('1');                
            }
            else{
                $("#all_time_common").val('0');                
            }
            submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');
            
        });
    });
</script>
<?php 
$this->renderPartial('//socai/create_update');
$this->renderPartial('//socai/thuchi_histoty');

$this->renderPartial('//render_partial/common/load_more_data',array('page_count'=>  ceil($count/Yii::app()->params['number_of_items_per_page'])));
$this->renderPartial('//render_partial/common/sort',array('session_key'=>'socai_list_sort','field_array'=>array(
                                                                            'created_at',
//                                                                            'content',
//                                                                            'thu',
//                                                                            'chi',
//                                                                            'tm',                                                                            
//                                                                            'giao_dich',
//                                                                            'thanh_toan',
//                                                                            'tham_chieu',
//                                                                            'trang_thai',
                                                                            )
    ));
?>