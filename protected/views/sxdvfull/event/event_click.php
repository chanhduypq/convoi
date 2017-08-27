<script type="text/javascript">
    jQuery(function($) {

        $("body").delegate("li.pronametitle", "click", function() {  
            cancelGoodsInBanHangPage($(this).parents(".div-margin.list"));
            init_sum=$('input[name="sum"]').val();
            init_tax_sum=$('input[name="tax_sum"]').val();
        });
        $("#add_new_hanghoa").click(function() {
            $("#div_loading_common").css({top:'50%',left:'50%',margin:'-'+($('#div_loading_common').height() / 2)+'px 0 0 -'+($('#div_loading_common').width() / 2)+'px'}).show();
            $.ajax({ 
                async: false,
                cache: false,
                url: '<?php echo $this->createUrl('/ajax/getallgoods1'); ?>',            
                success: function(data, textStatus, jqXHR) {
                    $("#div_loading_common").hide();
                    if($.trim(data)!=''){
                        $(data).insertBefore($("#add_new_hanghoa"));
                        $('.numeric').number(true);
                        setSumForHiddenInputs($(".div-pro1"),$('input[name="sum"]'),$('input[name="tax_sum"]'));
                        setTong($(".div-pro1"),$("#sum_sum label"),$("#sum_sum_tax label"),$("#sum_sum_and_tax"));
                        showGoodsOrderLabel($(".div-margin li.prostt input.cus-auto18-input"));
                        $("select.goods").eq($("select.goods").length-1).multiselect({
                            show: {effect: "slide", duration: 500},
                            hide: {effect: "slide", duration: 500},
                            noneSelectedText: "---Chọn hàng hóa---",
                            selectedText: "# Hàng hóa được chọn",
                        multiple:false
                        }).multiselectfilter();
                    }
                }
            });

        });
    });
</script>