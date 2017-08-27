<div style="display: none;" id="dialog-modal-tienmat">
    <div class="edit-KH" id="khachhang1">
        
            <div id="div_loading_customer1" style="display: none;position: absolute;z-index: 99999;">
                <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
            </div>

            <li class="e_title">Tiền mặt</li>
            <li class="e_content1">
                <input placeholder="Nhập số tiền tại đây" name="tienmat" type="text" value="" class="cus-auto18-input">
            </li>
            <li class="clearfix"></li>
            <div class="error tienmat"></div>           
            <input type="hidden" name="id1"/>

       
    </div>
</div>



<script type="text/javascript"> 
    jQuery(function($) {
        $('input[name="tienmat"]').number(true);    
        $("body").delegate('input[name="tienmat"]', "keyup", function() {
            if($.trim($(this).val())!=''&&$.trim($(this).val())!='0'){
                $("div.error.tienmat").html('').hide();
            }
            else{
                $("div.error.tienmat").html('Vui lòng nhập số tiền').show();
            }
        });

    });

</script>
