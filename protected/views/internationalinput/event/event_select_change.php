<script type="text/javascript">
    jQuery(function($) {  
        function check_exist(goods_group_id,goods_group_id_node){
            goods_group_node_array=$(".goods").not($(goods_group_id_node));
            for(i=0;i<goods_group_node_array.length;i++){
                if($(goods_group_node_array[i]).closest('select').find('option').filter(':selected:last').val()==goods_group_id){
                    jQuery("#dialog-modal-error").dialog({
                        title: 'Đã có tồn tại hàng hóa này rồi. Vui lòng chọn hàng hóa khác hoặc đơn vị khác',
                        create: function(event, ui) {
                            $("body").css({overflow: 'hidden'})
                        },
                        beforeClose: function(event, ui) {
                            $("body").css({overflow: 'inherit'})
                        },
                        position: ['top', 110],
                        height: 100,
                        width: 900,
                        show: {effect: "slide", duration: 500},
                        hide: {effect: "slide", duration: 500},
                        modal: true,
                        open: function(event, ui) {
                            $(".ui-dialog-buttonset").find("button").eq(0).addClass("close");
                        },
                        buttons: {
                            "Đóng": function() {
                                jQuery("#dialog-modal-error").dialog('close');
                                $(".ui-dialog-buttonset").html('');
                            }
                        }
                    });
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("close");
                    return;
                }
            }

        }
        $("body").delegate(".goods", "change", function() {
            
            goods_group_id=$(this).closest('select').find('option').filter(':selected:last').val();//$(this).val();            
            check_exist(goods_group_id,$(this));            
            unit_node=$(this).parent().parent().find(".unit").eq(0);
            quantity_node = $(unit_node).parent().next().find("input").eq(0);
            price_has_tax_node = $(unit_node).parent().next().next().find("input").eq(0);
            price_not_tax_node=$(price_has_tax_node).parent().next().find("input").eq(0);
            sum_node = $(unit_node).parent().parent().find(".pro-qtemoney-th").eq(0).find("input").eq(0);
               
            sum_tax_ttdb_node = $(unit_node).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_ttdb").eq(0);            
            sum_tax_nk_node = $(unit_node).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_nk").eq(0);            
            sum_tax_node = $(unit_node).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_vat").eq(0);            

            resetInput(quantity_node, price_has_tax_node,price_not_tax_node, sum_node, sum_tax_ttdb_node,sum_tax_nk_node,sum_tax_node, goods_group_id != "" ? true : false);
            setGoodCombobox(goods_group_id, unit_node, $(unit_node).parent().parent().find(".pro-qte-thueselect").eq(0).find('input.tax_ttdb').eq(0),$(unit_node).parent().parent().find(".pro-qte-thueselect").eq(0).find('input.tax_nk').eq(0),$(unit_node).parent().parent().find(".pro-qte-thueselect").eq(0).find('input.tax_vat').eq(0));            
            setSumForHiddenInputs($(".div-pro1"),$('input[name="sum"]'),$('input[name="tax_sum"]'));
            setTong($(".div-pro1"),$("#sum_sum label"),$("#sum_sum_tax label"),$("#sum_sum_and_tax"));
            set_gia_tri_thanh_toan_qua_ngan_hang();
            init_sum=$('input[name="sum"]').val();
            init_tax_sum=$('input[name="tax_sum"]').val();
        });  
        $("body").delegate("select.unit", "change", function() {
            
            node_tax_ttdb=$(this).parent().next().next().next().next().next().find("input.tax_ttdb").eq(0);
            node_tax_nk=$(this).parent().next().next().next().next().next().find("input.tax_nk").eq(0);
            node_tax=$(this).parent().next().next().next().next().next().find("input.tax_vat").eq(0);
            
            $(node_tax_ttdb).show();
            $(node_tax_nk).show();
            $(node_tax_ttdb).parent().prev().show();
            $(node_tax_nk).parent().prev().show();
            $(node_tax_ttdb).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li").show();
            
            goods_id=$(this).val();     
            quantity_node = $(this).parent().next().find("input").eq(0);
            price_has_tax_node = $(this).parent().next().next().find("input").eq(0);
            price_not_tax_node=$(price_has_tax_node).parent().next().find("input").eq(0);
            sum_node = $(this).parent().parent().find(".pro-qtemoney-th").eq(0).find("input").eq(0);
                  
            sum_tax_ttdb_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_ttdb").eq(0);            
            sum_tax_nk_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_nk").eq(0);            
            sum_tax_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_vat").eq(0); 

            $.ajax({ 
                async: false,
                cache: false,
                url: '<?php echo $this->createUrl("/ajax/getgoods"); ?>/id/' + goods_id,
                success: function(data, textStatus, jqXHR) {   
                    if($.trim(data)!=''){
                        data = $.parseJSON(data);
                        flag=false;
                        if(data.thue_tieu_thu_dac_biet=='0'){
                            flag=true;
                            $(node_tax_ttdb).hide();
                            $(node_tax_ttdb).parent().prev().hide();

                            $(node_tax_ttdb).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li.li-left").eq(0).hide();
                            $(node_tax_ttdb).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li.li-right").eq(0).hide();
                        }
                        else{
                            $(node_tax_ttdb).val(data.thue_tieu_thu_dac_biet);
                        }
                        if(data.thue_nhap_khau=='0'){
                            $(node_tax_nk).hide();
                            $(node_tax_nk).parent().prev().hide();
                            
                            if(flag==false){
                                index=1;
                            }
                            else{
                                index=0;
                            }
                            $(node_tax_nk).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li.li-left").eq(1).hide();
                            $(node_tax_nk).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li.li-right").eq(1).hide();
                        }
                        else{
                            $(node_tax_nk).val(data.thue_nhap_khau);
                        } 
                        $(node_tax).val(data.tax);
                    }
                }

            });
            //        
            resetInput(quantity_node, price_has_tax_node,price_not_tax_node, sum_node, sum_tax_ttdb_node,sum_tax_nk_node,sum_tax_node, true);            
            setSumForHiddenInputs($(".div-pro1"),$('input[name="sum"]'),$('input[name="tax_sum"]'));
            setTong($(".div-pro1"),$("#sum_sum label"),$("#sum_sum_tax label"),$("#sum_sum_and_tax"));
            set_gia_tri_thanh_toan_qua_ngan_hang();
            init_sum=$('input[name="sum"]').val();
            init_tax_sum=$('input[name="tax_sum"]').val();
        });  
    });
</script>