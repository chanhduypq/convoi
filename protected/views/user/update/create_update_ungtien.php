<?php
$payment_method=  PaymentMethod::model()->findAll("id<>".PaymentMethod::TAM_UNG." and id<>".PaymentMethod::CHUA_THANH_TOAN." and id<>".PaymentMethod::KHONG_THANH_TOAN);
?>
<div style="display: none;" id="dialog-modal-customer">
    <div class="edit-KH" id="khachhang">
        <!--<form id="form_customer" method="POST">-->
            <div id="div_loading_customer" style="display: none;position: absolute;z-index: 99999;">
                <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
            </div>


            
            <li class="e_title">&nbsp;</li>
            <li style="float: left;width: 250px;border:1px solid #bbbdbe;height: 33px;padding-top: 5px;padding-left: 0px;">
                
                <label>
                    <input id="thu" checked="checked" type="radio" name="xac_nhan" value="1"/>Đồng ý
                </label>
                <label>
                    <input id="chi" type="radio" name="xac_nhan" value="0"/>Không đồng ý
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

            <li class="e_title">Lý do</li>
            <li class="e_content1">
                <input name="content" type="text" value="" class="cus-auto18-input">
            </li>
            <li class="clearfix"></li>
            <div class="error content"></div>
            
            
            <input type="hidden" name="id_ungtien"/>

        <!--</form>-->
    </div>
</div>



<script type="text/javascript"> 
    jQuery(function($) {
        $("button").click(function() {
            id_ungtien=$(this).parent().parent().find('input').eq(0).val();
            jQuery("#dialog-modal-customer").dialog({
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
                        $("#thu").attr("checked","checked");
                        
                        $('#khachhang input[name="content"]').val('');
                        $('#khachhang select[name="thanh_toan"]').val('');
                        $('#khachhang input[name="created_at"]').val('');                          

                        $('#khachhang input[name="id_ungtien"]').val(id_ungtien);
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
