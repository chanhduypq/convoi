<div style="display: none;" id="dialog-modal-reason">
    <div class="error">
        Vui lòng nhập lý do phía dưới
    </div>
    <textarea rows="15" cols="40" placeholder="Nhập lý do tại đây"></textarea>  
    <li class="clearfix"></li>
</div>

<script type="text/javascript">

    function submit(type) {
        if (type == '1') {//user click button lưu
            $("form#update_bill").removeAttr("target");
            $("form#update_bill").attr("action", "<?php echo $this->createUrl("/thuchi/updategoods"); ?>");
            $("form#update_bill").submit();
        }   
        

    }
    
</script>