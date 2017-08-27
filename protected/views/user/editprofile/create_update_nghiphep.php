<div style="display: none;" id="dialog-modal-customer1">
    <div class="edit-KH" id="khachhang1">
        <!--<form id="form_customer" method="POST">-->
            <div id="div_loading_customer" style="display: none;position: absolute;z-index: 99999;">
                <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
            </div>
            

            <li class="cus-info cus-info16">STT</li>
            <li class="cus-auto18"><input disabled="disabled" id="bill_number1" name="stt" type="text" class="cus-auto18-input" value="<?php echo $stt1;?>"></li>
            <li class="clearfix"></li>
            
            <li class="e_title">Ngày bắt đầu nghỉ</li>
            <li class="e_content1" style="margin-left: 0px;width: 220px;padding-left: 0px;">
                <input tabindex="-1" name="start_date" readonly="readonly" id="start_date" type="text" value="" class="cus-auto18-input">
            </li>
            <li class="clearfix"></li>
            <div class="error start_date"></div>
            
            <li class="e_title">Ngày đi làm lại</li>
            <li class="e_content1" style="margin-left: 0px;width: 220px;padding-left: 0px;">
                <input tabindex="-1" name="end_date" readonly="readonly" id="end_date" type="text" value="" class="cus-auto18-input">
            </li>
            <li class="clearfix"></li>
            <div class="error end_date"></div>
            

            <li class="e_title">&nbsp;</li>        
            <li class="e_content2" style="margin-left: 0px;width: 220px;padding-left: 0px;"><input placeholder="Nhập số ngày nghỉ tại đây" name="so_ngay_nghi" type="text" value="" class="cus-auto18-input"></li>
            <li class="clearfix"></li>        
            <div class="error so_ngay_nghi"></div>
            
            

            <li class="e_title">Nội dung</li>
            <li class="e_content1">
                <input tabindex="-1" name="content1" type="text" value="" class="cus-auto18-input">
            </li>
            <li class="clearfix"></li>
            <div class="error content1"></div>
            
            

            
            
            <input type="hidden" name="id_ungtien1"/>
            <input type="hidden" id="stt1" value="<?php echo $stt1;?>"/>

        <!--</form>-->
    </div>
</div>



<script type="text/javascript"> 
    jQuery(function($) {
        
        $("#add_customer1").click(function() {
            jQuery("#dialog-modal-customer1").dialog({
//                    title:'Thêm mới',
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
                        
                        $('#khachhang1 input[name="so_ngay_nghi"]').replaceWith('<input tabindex="-1" placeholder="Nhập số ngày nghỉ tại đây" name="so_ngay_nghi" type="text" value="" class="cus-auto18-input">');
                        
                        $('#khachhang1 input[name="content1"]').val('');
                        $('#khachhang1 input[name="start_date"]').val('');
                        $('#khachhang1 input[name="end_date"]').val('');                          

                        $('#khachhang1 input[name="id_ungtien1"]').val('');
                        $("#bill_number1").val($("#stt1").val());
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

        
        
        $('#khachhang1 input[name="content1"]').on('input',function(e){
            node=$(this);
                delay(function(){
                    if($.trim($(node).val())!=''){
                        $("div.error.content1").html('').hide();
                    }
                    else{
                        $("div.error.content1").html('Vui lòng nhập nội dung').show();
                    }
                }, 1000 );
        
        });
        $("body").delegate('input[name="so_ngay_nghi"]', "keyup", function() {
            so_ngay_nghi=$.trim($('input[name="so_ngay_nghi"]').val());
            if(so_ngay_nghi!=""){
                so_ngay_nghi=so_ngay_nghi.replace(",",".");
            }
            if(so_ngay_nghi==''||so_ngay_nghi=='0'){
                $("div.error.so_ngay_nghi").html('Vui lòng nhập số ngày nghỉ').show();
            }
            else if(!isFinite(so_ngay_nghi)){
                $("div.error.so_ngay_nghi").html('Vui lòng nhập số ngày nghỉ bằng số nguyên hoặc số thực. Ví dụ: 1,5 hoặc 1.5').show();
                flag=false;  
            }
            else{
                $("div.error.so_ngay_nghi").html('').hide();
            }
            
            
        });
        

        

    });

</script>
