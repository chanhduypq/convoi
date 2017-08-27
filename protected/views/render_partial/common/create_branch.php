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
        <li class="e_content1" style="width: 403px;"><input name="tax_code" type="text" value="" class="cus-auto18-input"></li>
        <li class="e_title" style="width: 12px;">&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        <li class="e_content2" style="margin-left: 15px;width: 70px;"><input name="tax_code_chinhanh" type="text" value="" class="cus-auto18-input"></li>
        <li class="clearfix"></li>
        <div class="error tax_code"></div>
        <div class="error tax_code_chinhanh"></div>
        

        <li class="e_title">Người liên hệ</li>
        <li class="e_content1 first_name combobox">
            <select name="first_name">
                <option value="anh">anh</option>
                <option value="chị">chị</option>
            </select>
        </li>  
        <li class="e_title" style="width: 12px;">&nbsp;</li>
        <li class="e_content2" style="margin-left: 15px;width: 403px;"><input placeholder="Tên" name="last_name" type="text" value="" class="cus-auto18-input"></li>
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
    if($type==Branch::CUSTOMER){
        $url_for_submit_form_common=$this->createUrl('/customerfull/index');
        $title_of_popup='Thêm thông tin khách hàng';
    }
    else if($type==Branch::SUPPLIER){
        $url_for_submit_form_common=$this->createUrl('/supplierfull/index');
        $title_of_popup='Thêm thông tin '. lcfirst (Yii::app()->params['label_for_supplier']);
    }
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
                    tax_code_chinhanh:$('#khachhang input[name="tax_code_chinhanh"]').val(),
                    address:$('#khachhang input[name="address"]').val(),
                    email:$('#khachhang input[name="email"]').val(),
                    phone:$('#khachhang input[name="phone"]').val(),
                    first_name:$('#khachhang select[name="first_name"]').val(),
                    last_name:$('#khachhang input[name="last_name"]').val(),
                    full_name:$('#khachhang input[name="full_name"]').val(),
                    short_hand_name:$('#khachhang input[name="short_hand_name"]').val(),
                    is_submit:'1',
                    type:'<?php echo $type;?>',
                    type_init:'<?php echo $type;?>'
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
                        if($("div.error.tax_code").html()=='Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'&&$("div.error.tax_code_chinhanh").html()=='Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'){
                            $("div.error.tax_code_chinhanh").html('').hide();
                        } 
                        
                    }
                    else{
                        jQuery("#dialog-modal-customer").dialog('close');
                        submit_form_common('<?php echo $url_for_submit_form_common;?>','<?php echo $this->createUrl("/ajax/search"); ?>');                   
                    }
                }
            });
        }
        
        $('#khachhang input').on('input',function(e){
            node=$(this);
            delay(function(){
                name=$(node).attr("name");                  
                if(name=="full_name"){                
                    $("div.error.full_name_for_unique_validate,div.error.full_name").html('').hide();
                }
                else{
                    $("div.error."+name).html('').hide();
                }
                if(name=="tax_code"){                
                    if($("div.error.tax_code_chinhanh").html()=='Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'){
                        $("div.error.tax_code_chinhanh").html('').hide();
                    }                
                }
                else if(name=="tax_code_chinhanh"){                
                    if($("div.error.tax_code").html()=='Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'){
                        $("div.error.tax_code").html('').hide();
                    }                
                }

                $.ajax({ 
                    async: false,
                    cache: false,
                    type: "POST",
                    url: '<?php echo $this->createUrl('/branch/savebranch/'); ?>',
                    data: {
                        tax_code:$('#khachhang input[name="tax_code"]').val(),
                        tax_code_chinhanh:$('#khachhang input[name="tax_code_chinhanh"]').val(),
                        address:$('#khachhang input[name="address"]').val(),
                        email:$('#khachhang input[name="email"]').val(),
                        phone:$('#khachhang input[name="phone"]').val(),
                        first_name:$('#khachhang select[name="first_name"]').val(),
                        last_name:$('#khachhang input[name="last_name"]').val(),
                        full_name:$('#khachhang input[name="full_name"]').val(),
                        short_hand_name:$('#khachhang input[name="short_hand_name"]').val(),
                        type:'<?php echo $type;?>'
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
                                else if(name=="tax_code"&&temp=='tax_code_chinhanh'&&data[key]=='Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'){                
                                    $("div.error.tax_code_chinhanh").html('Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.').show();             
                                }
                                else if(name=="tax_code_chinhanh"&&temp=='tax_code'&&data[key]=='Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'){                
                                    $("div.error.tax_code").html('Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.').show();              
                                }
                                else{
                                    if(selector=="div.error."+name){
                                        $(selector).html(data[key]).show();
                                    }
                                }                        

                            }
                            if($("div.error.tax_code").html()=='Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'&&$("div.error.tax_code_chinhanh").html()=='Đã tồn tại mã số thuế này rồi. Vui lòng nhập lại.'){
                                $("div.error.tax_code_chinhanh").html('').hide();
                            } 
                        }

                    }
                });
            }, 2000 );
        
        });
        

        
        $("#add-new img").eq(1).click(function() {               
            jQuery("#dialog-modal-customer").dialog({  
                title: '<?php echo $title_of_popup;?>',
                create: function(event, ui) {
                  $("body").css({ overflow: 'hidden' });
                  $('.title-HD.sort').css('z-index','1');
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
                    $('input[name="tax_code"]').val('');
                    $('input[name="tax_code_chinhanh"]').val('');
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