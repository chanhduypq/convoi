<div style="display: none;" id="dialog-modal-history">
    <div id="history">
        
    </div>
</div>
<script type="text/javascript"> 
    jQuery(function($) {
        $("body").delegate('.history', "click", function() {
            thuchi_id=$(this).parent().find('input[name="thuchi_id[]"]').eq(0).val();
            jQuery("#dialog-modal-history").dialog({
                title:'',
                create: function(event, ui) {
                  $("body").css({ overflow: 'hidden' });
                  jQuery("div.ui-dialog.ui-widget.ui-widget-content.ui-corner-all.ui-front.ui-draggable.ui-resizable").css('z-index','99999');
                 },
                 beforeClose: function(event, ui) {
                  $("body").css({ overflow: 'inherit' });
                 },

                position: ['top', 110],                
                height: 500,
                width: $(window).width(),
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                open: function(event, ui) {
                    $.ajax({ 
                        async: false,
                        cache: false,                                
                        url: '<?php echo $this->createUrl('/thuchi/gethistory/thuchi_id');?>/'+thuchi_id,            
                        success: function(data, textStatus, jqXHR) {
                            $("#history").html(data);
                        }
                    });
                }
            });              
        });
    });

</script>
