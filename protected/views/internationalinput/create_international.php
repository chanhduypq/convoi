<div style="display: none;" id="dialog-modal-customer">
    <div class="edit-KH" id="khachhang">
        
        <div id="div_loading_customer" style="display: none;position: absolute;z-index: 99999;">
            <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
        </div>


        <li class="e_title">Tên công ty</li>
        <li class="e_content1"><input placeholder="Tên đầy đủ" name="full_name" type="text" value="" class="cus-auto18-input"></li>
        <li class="clearfix"></li>
        <div class="error full_name"></div>
        <div class="error full_name_for_unique_validate"></div>

        <li class="e_title">&nbsp;</li>        
        <li class="e_content2" style="margin-left: 0px;width: 200px;padding-left: 0px;"><input placeholder="Viết tắt" name="short_hand_name" type="text" value="" class="cus-auto18-input"></li>
        <li class="clearfix"></li>        
        <div class="error short_hand_name"></div>

        <li class="e_title">Địa chỉ</li>
        <li class="e_content1"><input name="address" type="text" value="" class="cus-auto18-input"></li>
        <li class="clearfix"></li>
        <div class="error address"></div>

        <li class="e_title">MST</li>
        <li class="e_content1"><input name="tax_code" type="text" value="" class="cus-auto18-input"></li>
        <li class="clearfix"></li>
        

        <li class="e_title">Người liên hệ</li>
        <li class="e_content1 first_name">
            <input placeholder="Tên" name="first_name" type="text" value="" class="cus-auto18-input">
        </li>  
        <li class="clearfix"></li>
        <li class="e_title"></li>            
        <li class="e_content1"><input placeholder="Họ" name="last_name" type="text" value="" class="cus-auto18-input"></li>
        <li class="clearfix"></li>
        <div class="error first_name"></div>
        <div class="error last_name"></div>

        <li class="e_title">Điện thoại</li>
        <li class="e_content1"><input name="phone" type="text" value="" class="cus-auto18-input"></li>
        <li class="clearfix"></li>
        <div class="error phone"></div>

        <li class="e_title">Email</li>
        <li class="e_content1"><input name="email" type="text" value="" class="cus-auto18-input"></li>
        <li class="clearfix"></li>
        <div class="error email"></div>          
            
        
    </div>
</div>
<script type="text/javascript">
    <?php
    $title_of_popup='Thêm thông tin '. lcfirst (Yii::app()->params['label_for_supplier']);
    ?>
    
    jQuery(function($) {
        
        
        function saveCustomer() {        
            $("#div_loading_customer").show();   
            $("div.error").html('').hide();        
            //
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/branch/savebranch/'); ?>',
                data: {
                    tax_code:$('#khachhang input[name="tax_code"]').val(),
                    address:$('#khachhang input[name="address"]').val(),
                    email:$('#khachhang input[name="email"]').val(),
                    phone:$('#khachhang input[name="phone"]').val(),
                    first_name:$('#khachhang input[name="first_name"]').val(),
                    last_name:$('#khachhang input[name="last_name"]').val(),
                    full_name:$('#khachhang input[name="full_name"]').val(),
                    short_hand_name:$('#khachhang input[name="short_hand_name"]').val(),
                    is_submit:'1',
                    type:'<?php echo $type;?>',
                    type_init:'<?php echo $type;?>',
                    is_international:'1'
                },
                success: function(data, textStatus, jqXHR) {
                    $("#div_loading_customer").hide();                
                    if($.trim(data)!=""&&data.indexOf("Branch")!=-1){                    
                        data=$.parseJSON(data);
                        for (key in data) {                                 
                            temp=key.replace("Branch_","");
                            selector="div.error."+temp;                        
                            $(selector).html(data[key]).show();
                        }
                    }
                    else{
                        jQuery("#dialog-modal-customer").dialog('close');
                    }
                }
            });
        }
        
        $('#khachhang input').on('input',function(e){
        node=$(this);
                delay(function(){
                    name=$(node).attr("name");                                   
                    if(name=="tax_code"){
                        return;
                    }
                    if(name=="full_name"){                
                        $("div.error.full_name_for_unique_validate,div.error.full_name").html('').hide();
                    }
                    else{
                        $("div.error."+name).html('').hide();
                    }

                    $.ajax({ 
                        async: false,
                        cache: false,
                        type: "POST",
                        url: '<?php echo $this->createUrl('/branch/savebranch/'); ?>',
                        data: {                    
                            address:$('#khachhang input[name="address"]').val(),
                            email:$('#khachhang input[name="email"]').val(),
                            phone:$('#khachhang input[name="phone"]').val(),
                            first_name:$('#khachhang input[name="first_name"]').val(),
                            last_name:$('#khachhang input[name="last_name"]').val(),
                            full_name:$('#khachhang input[name="full_name"]').val(),
                            short_hand_name:$('#khachhang input[name="short_hand_name"]').val(),
                            type:'<?php echo $type;?>',
                            is_international:'1'
                        },
                        success: function(data, textStatus, jqXHR) {

                            if($.trim(data)!=""&&data.indexOf("Branch")!=-1){                    
                                data=$.parseJSON(data);
                                for (key in data) {                                 
                                    temp=key.replace("Branch_","");
                                    selector="div.error."+temp; 
                                    if(name=="full_name"){
                                        if(selector=="div.error.full_name_for_unique_validate"||selector=="div.error.full_name"){
                                            $(selector).html(data[key]).show();
                                        }
                                    }
                                    else{
                                        if(selector=="div.error."+name){
                                            $(selector).html(data[key]).show();
                                        }
                                    }


                                }
                            }

                        }
                    });
                }, 2000 );
        
        });
        

        
        $("#add_customer").click(function() {               
            
            jQuery("#dialog-modal-customer").dialog({  
                title: '<?php echo $title_of_popup;?>',
                create: function(event, ui) {
                  $("body").css({ overflow: 'hidden' })
                 },
                 beforeClose: function(event, ui) {
                  $("body").css({ overflow: 'inherit' })
                 },
                position: ['top', 110],                
                height: 500,
                width: 900,
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                open: function(event, ui) {                                      
                    $('input[name="tax_code"]').val('');
                    $('input[name="address"]').val('');
                    $('input[name="email"]').val('');
                    $('input[name="phone"]').val('');
                    $('input[name="first_name"]').val('');
                    $('input[name="last_name"]').val('');
                    $('input[name="full_name"]').val('');
                    $('input[name="short_hand_name"]').val('');                
                    $('input[name="id"]').val('');
                    $('div.error').html('').hide();
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                },
                buttons: {
                    "<?php echo Yii::app()->params['text_for_button_save'];?>": saveCustomer,
                    "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                      jQuery("#dialog-modal-customer").dialog('close');
                      $(".ui-dialog-buttonset").html('');
                    }
                }  
            });
                      
        });
        
        
    });
</script>