<script type="text/javascript">
    function setTienHangAndTax1(quantity, price_not_tax, tax, sum_node, tax_sum_node) {
        if(tax=='/'){
            tax=0;
        }
        /**
         * bỏ dấu phẩy và dấu chấm trong chuỗi số để cộng trừ nhân chia với số
         */
        if (quantity.indexOf(".") != -1) {
            quantity = quantity.split(".").join("");
        }
        /**
         *          
         */
        sum = quantity * price_not_tax;
        tax_sum = sum * tax / 100;
        sum = sum.toFixed(0);
        tax_sum = tax_sum.toFixed(0);
        sum = numberWithCommas(sum);
        $(sum_node).val(sum);
        tax_sum = numberWithCommas(tax_sum);
        $(tax_sum_node).val(tax_sum);


    }    
    function editMoney(){

        if ($("li.li-address").css('height') == '50px') {
                $("li.li-mst").css("padding-top", "0px");
        }
        quantities = $("#a").contents().find("input[name='sl']");
        for (i = 0; i < quantities.length; i++) {
            tax = $(quantities[i]).parent().next().next().next().find("input").eq(0).val();
            if(tax=='/'){
                tax=0;
            }
            tax = parseInt(tax);
            sum_node = $(quantities[i]).parent().next().next().find("input").eq(0);
            tax_sum_node = $(quantities[i]).parent().next().next().next().next().find("input").eq(0);
            price_has_tax_node = $(quantities[i]).parent().parent().next();
            //
            price = $(price_has_tax_node).val();
            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            price_not_tax_double = temp.toFixed(2);
            setTienHangAndTax1($(quantities[i]).val(), price_not_tax_double, tax, sum_node, tax_sum_node);
            <?php
            if(Yii::app()->session['calculate_way']=='1'){
                echo "editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_sum_node, tax);";
            }
            ?>


        } 

    }
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
                $("head").append("<link href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>");
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
                        var iframe=document.createElement('iframe');
                        iframe.setAttribute("id","a");
                        document.body.appendChild(iframe);
                        var iframedoc=iframe.contentDocument||iframe.contentWindow.document;
                        iframedoc.body.innerHTML=data;
                        editMoney();
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
                $("head").append("<link href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>");
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
                        var iframe=document.createElement('iframe');
                        iframe.setAttribute("id","a");
                        document.body.appendChild(iframe);
                        var iframedoc=iframe.contentDocument||iframe.contentWindow.document;
                        iframedoc.body.innerHTML=data;
                        editMoney();
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