<div style="display: none;" id="dialog-modal-customer-edit">
    <div class="edit-KH" id="khachhang_edit">

        <div id="div_loading_customer_edit" style="display: none;position: absolute;z-index: 99999;">
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

        <input type="hidden" name="id"/>

    </div>
</div>
<script type="text/javascript">
    <?php
    if($type==Branch::SUPPLIER){
        $url_for_submit_form_common=$this->createUrl('/international/index');
        $title_of_popup='Sửa thông tin '. lcfirst (Yii::app()->params['label_for_supplier']);
    }
    ?>
    
    function setInfo(branch_id) {
        $.ajax({ 
            async: false,
            cache: false,
            url: '<?php echo $this->createUrl('/branch/getbranch/id'); ?>/' + branch_id,
            success: function(data, textStatus, jqXHR) {
                if($.trim(data)!=''){
                    data = $.parseJSON(data);
                    $('input[name="tax_code"]').val(data.tax_code);
                    $('input[name="address"]').val(data.address);
                    $('input[name="email"]').val(data.email);
                    $('input[name="phone"]').val(data.phone);
                    $('input[name="first_name"]').val(data.first_name);
                    $('input[name="last_name"]').val(data.last_name);
                    $('input[name="full_name"]').val(data.full_name);
                    $('input[name="short_hand_name"]').val(data.short_hand_name);
                    $('input[name="id"]').val(data.id);
                }
            }
        });
    }





    jQuery(function($) {

        $('#khachhang_edit input').on('input',function(e){
            node=$(this);
            delay(function(){
                name=$(node).attr("name");                  
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
                        tax_code:$('#khachhang_edit input[name="tax_code"]').val(),
                        address:$('#khachhang_edit input[name="address"]').val(),
                        email:$('#khachhang_edit input[name="email"]').val(),
                        phone:$('#khachhang_edit input[name="phone"]').val(),
                        first_name:$('#khachhang_edit input[name="first_name"]').val(),
                        last_name:$('#khachhang_edit input[name="last_name"]').val(),
                        full_name:$('#khachhang_edit input[name="full_name"]').val(),
                        short_hand_name:$('#khachhang_edit input[name="short_hand_name"]').val(),
                        id: $('#khachhang_edit input[name="id"]').val(),
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

        function saveCustomer() {

            $("#div_loading_customer_edit").show();
            $("div.error").html('').hide();
            //
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/branch/savebranch/'); ?>',
                data: {
                    tax_code: $('#khachhang_edit input[name="tax_code"]').val(),
                    address: $('#khachhang_edit input[name="address"]').val(),
                    email: $('#khachhang_edit input[name="email"]').val(),
                    phone: $('#khachhang_edit input[name="phone"]').val(),
                    first_name: $('#khachhang_edit input[name="first_name"]').val(),
                    last_name: $('#khachhang_edit input[name="last_name"]').val(),
                    full_name: $('#khachhang_edit input[name="full_name"]').val(),
                    short_hand_name: $('#khachhang_edit input[name="short_hand_name"]').val(),
                    id: $('#khachhang_edit input[name="id"]').val(),
                    is_submit:'1',
                    is_international:'1'
                },
                success: function(data, textStatus, jqXHR) {
                    $("#div_loading_customer_edit").hide();
                    if ($.trim(data) != "" && data.indexOf("Branch") != -1) {
                        data = $.parseJSON(data);
                        for (key in data) {
                            temp = key.replace("Branch_", "");
                            selector = "div.error." + temp;
                            $(selector).html(data[key]).show();
                        }
                    }
                    else {
                        jQuery("#dialog-modal-customer-edit").dialog('close');
                        submit_form_common('<?php echo $url_for_submit_form_common;?>','<?php echo $this->createUrl("/ajax/search"); ?>');                        
                    }
                }
            });
        }

        function showDialogForEditCustomer(customer_id) {
            jQuery("#dialog-modal-customer-edit").dialog({
                title: '<?php echo $title_of_popup;?>',
                create: function(event, ui) {
                    $("body").css({overflow: 'hidden'});
                    $('.title-HD.sort').css('z-index','1');
                },
                beforeClose: function(event, ui) {
                    $("body").css({overflow: 'inherit'})
                },
                position: ['top', 110],
                height: 550,
                width: 900,
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                open: function(event, ui) {
                    $('div.error').html('').hide();
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                    /**
                     * hiển thị thông tin khách hàng dc load từ db lên
                     */
                    setInfo(customer_id);
                },
                buttons: {
                    "<?php echo Yii::app()->params['text_for_button_save'];?>": saveCustomer,
                    "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                      jQuery("#dialog-modal-customer-edit").dialog('close');
                      $(".ui-dialog-buttonset").html('');
                    }
                }  
            });
            $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
            $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
        }

        //
        $("body").delegate("td.edit_customer a", "click", function() {        
            customer_id=$(this).parent().parent().parent().attr("id");            
            showDialogForEditCustomer(customer_id);                     
        });   
        
//        $("body").delegate("td.edit_customer", "click", function() {
//            customer_id = $(this).attr("id");
//            showDialogForEditCustomer(customer_id);
//        });
        
    });
</script>