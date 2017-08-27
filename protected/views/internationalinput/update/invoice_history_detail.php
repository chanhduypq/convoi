<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/multiselect/jquery-ui.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/multiselect/jquery-ui.min.js"></script>
<div style="display: none;" id="dialog-modal-history-update">
    <div id="content1">
    </div>

</div>
<script type="text/javascript">
    function getInfoUpdate(bill_history_id) {
        $.ajax({ 
            async: false,
            cache: false,
            url: '<?php echo Yii::app()->baseUrl . "/ajax/getbillimporthistoryupdate/id/"; ?>' + bill_history_id,
            success: function(data, textStatus, jqXHR) {
                $("#dialog-modal-history-update #content1").html(data);
            }
        });
    }
    
    jQuery(function($) {
        $("div.link").click(function() {
            bill_history_id = $(this).attr("id");
            
            getInfoUpdate(bill_history_id);
            jQuery("#dialog-modal-history-update").dialog({
                create: function(event, ui) {
                    $("body").css({overflow: 'hidden'})
                },
                beforeClose: function(event, ui) {
                    $("body").css({overflow: 'inherit'})
                },
                position: ['top', 110],
                height: 500,
                width: 1200,
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                buttons: [
                    {
                      text: "<?php echo Yii::app()->params['text_for_button_close'];?>",                          
                      click: function() {
                        $( this ).dialog( "close" );
                        $(".ui-dialog-buttonset").html('');
                      }                          
                    }
                ],
                open: function(event, ui) {
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("close");
                    quantities = $("#content1").find("input[name='quantity[]']");

                    for (i = 0; i < quantities.length; i++) {
                        tax_ttdb = $(quantities[i]).parent().parent().find(".tax").eq(0).val();
                        tax_nk = $(quantities[i]).parent().parent().find(".tax").eq(1).val();
                        tax = $(quantities[i]).parent().parent().find(".tax").eq(2).val();           
                        if(tax=='/'){
                            tax=0;
                        }
                        tax = parseInt(tax);
                        if(tax_ttdb=='/'){
                            tax_ttdb=0;
                        }
                        tax_ttdb = parseInt(tax_ttdb);
                        if(tax_nk=='/'){
                            tax_nk=0;
                        }
                        tax_nk = parseInt(tax_nk);
                        sum_node = $(this).parent().parent().find(".pro-qtemoney-th").eq(0);
                        tax_ttdb_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.li-right").eq(0);            
                        tax_nk_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.li-right").eq(1);            
                        tax_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.li-right").eq(2); 
                        price_has_tax_node = $(quantities[i]).parent().next().next().find("input").eq(0);
                        //
                        price = $(price_has_tax_node).val();
                        if (price.indexOf(".") != -1) {
                            price = price.split(".").join("");
                        }
                        temp = price / ((100 + tax) / 100);
                        price_not_tax_double=temp.toFixed(2);
                        //
                        setTienHangAndTax($(quantities[i]).val(), price_not_tax_double, tax_ttdb,tax_nk,tax, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node);
                        <?php
                        if(Yii::app()->session['calculate_way']=='1'){
                            echo "editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node, tax);";
                        }
                        ?>  
                        

                    }

                }
            });
            
            
        });
    });
</script>