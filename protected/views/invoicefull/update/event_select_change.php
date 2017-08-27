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
            sum_node = $(unit_node).parent().parent().find(".pro-money").eq(0);
            sum_tax_node = $(unit_node).parent().parent().find(".pro-money").eq(1);            
            //        
            resetInput(quantity_node, price_has_tax_node,price_not_tax_node, sum_node, sum_tax_node, goods_group_id != "" ? true : false);
            setGoodCombobox(goods_group_id, unit_node, $(this).parent().parent().find(".tax").eq(0));
            setSumForHiddenInputs($(".div-pro1"),$('input[name="sum"]'),$('input[name="tax_sum"]'));
            setTong($(".div-pro1"),$("#sum_sum label"),$("#sum_sum_tax label"),$("#sum_sum_and_tax"));
            init_sum=$('input[name="sum"]').val();
            init_tax_sum=$('input[name="tax_sum"]').val();
        });   
        $("body").delegate("select.unit", "change", function() {
            node_tax=$(this).parent().next().next().next().next().find("input.tax").eq(0);
            goods_id=$(this).val();            
            quantity_node = $(this).parent().next().find("input").eq(0);
            price_has_tax_node = $(this).parent().next().next().find("input").eq(0);
            price_not_tax_node=$(price_has_tax_node).parent().next().find("input").eq(0);
            sum_node = $(this).parent().parent().find(".pro-money").eq(0);
            sum_tax_node = $(this).parent().parent().find(".pro-money").eq(1);        
            //
            $.ajax({ 
                async: false,
                cache: false,
                url: '<?php echo $this->createUrl("/ajax/getgoods"); ?>/id/' + goods_id,
                success: function(data, textStatus, jqXHR) { 
                    if($.trim(data)!=''){
                        data = $.parseJSON(data);
                        $(node_tax).val(data.tax);
                    }
                }

            });
            //        
            resetInput(quantity_node, price_has_tax_node,price_not_tax_node, sum_node, sum_tax_node, true);            
            setSumForHiddenInputs($(".div-pro1"),$('input[name="sum"]'),$('input[name="tax_sum"]'));
            setTong($(".div-pro1"),$("#sum_sum label"),$("#sum_sum_tax label"),$("#sum_sum_and_tax"));
            init_sum=$('input[name="sum"]').val();
            init_tax_sum=$('input[name="tax_sum"]').val();
        });  
    });
</script>