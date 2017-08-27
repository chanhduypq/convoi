
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
<li class="add_child two_img" id="add_customer1" title="Thêm mới nghỉ phép" style="margin-bottom: 5px;">
    <a>
        <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
    </a>
</li>
<li class="clearfix"></li>
<table class="title-HD1">
    <tbody id="listing_container">
        <tr class="title-HD sort1">
            <td class="title-HDliw5" style="float:left;height:50px;border-left:1px solid #00acac;padding:0 0 0 15px;font-weight:500;">#</td>
            <td class="title-HDli title-HDliw25">Nội dung</td>
            <td class="title-HDli title-HDliw20">Ngày bắt đầu nghỉ</td>
            <td class="title-HDli title-HDliw20">Ngày đi làm lại</td>
            <td class="title-HDli title-HDliw15">Số ngày</td>
            <td class="title-HDli title-HDliw15">Ngày phép còn lại</td>
            
        </tr>
        <?php $this->renderPartial('//user/editprofile/data_list_nghiphep',array('nghi_pheps'=>$nghi_pheps));?> 
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
    function validate_thu_chi1(){
        $("div.error").html('').hide();
        flag=true;    
        so_ngay_nghi=$.trim($('input[name="so_ngay_nghi"]').val());
        if(so_ngay_nghi!=""){
            so_ngay_nghi=so_ngay_nghi.replace(",",".");
        }
        if($.trim($('input[name="content1"]').val())==''){
            $("div.error.content1").html('Vui lòng nhập nội dung').show();
            flag=false;  
        }
        if(so_ngay_nghi==''||so_ngay_nghi=='0'){
            $("div.error.so_ngay_nghi").html('Vui lòng nhập số ngày nghỉ').show();
            flag=false;  
        }
        else if(!isFinite(so_ngay_nghi)){
            
            $("div.error.so_ngay_nghi").html('Vui lòng nhập số ngày nghỉ bằng số nguyên hoặc số thực. Ví dụ: 1,5 hoặc 1.5').show();
            flag=false;  
        }
        if($.trim($('input[name="start_date"]').val())==''){
            $("div.error.start_date").html('Vui lòng nhập ngày bắt đầu nghỉ').show();
            flag=false;  
        }
        if($.trim($('input[name="end_date"]').val())==''){
            $("div.error.end_date").html('Vui lòng nhập ngày đi làm lại').show();
            flag=false;  
        }
        return flag;
    }
    function save_thu_chi1() {
        if(validate_thu_chi1()==false){
            return;
        }
        $("#div_loading_customer").show();
        $.ajax({ 
            async: false,
            cache: false,
            type: "POST",
            url: '<?php echo $this->createUrl('/nghiphep/save'); ?>',
            data: {
                stt:$("#bill_number1").val(),
                so_ngay_nghi:$('input[name="so_ngay_nghi"]').val(),
                content:$('input[name="content1"]').val(),
                start_date:$('input[name="start_date"]').val(),
                end_date:$('input[name="end_date"]').val(),
                id_ungtien:$('input[name="id_ungtien1"]').val()
            },
            success: function(data, textStatus, jqXHR) {
                $("#div_loading_customer").hide();
                window.location.reload();
            }
        });
    }
    
    jQuery(function ($){

        
        $('#start_date').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
//            maxDate: 0,
//            minDate: '<?php // echo $min_date;?>',
            onClose: function() {                               
                if ($(this).val() != "") {
                    $("div.error.start_date").html('').hide();
                }
            }
        });
        $('#end_date').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
//            maxDate: 0,
//            minDate: '<?php // echo $min_date;?>',
            onClose: function() {                               
                if ($(this).val() != "") {
                    $("div.error.end_date").html('').hide();
                }
            }
        });
        
        $("body").delegate("tr.row-HD.edit1", "dblclick", function() {
            thuchi_id1=$(this).find('input[name="ungtien_id1[]"]').eq(0).val();
            
            jQuery("#dialog-modal-customer1").dialog({
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
                        url: '<?php echo $this->createUrl('/ajax/getnghiphep/id');?>/'+thuchi_id1,            
                        success: function(data, textStatus, jqXHR) {
                            if($.trim(data)!=''){
                                data=$.parseJSON(data);
                                
                                $('#khachhang1 input[name="so_ngay_nghi"]').attr('tabindex', '-1').val(data.so_ngay_nghi);
                                $('#khachhang1 input[name="content1"]').val(data.content);
                                $('#khachhang1 input[name="end_date"]').val(data.end_date);
                                $('#khachhang1 input[name="start_date"]').val(data.start_date);                          

                                $('#khachhang1 input[name="id_ungtien1"]').val(data.id);
                                $("#bill_number1").val(data.stt);
                            }
                        }
                    });

                    $('div.error').html('').hide();
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                },
                buttons: {
                    "<?php echo Yii::app()->params['text_for_button_save'];?>": save_thu_chi1,
                    "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                      jQuery("#dialog-modal-customer1").dialog('close');
                      $(".ui-dialog-buttonset").html('');
                    }
                }  
            });
            
        });
        
    });
</script>
<?php
$stt1=Yii::app()->db->createCommand("select max(stt) as max from nghiphep where user_id=".Yii::app()->session['user_id'])->queryScalar();
if($stt1==FALSE||$stt1==""){
    $stt1=0;
}
$stt1++;
$this->renderPartial('//user/editprofile/create_update_nghiphep',array('stt1'=>$stt1));
?>