<script type="text/javascript">
    jQuery(function($) {        
        $("#submit").click(function() {            
            if(validate()==true){
                $("form#create_bill").removeAttr("target");
                $("form#create_bill").attr("action", "<?php echo $this->createUrl("/sxdvfull/create"); ?>");
                $("form#create_bill").submit();
            }
        });
        $("#print_bill1").click(function() {       
            if(validate()==true){
                $("head").append("<link href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>");
                $("head").append('<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font_roboto.css" />');
                $("#print").val('1');
                $("#print_loading").css({top:'50%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'}).show();
                <?php Yii::app()->session['url']=$this->createUrl('/sxdvfull/index/');?>
                $.ajax({ 
                    async: false,
                    cache: false,
                    type: "POST",
                    url: '<?php echo $this->createUrl('/sxdvfull/printandcreate1/'); ?>',
                    data: $("form#create_bill").serialize(),
                    success: function(data, textStatus, jqXHR) {
//                        var find = 'fi';
//                        var re = new RegExp(find, 'g');
//                        data = data.replace(re, '<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>');
//
//                        find = 'clear<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>';
//                        re = new RegExp(find, 'g');
//                        data = data.replace(re, 'clearfi');
                        
                        var iframe=document.createElement('iframe');
                        document.body.appendChild(iframe);
                        var iframedoc=iframe.contentDocument||iframe.contentWindow.document;
                        iframedoc.body.innerHTML=data;
                        html2canvas(iframedoc.body, {            
                            onrendered: function(canvas) {
                                var imgString = canvas.toDataURL("image/png");

                                $.ajax({ 
                                    async: false,
                                    cache: false,
                                    url: '<?php echo $this->createUrl('/ajax/print');?>',
                                    type: "POST",
                                    data: {content: imgString, bill_number: '<?php echo $bill_number;?>', lien: '1'},
                                    success: function(data, textStatus, jqXHR) {  
                                        window.location='<?php echo $this->createUrl('/sxdv/print');?>';

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
                $("head").append("<link href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>");
                $("head").append('<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font_roboto.css" />');
                $("#print").val('1');
                $("#print_loading").css({top:'50%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'}).show();
                <?php Yii::app()->session['url']=$this->createUrl('/sxdvfull/index/');?>
                $.ajax({ 
                    async: false,
                    cache: false,
                    type: "POST",
                    url: '<?php echo $this->createUrl('/sxdvfull/printandcreate2/'); ?>',
                    data: $("form#create_bill").serialize(),
                    success: function(data, textStatus, jqXHR) {
//                        var find = 'fi';
//                        var re = new RegExp(find, 'g');
//                        data = data.replace(re, '<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>');
//
//                        find = 'clear<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>';
//                        re = new RegExp(find, 'g');
//                        data = data.replace(re, 'clearfi');
                        
                        var iframe=document.createElement('iframe');
                        document.body.appendChild(iframe);
                        var iframedoc=iframe.contentDocument||iframe.contentWindow.document;
                        iframedoc.body.innerHTML=data;
                        html2canvas(iframedoc.body, {            
                            onrendered: function(canvas) {
                                var imgString = canvas.toDataURL("image/png");

                                $.ajax({ 
                                    async: false,
                                    cache: false,
                                    url: '<?php echo $this->createUrl('/ajax/print');?>',
                                    type: "POST",
                                    data: {content: imgString, bill_number: '<?php echo $bill_number;?>', lien: '2'},
                                    success: function(data, textStatus, jqXHR) {  
                                        window.location='<?php echo $this->createUrl('/sxdv/print');?>';

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