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
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery.multiselect.filter.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery-ui.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.ui.datepicker-vi.min.js"></script>
<li class="add_child two_img" id="add_customer" title="Thêm mới ứng tiền/hoàn trả" style="margin-bottom: 5px;">
    <a>
        <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
    </a>
</li>
<li class="clearfix"></li>
<table class="title-HD1">
    <tbody id="listing_container">
        <tr class="title-HD sort1">
            <td class="title-HDliw5" style="float:left;height:50px;border-left:1px solid #00acac;padding:0 0 0 15px;font-weight:500;">#</td>
            <td class="title-HDli title-HDliw10">Ngày</td>
            <td class="title-HDli title-HDliw25">Nội dung</td>
            <td class="title-HDli title-HDliw15">Ứng tiền</td>
            <td class="title-HDli title-HDliw15">Hoàn trả</td>
            <td class="title-HDli title-HDliw15">Số tiền</td>
            <td class="title-HDli title-HDliw15">Xác nhận</td>
        </tr>
        <?php $this->renderPartial('//user/editprofile/data_list_ungtien',array('ung_tiens'=>$ung_tiens));?> 
    </tbody>
    <tfoot>
<!--        <tr>
            <td colspan="9">
                <label style="float: right;margin-left: 10px;cursor: pointer;margin-top: 10px;"><input type="checkbox" id="cb_banhang1"/>Bán hàng</label>
                <li class="add_child two_img" id="add_customer1" title="Thêm mới thu/chi" style="margin-top: 10px;">
                    <a>
                        <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
                    </a>
                </li>
                <li class="clearfix"></li>
                <button style="float: right;margin-bottom: 10px;" class="cursor">Kết sổ</button>
            </td>
        </tr>-->
        
        
        
    </tfoot>
    
</table>
<script type="text/javascript">
    function validate_thu_chi(){
        $("div.error").html('').hide();
        flag=true;        
        if($('input[name="tien"]').val()==''||$('input[name="tien"]').val()=='0'){
            $("div.error.tien").html('Vui lòng nhập số tiền').show();
            flag=false;        
        }
        if($.trim($('input[name="content"]').val())==''){
            $("div.error.content").html('Vui lòng nhập nội dung').show();
            flag=false;  
        }
        if($.trim($('input[name="created_at"]').val())==''){
            $("div.error.created_at").html('Vui lòng nhập ngày').show();
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
            url: '<?php echo $this->createUrl('/ungtien/save'); ?>',
            data: {
                stt:$("#bill_number").val(),
                ungtien_hoantra:thu_chi,
                tien:$('input[name="tien"]').val(),
                content:$('input[name="content"]').val(),                
                created_at:$('input[name="created_at"]').val(),
                id_ungtien:$('input[name="id_ungtien"]').val()
            },
            success: function(data, textStatus, jqXHR) {
                $("#div_loading_customer").hide();
                window.location.reload();
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
        
        $('#thuchi_created_at').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0,
//            minDate: '<?php // echo $min_date;?>',
            onClose: function() {                               
                if ($(this).val() != "") {
                    $("div.error.created_at").html('').hide();
                }
            }
        });
        
        $("body").delegate("tr.row-HD.edit", "dblclick", function() {
            thuchi_id=$(this).find('input[name="ungtien_id[]"]').eq(0).val();
            jQuery("#dialog-modal-customer").dialog({
                title:'',
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
                        url: '<?php echo $this->createUrl('/ajax/getungtien/id');?>/'+thuchi_id,            
                        success: function(data, textStatus, jqXHR) {
                            if($.trim(data)!=''){
                                data=$.parseJSON(data);
                                if(data.ung_tien=='0'){
                                    tien=data.hoan_tra;
                                    $("#chi").attr("checked","checked");
                                }
                                else{
                                    tien=data.ung_tien;
                                    $("#thu").attr("checked","checked");
                                }
                                $('#khachhang input[name="tien"]').val(tien);
                                $('#khachhang input[name="content"]').val(data.content);
                                $('#khachhang select[name="type"]').val(data.type);
                                $('#khachhang input[name="created_at"]').val(data.created_at);                          

                                $('#khachhang input[name="id_ungtien"]').val(data.id);
                                $("#bill_number").val(data.stt);
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
        
    });
</script>
<?php
$stt=Yii::app()->db->createCommand("select max(stt) as max from ung_tien where user_id=".Yii::app()->session['user_id'])->queryScalar();
if($stt==FALSE||$stt==""){
    $stt=0;
}
$stt++;
$this->renderPartial('//user/editprofile/create_update_ungtien',array('stt'=>$stt));
?>