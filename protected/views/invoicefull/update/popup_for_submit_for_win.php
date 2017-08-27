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
            $("form#update_bill").attr("action", "<?php echo $this->createUrl("/invoicefull/update"); ?>");
            $("form#update_bill").submit();
        }
        else if (type == '2') {//user click button hóa đơn liên 1
            $("#print_loading").css({top: '50%', left: '50%', margin: '-' + ($('#myDiv').height() / 2) + 'px 0 0 -' + ($('#myDiv').width() / 2) + 'px'}).show();
<?php Yii::app()->session['url'] = Yii::app()->request->url; ?>
            $.ajax({
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/invoicefull/updateandprint1/'); ?>',
                data: $("form#update_bill").serialize(),
                success: function(data, textStatus, jqXHR) {
                    pr=$("#print").val();
                    $("head").html('');
                    $("body").html(data);
                    html2canvas($("body"), { 
                        onrendered: function(canvas) {
                            var imgString = canvas.toDataURL("image/png");

                            $.ajax({
                                async: false,
                                cache: false,
                                url: '<?php echo $this->createUrl('/ajax/print'); ?>',
                                type: "POST",
                                data: {content: imgString, bill_number: '<?php echo $bill_number; ?>', lien: '1'},
                                success: function(data, textStatus, jqXHR) {
                                    if (pr == '1') {
                                        window.location = '<?php echo $this->createUrl('/invoice/print'); ?>';
                                    }
                                    else {
                                        window.location = '<?php echo $this->createUrl('/invoice/preview'); ?>';
                                    }

                                }
                            });

                        }
                    });
                }
            });
        }
        else if (type == '3') {//user click button hóa đơn liên 2
            $("#print_loading").css({top: '50%', left: '50%', margin: '-' + ($('#myDiv').height() / 2) + 'px 0 0 -' + ($('#myDiv').width() / 2) + 'px'}).show();
<?php Yii::app()->session['url'] = Yii::app()->request->url; ?>
            $.ajax({
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/invoicefull/updateandprint2/'); ?>',
                data: $("form#update_bill").serialize(),
                success: function(data, textStatus, jqXHR) {
                    pr=$("#print").val();
                    $("head").html('');
                    $("body").html(data);
                    html2canvas($("body"), { 
                        onrendered: function(canvas) {
                            var imgString = canvas.toDataURL("image/png");

                            $.ajax({
                                async: false,
                                cache: false,
                                url: '<?php echo $this->createUrl('/ajax/print'); ?>',
                                type: "POST",
                                data: {content: imgString, bill_number: '<?php echo $bill_number; ?>', lien: '2'},
                                success: function(data, textStatus, jqXHR) {
                                    if (pr == '1') {
                                        window.location = '<?php echo $this->createUrl('/invoice/print'); ?>';
                                    }
                                    else if(pr=='0'){
                                        window.location='<?php echo $this->createUrl('/invoice/preview');?>';
                                    }
                                    else{
                                        window.location='<?php echo $this->createUrl('/invoice/download');?>';
                                        $("#print_loading").hide();
                                        $(iframe).remove();
                                    }

                                }
                            });

                        }
                    });
                }
            });
        }
        

    }
    
</script>
<style>
button.ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-icon-only.ui-dialog-titlebar-close{
    display: block !important;
}
</style>