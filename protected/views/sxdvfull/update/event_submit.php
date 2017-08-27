<script type="text/javascript">
    jQuery(function($) {
        function download(){
            if ($.trim($("#dialog-modal-reason textarea").val()) == "") {
                $("#dialog-modal-reason div").html("Vui lòng nhập lý do phía dưới").show();
                $("#dialog-modal-reason textarea").focus();
            }
            else {
                $("#print").val('-1');
                jQuery("#dialog-modal-reason").dialog('close');
                submit($("#edit_lien1_lien2").val());
            }
        }
        function print() {
            if ($.trim($("#dialog-modal-reason textarea").val()) == "") {
                $("#dialog-modal-reason div").html("Vui lòng nhập lý do phía dưới").show();
                $("#dialog-modal-reason textarea").focus();
            }
            else {
                $("#print").val('1');
                jQuery("#dialog-modal-reason").dialog('close');
                submit($("#edit_lien1_lien2").val());
            }
        }
        function preview() {
            if ($.trim($("#dialog-modal-reason textarea").val()) == "") {
                $("#dialog-modal-reason div").html("Vui lòng nhập lý do phía dưới").show();
                $("#dialog-modal-reason textarea").focus();
            }
            else {
                $("#print").val('0');
                jQuery("#dialog-modal-reason").dialog('close');
                submit($("#edit_lien1_lien2").val());
            }
        }
        $("#submit").click(function() {
            if (validate() == false) {
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
                height: 250,
                width: 500,
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                open: function(event, ui) {
                    $("#dialog-modal-reason div").hide();
                    $("#dialog-modal-reason textarea").val('');

                    $("#edit_lien1_lien2").val('1');
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                },
                buttons: {
                    "<?php echo Yii::app()->params['text_for_button_save']; ?>": print,
                    "<?php echo Yii::app()->params['text_for_button_close']; ?>": function() {
                        jQuery("#dialog-modal-reason").dialog('close');
                        $(".ui-dialog-buttonset").html('');
                    }
                }
            });

        });
        $("#print_bill1").click(function() { 
        
            if (validate() == false) {
                return;
            }
<?php
if ($count_print_lien1_histoty_date == 0) {
    ?> 
                $("#print").val('1');
                submit('2');

    <?php
} else {
    ?>

                jQuery("#dialog-modal-reason").dialog({
                    create: function(event, ui) {
                        $("body").css({overflow: 'hidden'})
                    },
                    beforeClose: function(event, ui) {
                        $("body").css({overflow: 'inherit'});
                        $('form#update_bill input[name="reason"]').val($("#dialog-modal-reason textarea").val());

                    },
                    position: ['top', 110],
                    height: 250,
                    width: 500,
                    show: {effect: "slide", duration: 500},
                    hide: {effect: "slide", duration: 500},
                    modal: true,
                    open: function(event, ui) {
                        $("#dialog-modal-reason div").hide();
                        $("#dialog-modal-reason textarea").val('');

                        $("#edit_lien1_lien2").val('2');
                        $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                        $(".ui-dialog-buttonset").find("button").eq(1).addClass("save");
                        
                    },
                    buttons: {
                        "In": print,
                        "Xem trước": preview
                    }
                });
    <?php
}
?>
        });
        $("#print_bill2").click(function() {
            if (validate() == false) {
                return;
            }
<?php
if ($count_print_lien2_histoty_date == 0) {
    ?>
                $("#print").val('1');
                submit('3');
    <?php
} else {
    ?>


                jQuery("#dialog-modal-reason").dialog({
                    create: function(event, ui) {
                        $("body").css({overflow: 'hidden'})
                    },
                    beforeClose: function(event, ui) {
                        $("body").css({overflow: 'inherit'});
                        $('form#update_bill input[name="reason"]').val($("#dialog-modal-reason textarea").val());

                    },
                    position: ['top', 110],
                    height: 250,
                    width: 500,
                    show: {effect: "slide", duration: 500},
                    hide: {effect: "slide", duration: 500},
                    modal: true,
                    open: function(event, ui) {
                        $("#dialog-modal-reason div").hide();
                        $("#dialog-modal-reason textarea").val('');

                        $("#edit_lien1_lien2").val('3');
                        $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                        $(".ui-dialog-buttonset").find("button").eq(1).addClass("save");
                        $(".ui-dialog-buttonset").find("button").eq(2).addClass("save");
                        
                    },
                    buttons: {
                        "In": print,
                        "Xem trước": preview,
                        "Download":download
                    }
                });
    <?php
}
?>
        });

    })
</script>
<?php 
$this->renderPartial('//sxdvfull/update/popup_for_submit', array('bill_number' => $bill_number)); 
?>