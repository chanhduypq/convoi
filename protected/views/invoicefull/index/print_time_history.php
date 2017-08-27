<div style="display: none;" id="dialog-modal-history-print">
    <div id="div_loading_customer_edit" style="display: none;position: absolute;z-index: 99999;">
        <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
    </div>
    <div id="content-history-print" class="list-table">        
    </div>
    
         
</div>
<script type="text/javascript"> 
    jQuery(function ($){
        
        $("#close_history").click(function() {
            jQuery("#dialog-modal-history-print").dialog('close');
        });
        
        function getTimePrintHistory(bill_id,print_type){
            $("#div_loading_customer_edit").show();
            $.ajax({ 
                async: false,
                cache: false,
                url: '<?php echo $this->createUrl('/ajax/gettimeprinthistory/bill_id'); ?>/' + bill_id+'/print_type/'+print_type,
                success: function(data, textStatus, jqXHR) {
                    $("#div_loading_customer_edit").hide();
                    $("#content-history-print").html(data); 
                }
            });
        }
        $("body").delegate(".p2.count_lien1.go", "click", function() {
           current_node=$(this).parent();
           
               jQuery("#dialog-modal-history-print").dialog({
                   title:'Thời gian đã in hóa đơn',
                    create: function(event, ui) {
                        $("body").css({overflow: 'hidden'});
                        $('.title-HD.sort').css('z-index','1');
                    },
                    beforeClose: function(event, ui) {
                        $("body").css({overflow: 'inherit'});                   
                    },
                    position: ['top', 110],
                    height: 300,
                    width: 500,
                    show: {effect: "slide", duration: 500},
                    hide: {effect: "slide", duration: 500},
                    modal: true,
                    buttons: [
                        {
                          text: "<?php echo Yii::app()->params['text_for_button_close'];?>",                          
                          click: function() {
                            $( this ).dialog( "close" );
                          }                          
                        }
                    ],
                    open: function(event, ui) {      
                        $(".ui-dialog-buttonset").find("button").eq(0).addClass("close");
                        href=$(current_node).parent().find("a").eq(0).attr("href");
                        href=href.split("/");
                        bill_id=href[href.length-1];
                        getTimePrintHistory(bill_id,1);
                    }
            });   
        });
        $("body").delegate(".p2.count_lien2.go", "click", function() {
           current_node=$(this).parent();
           
           jQuery("#dialog-modal-history-print").dialog({
               title:'Thời gian đã in hóa đơn',
                create: function(event, ui) {
                    $("body").css({overflow: 'hidden'});
                    $('.title-HD.sort').css('z-index','1');
                },
                beforeClose: function(event, ui) {
                    $("body").css({overflow: 'inherit'});                   
                },
                position: ['top', 110],
                height: 300,
                width: 500,
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
                    href=$(current_node).parent().find("a").eq(0).attr("href");
                    href=href.split("/");
                    bill_id=href[href.length-1];
                    getTimePrintHistory(bill_id,2);
                }
        });         


        });
        
    });
</script>