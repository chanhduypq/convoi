<script type="text/javascript">
    jQuery(function($) {  
        $("#submit").click(function() {
            if(validate()==false){
                return;
            }
            
            
            
            jQuery("#dialog-modal-reason").dialog({
                create: function(event, ui) {
                    $("body").css({overflow: 'hidden'})
                },
                beforeClose: function(event, ui) {
                    $("body").css({overflow: 'inherit'});
                    $('form#update_bill input[name="reason"]').val($("#dialog-modal-reason textarea").val());                    
                    
                },
                position: ['top', 110],
                height: 300,
                width: 500,
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                open: function(event, ui) {
                    $("#dialog-modal-reason div").hide();
                    $("#dialog-modal-reason textarea").val('');
                    
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");                    
                },
                buttons: {
                    "<?php echo Yii::app()->params['text_for_button_save'];?>": print,
                    "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                      jQuery("#dialog-modal-reason").dialog('close');
                      $(".ui-dialog-buttonset").html('');
                    }
                } 
            });

        });


    })
</script>
<?php $this->renderPartial('//invoiceinputfull/update/popup_for_submit'); ?>