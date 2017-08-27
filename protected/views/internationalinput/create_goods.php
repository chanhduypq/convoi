<div style="display: none;" id="dialog-modal-goods">
    <div class="edit-KH" id="hanghoa">
        <form id="form_goods" method="POST">
            <div id="div_loading_goods" style="display: none;position: absolute;z-index: 99999;">
                <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
            </div>
            

            <li class="e_title">Tên hàng hóa</li>
            <li class="e_content1"><input placeholder="Tên đầy đủ" name="goods_full_name" type="text" value="" class="cus-auto18-input"></li>
            <li class="e_content2"><input placeholder="Viết tắt" name="goods_short_hand_name" type="text" value="" class="cus-auto18-input"></li>
            <li class="clearfix"></li>
            <div class="error goods_full_name"></div>
            <div class="error goods_short_hand_name"></div>

            <li class="e_title"></li>
            <li class="e_content1 width-dvt"><input placeholder="Đơn vị tính" name="unit_full_name" type="text" value="" class="cus-auto18-input"></li>            
            <li class="clearfix"></li>           
            <div class="error unit_full_name"></div>
            <div class="error goods_full_name_for_unique_validate"></div>
            


            <li class="e_title">Thuế tiêu thụ đặc biệt</li>
            <li class="e_content1">                
                <input name="thue_tieu_thu_dac_biet" type="text" value="" class="cus-auto18-input numeric1" id="tax_ttdb">
            </li>
            <li class="clearfix"></li>
            <div class="error thue_tieu_thu_dac_biet"></div>

            <li class="e_title">Thuế nhập khẩu</li>
            <li class="e_content1">
                <input name="thue_nhap_khau" type="text" value="" class="cus-auto18-input numeric1" id="tax_nk">
            </li>
            <li class="clearfix"></li>
            <div class="error thue_nhap_khau"></div>
            
            <li class="e_title">Thuế VAT</li>
            <li class="e_content1">
                <input name="tax" type="text" value="" class="cus-auto18-input numeric1" id="tax_vat">
            </li>
            <li class="clearfix"></li>
            <div class="error tax"></div>
        </form>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($) {
        $(".numeric1").number(true);
        
        $('#hanghoa input').on('input',function(e){
            node=$(this);
                delay(function(){
                    name=$(node).attr("name");                                   
                    $("div.error."+name).html('').hide();
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
                    if(name=="goods_full_name"||name=="unit_full_name"){                
                        $("div.error.goods_full_name_for_unique_validate").html('').hide();
                    }


                    $.ajax({ 
                        async: false,
                        cache: false,
                        type: "POST",
                        url: '<?php echo $this->createUrl('/goodsfull/creategoodsimport/'); ?>',
                        data: {
                            unit_full_name:$('input[name="unit_full_name"]').val(),                    
                            tax:$('input[name="tax"]').val(),                    
                            thue_tieu_thu_dac_biet:$('input[name="thue_tieu_thu_dac_biet"]').val(),                    
                            thue_nhap_khau:$('input[name="thue_nhap_khau"]').val(),                    
                            goods_full_name:$('input[name="goods_full_name"]').val(),
                            goods_short_hand_name:$('input[name="goods_short_hand_name"]').val()
                        },
                        success: function(data, textStatus, jqXHR) {

                            if($.trim(data)!=""&&data.indexOf("Goods")!=-1){                    
                                data=$.parseJSON(data);
                                for (key in data) {                                 
                                    temp=key.replace("Goods_","");
                                    selector="div.error."+temp; 
                                    if(selector=="div.error.goods_full_name_for_unique_validate"){
                                        if(name=="goods_full_name"||name=="unit_full_name"){        
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
        

        

        $("#add_goods").click(function() {

            jQuery("#dialog-modal-goods").dialog({
                title:'Thêm thông tin hàng hóa',
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
                    $('div.error').html('').hide();
                    $("form#form_goods").trigger('reset');
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                },
                buttons: {
                    "<?php echo Yii::app()->params['text_for_button_save'];?>": saveGoods,
                    "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                      jQuery("#dialog-modal-goods").dialog('close');
                      $(".ui-dialog-buttonset").html('');
                    }
                }  
            });
        });

        

        function saveGoods() {
            $("#div_loading_goods").show();
            $("div.error").html('').hide();
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/goodsfull/creategoodsimport'); ?>',
                data: {
                    unit_full_name:$('input[name="unit_full_name"]').val(),                    
                    tax:$('input[name="tax"]').val(),         
                    thue_tieu_thu_dac_biet:$('input[name="thue_tieu_thu_dac_biet"]').val(),                    
                    thue_nhap_khau:$('input[name="thue_nhap_khau"]').val(),
                    goods_full_name:$('input[name="goods_full_name"]').val(),
                    goods_short_hand_name:$('input[name="goods_short_hand_name"]').val(),
                    is_submit:'1'
                },
                success: function(data, textStatus, jqXHR) {                    
                    $("#div_loading_goods").hide();                    
                    if(data.indexOf("Goods")!=-1){                    
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
                        if($('input[name="thue_tieu_thu_dac_biet"]').val()==''){                            
                            $("div.error.thue_tieu_thu_dac_biet").html('Vui lòng nhập Thuế tiêu thụ đặc biệt').show();
                        }                          

                        if($('input[name="thue_nhap_khau"]').val()==''){
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
                        if($('input[name="thue_tieu_thu_dac_biet"]').val()!=''&&$('input[name="thue_nhap_khau"]').val()!=''){
                            jQuery("#dialog-modal-goods").dialog('close');
                            goods_group_id=$.trim(data);                        
                            appendGoodsIntoGoodsCombobox(goods_group_id);
                            $(".ui-dialog-buttonset").html('');
                        }
                        else{
                            if($('input[name="thue_tieu_thu_dac_biet"]').val()==''){                                
                                $("div.error.thue_tieu_thu_dac_biet").html('Vui lòng nhập Thuế tiêu thụ đặc biệt').show();
                            }                          

                            if($('input[name="thue_nhap_khau"]').val()==''){
                                $("div.error.thue_nhap_khau").html('Vui lòng nhập Thuế nhập khẩu').show();                        
                            }  
                        }
                        
                        
                    }


                }
            });
        }

        function appendGoodsIntoGoodsCombobox(goods_group_id) {
            $.ajax({ 
                async: false,
                cache: false,
                url: '<?php echo $this->createUrl('/ajax/getgoods'); ?>/goods_group_id/'+goods_group_id,
                success: function(data, textStatus, jqXHR) {   
                    if($.trim(data)!=''){
                        data = $.parseJSON(data);                        
                        option = "<option value='" + data.group_id + "'>" + data.goods_full_name + "</option>";
                        selects = $("select.goods.cus-auto18-input");  
                        flag=$(selects[0]).find("option[value='"+data.group_id+"']").length==0;
                        for (ij = 0,n1=selects.length; ij < n1; ij++) { 
                            if(flag){  
                                val1=$(selects[ij]).val();
                                text=$(selects[ij]).next().find("span").eq(1).html();
                                $(selects[ij])
                                 .append($("<option></option>")
                                 .attr("value",data.group_id)
                                 .text(data.goods_full_name));                           
                                li_node=$(selects[ij]).parent();
                                 $(li_node).append("<select class='goods cus-auto18-input' multiple='multiple'>"+$(selects[ij]).html().replace(' selected="selected"','')+"</select>");
                                 $(selects[ij]).next().remove();
                                $(selects[ij]).remove();
                                
                                 $(li_node).find("select").eq(0).multiselect({
                                    show: {effect: "slide", duration: 500},
                                    hide: {effect: "slide", duration: 500},
                                    noneSelectedText: "---Chọn hàng hóa---",
                                    selectedText: "# Hàng hóa được chọn",
                                    multiple:false
                                }).multiselectfilter();       
                                $(li_node).find("select").eq(0).val(val1);
                                $(li_node).find("select").eq(0).next().find("span").eq(1).html(text);                               
                               
                            }
                             
                            
                        }
                        

                    }
                }
            });
        }



    });

</script>