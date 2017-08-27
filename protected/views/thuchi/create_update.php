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
            <li class="e_content2" style="margin-left: 0px;width: 200px;padding-left: 0px;"><input placeholder="Nhập số tiền tại đây" name="tien" type="text" value="" class="cus-auto18-input"></li>
            <li class="clearfix"></li>        
            <div class="error tien"></div>

            <li class="e_title">Nội dung</li>
            <li class="e_content1">
                <input name="content" type="text" value="" class="cus-auto18-input">
            </li>
            <li class="clearfix"></li>
            <div class="error content"></div>
            
            <li class="e_title">Ngày</li>
            <li class="e_content1" style="margin-left: 0px;width: 200px;padding-left: 0px;">
                <input name="created_at" readonly="readonly" id="thuchi_created_at" type="text" value="" class="cus-auto18-input">
            </li>
            <li class="clearfix"></li>
            <div class="error created_at"></div>

            
            <li class="e_title">TM/CK/Khác</li>
            <li class="e_content1" style="width: 200px;">
                <select name="type" style="width: 200px;">
                    <option value="<?php echo ThuChi::TIEN_MAT;?>">Tiền mặt</option>
                    <option value="<?php echo ThuChi::CHUYEN_KHOAN;?>">Chuyển khoản</option>
                    <option value="<?php echo ThuChi::OTHER;?>">Khác</option>
                </select>
            </li>  
            <li class="clearfix"></li>
            <div class="error type"></div>
            <input type="hidden" name="id"/>

        <!--</form>-->
    </div>
</div>



<script type="text/javascript"> 
    jQuery(function($) {
        $('input[name="tien"]').number(true);
        $("#add_customer,#add_customer1").click(function() {
            if($(this).prev().find("input").eq(0).is(':checked')){   
                window.location='<?php echo $this->createUrl('/thuchi/creategoods/'); ?>';               
            }
            else{
                jQuery("#dialog-modal-customer").dialog({
                    title:'Thêm mới thu/chi',
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
                        $("#thu").attr("checked","checked");
                        $('#khachhang input[name="tien"]').replaceWith('<input placeholder="Nhập số tiền tại đây" name="tien" type="text" value="" class="cus-auto18-input">');
                        $('input[name="tien"]').number(true);
                        $('#khachhang input[name="content"]').val('');
                        $('#khachhang select[name="type"]').val('<?php echo ThuChi::TIEN_MAT;?>');
                        $('#khachhang input[name="created_at"]').val('');                          

                        $('#khachhang input[name="id"]').val('');
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
            }
            
            
            
        });

        
        
        $('#khachhang input[name="content"]').on('input',function(e){
            node=$(this);
                delay(function(){
                    if($.trim($(node).val())!=''){
                        $("div.error.content").html('').hide();
                    }
                    else{
                        $("div.error.content").html('Vui lòng nhập nội dung').show();
                    }
                }, 1000 );
        
        });
        $("body").delegate('input[name="tien"]', "keyup", function() {
            if($.trim($(this).val())!=''&&$.trim($(this).val())!='0'){
                $("div.error.tien").html('').hide();
            }
            else{
                $("div.error.tien").html('Vui lòng nhập số tiền').show();
            }
        });
        

        

    });

</script>
