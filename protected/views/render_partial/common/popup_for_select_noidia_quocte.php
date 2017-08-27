<div id="dialog-modal-noidia_quocte" style="display: none;">
    <div class="edit-KH1">
        <label class="noidia_quocte" style="cursor: pointer;">
            <input type="radio" name="noidia_quocte" value="<?php echo Branch::NOI_DIA; ?>"/>
            Nội địa
        </label>
        <label class="noidia_quocte" style="cursor: pointer;">
            <input type="radio" name="noidia_quocte" value="<?php echo Branch::QUOC_TE; ?>"/>
            Quốc tế
        </label>

    </div>
    <div class="error" id="error_select" style="margin-top: 20px;display: none;margin-left: 0px;">Vui lòng chọn một trong hai</div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        var noidia_quocte;
        
        $('.noidia_quocte').click(function() {
            noidia_quocte = $(this).find('input').eq(0).val();
        });
        

        
        $("#add_goods").click(function() {

            jQuery("#dialog-modal-noidia_quocte").dialog({
                title: 'Chọn nơi mua hàng',
                create: function(event, ui) {
                    $("body").css({overflow: 'hidden'});
                    $('.title-HD.sort').css('z-index','1');
                    
                },
                beforeClose: function(event, ui) {
                    $("body").css({overflow: 'inherit'});
                    jQuery("#dialog-modal-noidia_quocte").hide();
                },
                open: function(event, ui) {
                    $("div#error_select").hide();
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                },
                position: ['top', 110],
                height: 300,
                width: 400,
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                buttons: {
                    "Đồng ý": go_to_page,
                    "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                        jQuery("#dialog-modal-noidia_quocte").dialog('close');
                        $(".ui-dialog-buttonset").html('');
//                        jQuery("#dialog-modal-noidia_quocte").hide();
                    }
                }  
            });

        });
        function go_to_page(){
            if (noidia_quocte == '<?php echo Branch::NOI_DIA; ?>') {
                window.location = "<?php echo $this->createUrl('/invoiceinputfull/create'); ?>";
            }
            else if (noidia_quocte == '<?php echo Branch::QUOC_TE; ?>') {
                window.location = "<?php echo $this->createUrl('/internationalinput/create'); ?>";
            }
            else {
                $("div#error_select").show();
//                jQuery("#dialog-modal-noidia_quocte").dialog('close');
//                jQuery("#dialog-modal-noidia_quocte").hide();
            }
        }
        
    });
</script>