<div style="display: none;" id="dialog-modal-customer">
    <div class="edit-KH" id="khachhang">
        
            <div id="div_loading_customer" style="display: none;position: absolute;z-index: 99999;">
                <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
            </div>           

            <li class="e_title">Tên hàng hóa</li>
            <li class="e_content1"><input placeholder="Tên đầy đủ" name="goods_full_name" type="text" value="" class="cus-auto18-input"></li>
            <li class="e_content2"><input placeholder="Viết tắt" name="goods_short_hand_name" type="text" value="" class="cus-auto18-input"></li>
            <li class="clearfix"></li>
            <div class="error goods_full_name"></div>            
            <div class="error goods_short_hand_name"></div>

            <li class="e_title"></li>
            <li class="e_content1" style="width: 250px;"><input placeholder="Đơn vị tính" name="unit_full_name" type="text" value="" class="cus-auto18-input"></li>            
            <li class="clearfix"></li>
            <div class="error unit_full_name"></div>            
            
            <div class="error goods_full_name_for_unique_validate"></div>
            
            <div id="tax_more" style="display: none;">
                <li class="e_title">Thuế tiêu thụ đặc biệt</li>
                <li class="e_content1">                
                    <input name="thue_tieu_thu_dac_biet" type="text" value="" class="cus-auto18-input numeric" id="tax_ttdb">
                </li>
                <li class="clearfix"></li>
                <div class="error thue_tieu_thu_dac_biet"></div>

                <li class="e_title">Thuế nhập khẩu</li>
                <li class="e_content1">
                    <input name="thue_nhap_khau" type="text" value="" class="cus-auto18-input numeric" id="tax_nk">
                </li>
                <li class="clearfix"></li>
                <div class="error thue_nhap_khau"></div>
            </div>
            
            <li class="e_title">Thuế VAT</li>
            <li class="e_content1"><input name="tax" type="text" value="" class="cus-auto18-input numeric" id="tax_vat"></li>
            <li class="clearfix"></li>
            <div class="error tax"></div>
            
            <h3 style="margin-top: 30px;background-color: #999999;border: 1px solid black;width: 50%;border-radius: 5px;padding: 10px;">Giá đã bán</h3>
            <div id="list-of-price" class="list-table">                
            </div>

            
            
            <input type="hidden" name="id"/>
        
    </div>
</div>

