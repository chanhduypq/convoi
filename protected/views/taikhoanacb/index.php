<style>
    input.created_at{
        width: 100px !important;
    }
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
    $DATE_FORMAT = "yy.mm.dd";
} elseif ($DATE_FORMAT == 'Y-m-d') {
    $DATE_FORMAT = "yy-mm-dd";
} elseif ($DATE_FORMAT == 'Y/m/d') {
    $DATE_FORMAT = "yy/mm/dd";
} elseif ($DATE_FORMAT == 'Ymd') {
    $DATE_FORMAT = "yymmdd";
}
?>
<h1>Thống kê tài khoản ACB<?php if($all_time_common=='0') echo ": $start_date_common - $end_date_common";?></h1>
<!--<label style="float: right;margin-left: 10px;cursor: pointer;"><input type="checkbox" id="cb_banhang"/>Bán hàng</label>
<li class="add_child two_img" id="add_customer" title="Thêm mới thu/chi" style="margin-bottom: 5px;">
    <a>
        <img style="float: left;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-add-new.png">
    </a>
</li>
<li class="clearfix"></li>
<button style="float: right;margin-bottom: 10px;" class="cursor">Kết sổ</button>-->

<table class="title-HD1 sort">
    <tbody id="listing_container">
        <tr class="title-HD sort">
            <td class="title-HDliw5" style="float:left;height:50px;border-left:1px solid #00acac;padding:0 0 0 15px;font-weight:500;">#</td>
            <td class="title-HDli title-HDliw10 created_at">Ngày</td>
            <td class="title-HDli title-HDliw20 content">Nội dung</td>
            <td class="title-HDli title-HDliw15 thu">Thu</td>
            <td class="title-HDli title-HDliw15 chi">Chi</td>
            <td class="title-HDli title-HDliw10 kho_hang">Kho hàng</td>
            <td class="title-HDli title-HDliw25 tm">Số tiền</td>
        </tr>
        <?php $this->renderPartial('//taikhoanacb/data_list',array('items'=>$items,'index'=>$index));?> 
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
        <?php $this->renderPartial('//render_partial/common/distance_tbody_thead_for_list_page',array('colspan'=>'9'));?>
        
        <tr class="all-HD">
            <td class="all-HDli row0-HDliw5">&nbsp;<?php echo $count;?></td>
            <td class="all-HDli row0-HDliw10">&nbsp;</td>
            <td class="all-HDli row0-HDliw20">&nbsp;</td>
            <td class="all-HDli row0-HDliw15"><?php echo number_format($sum_thu, 0, ",", ".");?></td>
            <td class="all-HDli row0-HDliw15"><?php echo number_format($sum_chi, 0, ",", ".");?></td>
            <td class="all-HDli row0-HDliw10">&nbsp;</td>
            <td class="all-HDli row0-HDliw25">&nbsp;</td>
        </tr>
    </tfoot>
    
</table>
<input type="hidden" name="start_date_common" id="start_date_common" value="<?php echo $start_date_common;?>"/>
<input type="hidden" name="end_date_common" id="end_date_common" value="<?php echo $end_date_common;?>"/>
<input type="hidden" name="all_time_common" id="all_time_common" value="<?php echo $all_time_common;?>"/>
<input type="hidden" name="ket_so" id="ket_so" value="0"/>
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
            url: '<?php echo $this->createUrl('/taikhoanacb/save'); ?>',
            data: {
                thuchi:thu_chi,
                tien:$('input[name="tien"]').val(),
                content:$('input[name="content"]').val(),
                type:$('select[name="type"]').val(),
                created_at:$('input[name="created_at"]').val(),
                id:$('input[name="id"]').val()
            },
            success: function(data, textStatus, jqXHR) {
                $("#div_loading_customer").hide();
                submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');
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
            url: '<?php echo $this->createUrl('/taikhoanacb/editinit'); ?>',
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
        $("button.cursor").click(function (){
            $("#div_loading_customer").show();
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/taikhoanacb/ketso'); ?>',
                success: function(data, textStatus, jqXHR) {
                    $("#div_loading_customer").hide();
                    submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');
                }
            });
        });
        
        $("body").delegate("tr.row-HD.edit", "dblclick", function() {
            thuchi_id=$(this).find('input[name="thuchi_id[]"]').eq(0).val();
            if($(this).hasClass("is_init")){
                <?php 
                if(FunctionCommon::get_role()==Role::ADMIN){?>
                node_this=$(this);
                jQuery("#dialog-modal-tienmat").dialog({
                    title:'Tài khoản ban đầu',
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
                <?php }?>
            }
            
            
        });
        
        
        $('#thuchi_created_at').datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0,
            minDate: '<?php echo $min_date;?>',
            onClose: function() {                               
                if ($(this).val() != "") {
                    $("div.error.created_at").html('').hide();
                }
            }
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
$this->renderPartial('//taikhoanacb/thuchi_histoty');
if(FunctionCommon::get_role()==Role::ADMIN){
    $this->renderPartial('//taikhoanacb/edit_init'); 
}
$this->renderPartial('//render_partial/common/load_more_data',array('page_count'=>  ceil($count/Yii::app()->params['number_of_items_per_page'])));
$this->renderPartial('//render_partial/common/sort',array('session_key'=>'tai_khoan_acb_list_sort','field_array'=>array(
                                                                            'created_at',
                                                                            'content',
                                                                            'thu',
                                                                            'chi',
                                                                            'tm',
                                                                            'kho_hang',
                                                                            'chuyen_khoan',
                                                                            'khac',
                                                                            )
    ));
?>