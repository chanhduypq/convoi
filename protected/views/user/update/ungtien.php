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
<!--<li class="add_child two_img" id="add_customer" title="Thêm mới ứng tiền/hoàn trả" style="margin-bottom: 5px;">
    <a>
        <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
    </a>
</li>-->
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
        <?php $this->renderPartial('//user/update/data_list_ungtien',array('ung_tiens'=>$ung_tiens));?> 
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
        
        if($.trim($('input[name="content"]').val())==''){
            $("div.error.content").html('Vui lòng nhập nội dung').show();
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
            async: true,
            cache: false,
            type: "POST",
            url: '<?php echo $this->createUrl('/ungtien/confirm'); ?>',
            data: {                
                xac_nhan:thu_chi,
                lydo:$('input[name="content"]').val(),
                thanh_toan:$('select[name="thanh_toan"]').val(),
                id:$('input[name="id_ungtien"]').val()
            },
            success: function(data, textStatus, jqXHR) {
                $("#div_loading_customer").hide();
//                window.location.reload();
            }
        });
        window.location.reload();
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
           
                
                node_this=$(this);
                jQuery("#dialog-modal-tienmat").dialog({
                    title:'Tiền mặt ban đầu',
                    create: function(event, ui) {
                      $("body").css({ overflow: 'hidden' })
                     },
                     beforeClose: function(event, ui) {
                      $("body").css({ overflow: 'inherit' });
                     },

                    position: ['top', 110],                
                    height: 250,
                    width: 900,
                    show: {effect: "slide", duration: 500},
                    hide: {effect: "slide", duration: 500},
                    modal: true,
                    open: function(event, ui) {
                        $('#khachhang1 input[name="id1"]').val($(node_this).find('input[name="thuchi_id[]"]').eq(0).val());
                        tien_mat=$(node_this).find("td").eq(6).html();                        
                        if (tien_mat.indexOf(".") != -1) {
                            tien_mat = tien_mat.split(".").join("");
                        }
                        $('#khachhang1 input[name="tienmat"]').val(tien_mat);

                        $('div.error').html('').hide();
                        $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                        $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                    },
                    buttons: {
                        "<?php echo Yii::app()->params['text_for_button_save'];?>": saveTienmat,
                        "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                          jQuery("#dialog-modal-tienmat").dialog('close');
                          $(".ui-dialog-buttonset").html('');
                        }
                    }  
                });
                
            

            
        });

        
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
        
    });
</script>
<?php
$this->renderPartial('//user/update/create_update_ungtien');
?>
