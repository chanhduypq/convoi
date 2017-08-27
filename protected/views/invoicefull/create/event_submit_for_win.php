<script type="text/javascript">
    jQuery(function($) {        
        $("#submit").click(function() {            
            if(validate()==true){
                $("form#create_bill").removeAttr("target");
                $("form#create_bill").attr("action", "<?php echo $this->createUrl("/invoicefull/create"); ?>");
                $("form#create_bill").submit();
            }
        });
        $("#print_bill1").click(function() {       
            if(validate()==true){
                $("#print").val('1');
                $("#print_loading").css({top:'50%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'}).show();
                <?php Yii::app()->session['url']=$this->createUrl('/invoicefull/index/');?>
                $.ajax({ 
                    async: false,
                    cache: false,
                    type: "POST",
                    url: '<?php echo $this->createUrl('/invoicefull/printandcreate1/'); ?>',
                    data: $("form#create_bill").serialize(),
                    success: function(data, textStatus, jqXHR) {
                        $("head").html('');
                        $("body").html(data);
                        html2canvas($("body"), {        
                            onrendered: function(canvas) {
                                var imgString = canvas.toDataURL("image/png");

                                $.ajax({ 
                                    async: false,
                                    cache: false,
                                    url: '<?php echo $this->createUrl('/ajax/print');?>',
                                    type: "POST",
                                    data: {content: imgString, bill_number: '<?php echo $bill_number;?>', lien: '1'},
                                    success: function(data, textStatus, jqXHR) {  
                                        window.location='<?php echo $this->createUrl('/invoice/print');?>';

                                    }
                                });

                            }
                        });                    
                    }
                });
            }
        });
        $("#print_bill2").click(function() {       
            if(validate()==true){
                $("#print").val('1');
                $("#print_loading").css({top:'50%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'}).show();
                <?php Yii::app()->session['url']=$this->createUrl('/invoicefull/index/');?>
                $.ajax({ 
                    async: false,
                    cache: false,
                    type: "POST",
                    url: '<?php echo $this->createUrl('/invoicefull/printandcreate2/'); ?>',
                    data: $("form#create_bill").serialize(),
                    success: function(data, textStatus, jqXHR) {
                        $("head").html('');
                        $("body").html(data);
                        html2canvas($("body"), {            
                            onrendered: function(canvas) {
                                var imgString = canvas.toDataURL("image/png");

                                $.ajax({ 
                                    async: false,
                                    cache: false,
                                    url: '<?php echo $this->createUrl('/ajax/print');?>',
                                    type: "POST",
                                    data: {content: imgString, bill_number: '<?php echo $bill_number;?>', lien: '2'},
                                    success: function(data, textStatus, jqXHR) {  
                                        window.location='<?php echo $this->createUrl('/invoice/print');?>';

                                    }
                                });

                            }
                        });                    
                    }
                });
            }
        });

        
        

        

        



    })
</script>