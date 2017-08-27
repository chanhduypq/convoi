<div style="display: none;" id="dialog-modal-reason">
    <div class="error">
        Vui lòng nhập lý do phía dưới
    </div>
    <textarea rows="15" cols="40" placeholder="Nhập lý do tại đây"></textarea>   
</div>
<script type="text/javascript"> 
    function submit() {
        $("form#update_bill").removeAttr("target");
        $("form#update_bill").attr("action", "<?php echo $this->createUrl("/internationalinput/update"); ?>");
        $("form#update_bill").submit();
    }
    function print(){
        if ($.trim($("#dialog-modal-reason textarea").val()) == "") {
            $("#dialog-modal-reason div").show();
            $("#dialog-modal-reason textarea").focus();
        }
        else {          
            jQuery("#dialog-modal-reason").dialog('close');
            submit();
        }
    }
</script>