<script type="text/javascript">
    /**
     * hiển thị thông tin hàng hóa dc load từ db lên
     */
    function setInfo(goods_id){
        $.ajax({ 
            async: false,
            cache: false,                                
            url: '<?php echo $this->createUrl('/ajax/getgoods/id');?>/'+goods_id,            
            success: function(data, textStatus, jqXHR) {
                if($.trim(data)!=''){
                    data=$.parseJSON(data);
                    $('#khachhang input[name="unit_full_name"]').val(data.unit_full_name);
                    $('#khachhang input[name="price"]').val(data.price);
                    $('#khachhang input[name="goods_full_name"]').val(data.goods_full_name);
                    $('#khachhang input[name="goods_short_hand_name"]').val(data.goods_short_hand_name);                          
                    $('#khachhang input[name="tax"]').val(data.tax); 
                    if(data.is_international=='1'){
                        $("#tax_more").show();
                        $('#khachhang input[name="thue_tieu_thu_dac_biet"]').val(data.thue_tieu_thu_dac_biet); 
                        $('#khachhang input[name="thue_nhap_khau"]').val(data.thue_nhap_khau); 
                        $("#tax_vat").parent().prev().html('Thuế VAT');
                    }
                    $('#khachhang input[name="id"]').val(data.id);
                }
            }
        });
    }
    function setListOfPrice(goods_id){
        $.ajax({ 
            async: false,
            cache: false,               
            url: '<?php echo $this->createUrl('/ajax/getgoodspricelist/id');?>/'+goods_id,               
            success: function(data, textStatus, jqXHR) {
                if($.trim(data)==""){
                    $("#list-of-price").html('');   
                    $("#list-of-price").prev().hide();
                    $("#list-of-price").hide();
                }
                else{
                    $("#list-of-price").html(data);  
                    $("#list-of-price").prev().show();
                    $("#list-of-price").show();
                }
                         
                $("#div_loading_customer").hide();    
            }
        });
    }
    function saveCustomer() {
        $("#div_loading_customer").show();   
        $("div.error").html('').hide();
        /**
         * 
         */
        $.ajax({ 
            async: false,
            cache: false,                
            type: "POST",
            url: '<?php echo $this->createUrl('/goodsfull/savegoods/'); ?>',
            data: {
                unit_full_name:$('#khachhang input[name="unit_full_name"]').val(),                
                goods_full_name:$('#khachhang input[name="goods_full_name"]').val(),
                goods_short_hand_name:$('#khachhang input[name="goods_short_hand_name"]').val(), 
                tax:$('#khachhang input[name="tax"]').val(), 
                thue_tieu_thu_dac_biet:$('#khachhang input[name="thue_tieu_thu_dac_biet"]').val(),                    
                thue_nhap_khau:$('#khachhang input[name="thue_nhap_khau"]').val(),                   
                id:$('#khachhang input[name="id"]').val(),
                is_submit:'1'
            },
            success: function(data, textStatus, jqXHR) {     
                
                $("#div_loading_customer").hide();                
                if($.trim(data)!=""&&data.indexOf("Goods")!=-1){                    
                    data=$.parseJSON(data);
                    for (key in data) {                        
                        temp=key.replace("Goods_","");
                        selector="div.error."+temp;                        
                        $(selector).html(data[key]).show();
                    }
                    /**
                     * trong rules của model Goods không cấu hình validate cho thuế nhập khẩu và thuế tiêu thụ đặc biệt
                     * do đó array data se k có key Goods_thue_tieu_thu_dac_biet và Goods_thue_nhap_khau
                     * cho nên phai kiểm tra trực tiếp
                     */
                    if($('#khachhang input[name="thue_tieu_thu_dac_biet"]').val()==''){                            
                        $("div.error.thue_tieu_thu_dac_biet").html('Vui lòng nhập Thuế tiêu thụ đặc biệt').show();
                    }                          

                    if($('#khachhang input[name="thue_nhap_khau"]').val()==''){
                        $("div.error.thue_nhap_khau").html('Vui lòng nhập Thuế nhập khẩu').show();                        
                    }  
                }
                else{
                    /**
                     * trong rules của model Goods không cấu hình validate cho thuế nhập khẩu và thuế tiêu thụ đặc biệt
                     * do đó array data se k có key Goods_thue_tieu_thu_dac_biet và Goods_thue_nhap_khau
                     * cho nên phai kiểm tra trực tiếp
                     * nếu một trong hai thuế nhập khẩu và thuế tiêu thụ đặc biệt chưa được nhập thi tiếp tục báo lỗi và không đóng dialog
                     * tại controller cũng chưa lưu vào db được
                     */   
                    if($('#khachhang input[name="thue_tieu_thu_dac_biet"]').val()!=''&&$('#khachhang input[name="thue_nhap_khau"]').val()!=''){
                        jQuery("#dialog-modal-customer").dialog('close');        
                        submit_form_common('<?php echo $this->createUrl('/goodsfull/index');?>','<?php echo $this->createUrl("/ajax/search"); ?>'); 
                    }
                    else{
                        if($('#khachhang input[name="thue_tieu_thu_dac_biet"]').val()==''){                                
                            $("div.error.thue_tieu_thu_dac_biet").html('Vui lòng nhập Thuế tiêu thụ đặc biệt').show();
                        }                          

                        if($('#khachhang input[name="thue_nhap_khau"]').val()==''){
                            $("div.error.thue_nhap_khau").html('Vui lòng nhập Thuế nhập khẩu').show();                        
                        }  
                    }
                    
                    
                }
                
            }
        });
    }
    

    jQuery(function($) {
    
        function showDialogForEditGoods(goods_id){
            jQuery("#dialog-modal-customer").dialog({
                title:'Sửa thông tin hàng hóa',
                create: function(event, ui) {
                  $("body").css({ overflow: 'hidden' });
                  $('.title-HD.sort').css('z-index','1');
                 },
                 beforeClose: function(event, ui) {
                  $("body").css({ overflow: 'inherit' })
                 },
                position: ['top', 110],                
                height: 450,
                width: 900,
                
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                open: function(event, ui) {
                    $('div.error').html('').hide();
                    $("#list-of-price").html('');        
                    $("#div_loading_customer").show(); 
                    $("#tax_more").hide();
                    $("#tax_vat").parent().prev().html('Thuế VAT');
                    $('#khachhang input[name="thue_tieu_thu_dac_biet"]').val('');
                    $('#khachhang input[name="thue_nhap_khau"]').val('');
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                    /**
                     * hiển thị thông tin hàng hóa dc load từ db lên
                     */
                    setInfo(goods_id);
                    setListOfPrice(goods_id);
                            
                },
                buttons: {
                    "<?php echo Yii::app()->params['text_for_button_save'];?>": saveCustomer,
                    "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                      jQuery("#dialog-modal-customer").dialog('close');
                      $(".ui-dialog-buttonset").html('');
                    }
                }  
            });
            $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
            $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
        }
    
        
        $("body").delegate("td.edit_goods a", "click", function() {        
            goods_id=$(this).parent().parent().attr("id");            
            showDialogForEditGoods(goods_id);                     
        });       

//        $("body").delegate("td.edit_goods", "click", function() {        
//            goods_id=$(this).attr("id");            
//            showDialogForEditGoods(goods_id);                     
//        });
        
        
        $('#khachhang input').on('input',function(e){
            node=$(this);
                delay(function(){
                    name=$(node).attr("name");                                   
                    /**
                     * nếu user đang nhập thuế nhập khẩu hoặc thuế tiêu thụ đặc biệt
                     * thi validate trực tiếp mà k cần chạy ajax 
                     */
                    if(name=='thue_tieu_thu_dac_biet'||name=='thue_nhap_khau'){
                        $("div.error."+name).html('').hide();
                        if(name=='thue_tieu_thu_dac_biet'){
                            if($(this).val()==''){
                                $("div.error."+name).html('Vui lòng nhập Thuế tiêu thụ đặc biệt').show();
                            }                          
                        }
                        else if(name=='thue_nhap_khau'){
                            if($(this).val()==''){
                                $("div.error."+name).html('Vui lòng nhập Thuế nhập khẩu').show();                        
                            }                    
                        }
                        return;
                    }
                    if(name=="goods_full_name"||name=="unit_full_name"||name=="price"){                
                        $("div.error.goods_full_name_for_unique_validate").html('').hide();
                    }
                    $("div.error."+name).html('').hide();

                    $.ajax({ 
                        async: false,
                        cache: false,
                        type: "POST",
                        url: '<?php echo $this->createUrl('/goodsfull/savegoods/'); ?>',
                        data: {
                            unit_full_name:$('#khachhang input[name="unit_full_name"]').val(),                    
                            goods_full_name:$('#khachhang input[name="goods_full_name"]').val(),
                            goods_short_hand_name:$('#khachhang input[name="goods_short_hand_name"]').val(), 
                            tax:$('#khachhang input[name="tax"]').val(), 
                            id:$('#khachhang input[name="id"]').val()
                        },
                        success: function(data, textStatus, jqXHR) {

                            if($.trim(data)!=""&&data.indexOf("Goods")!=-1){                    
                                data=$.parseJSON(data);
                                for (key in data) {                                 
                                    temp=key.replace("Goods_","");
                                    selector="div.error."+temp; 
                                    if(selector=="div.error.goods_full_name_for_unique_validate"){
                                        if(name=="goods_full_name"||name=="unit_full_name"||name=="price"){ 
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
        
    });
</script>