<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common/function.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/invoice/create/function.js"></script>
<script type="text/javascript">
    function editAutoTienHangAndTax(quantity_node, price_has_tax_node, sum_node, tax_sum_node, tax) {
        if(tax=='/'){
            tax=0;
        }
        quantity = $(quantity_node).val() + "";
        price_has_tax = $(price_has_tax_node).val() + "";
        sum = $(sum_node).val() + "";
        tax_sum = $(tax_sum_node).val() + "";
        /**
         * 
         */
        if (quantity.indexOf(".") != -1) {
            quantity = quantity.split(".").join("");
        }
        if (price_has_tax.indexOf(".") != -1) {
            price_has_tax = price_has_tax.split(".").join("");
        }
        if (sum.indexOf(".") != -1) {
            sum = sum.split(".").join("");
        }
        if (tax_sum.indexOf(".") != -1) {
            tax_sum = tax_sum.split(".").join("");
        }
        /**
         * 
         */
        quantity = parseInt(quantity);
        price_has_tax = parseInt(price_has_tax);
        //new
        price_not_tax = price_has_tax / ((tax + 100) / 100);
        price_not_tax = price_not_tax.toFixed(2);
        //end new
        sum = parseInt(sum);
        tax_sum = parseInt(tax_sum);
        tax = parseInt(tax);
        hieu = Math.abs(quantity * price_has_tax - (sum + tax_sum));

        if (hieu == 0) {
            return;
        }

        if (quantity * price_has_tax > sum + tax_sum) {//tăng thuế lên
            tax_sum += hieu;
            tax_sum = numberWithCommas(tax_sum);
            $(tax_sum_node).val(tax_sum);
            
        }
        else {//giảm tiền hàng xuống
            sum -= hieu;
            sum = numberWithCommas(sum);
            $(sum_node).val(sum);

            

        }
    }
    function setTienHangAndTax(quantity, price, tax, sum_node, tax_sum_node) {
        if(tax=='/'){
            tax=0;
        }

        /**
         * bỏ dấu phẩy và dấu chấm trong chuỗi số để cộng trừ nhân chia với số
         */
        if (quantity.indexOf(".") != -1) {
            quantity = quantity.split(".").join("");
        }
        //    if (price.indexOf(".") != -1) {
        //        price = price.split(".").join("");
        //    }


        /**
         *          
         */
        sum = quantity * price;
        tax_sum = sum * tax / 100;
        sum = sum.toFixed(0);
        tax_sum = tax_sum.toFixed(0);
        sum = numberWithCommas(sum);
        //    if (sum[sum.length - 1] == '9') {
        //        tax_sum = Math.ceil(tax_sum);
        //    }
        //    else {
        //        tax_sum = Math.floor(tax_sum);
        //    }





        $(sum_node).val(sum);
        tax_sum = numberWithCommas(tax_sum);
        $(tax_sum_node).val(tax_sum);


    }
    jQuery(function($) {
        if ($("li.li-address").css('height') == '50px') {
            $("li.li-mst").css("padding-top", "0px");
        }
        quantities = $("input[name='sl']");

        for (i = 0; i < quantities.length; i++) {
            tax = $(quantities[i]).parent().next().find("input").eq(0).val();
            if(tax=='/'){
                tax=0;
            }
            tax = parseInt(tax);
            sum_node = $(quantities[i]).parent().next().next().next().find("input").eq(0);
            tax_sum_node = $(quantities[i]).parent().next().next().next().next().find("input").eq(0);
            price_has_tax_node = $(quantities[i]).parent().parent().next();
            //
            price = $(price_has_tax_node).val();

            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            price_not_tax_double = temp.toFixed(2);
            //
            setTienHangAndTax($(quantities[i]).val(), price_not_tax_double, tax, sum_node, tax_sum_node);
            editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_sum_node, tax);



        }
        html2canvas($('body'), {            
            onrendered: function(canvas) {
                var imgString = canvas.toDataURL("image/png");
                
                $.ajax({ 
                    async: false,
                    cache: false,
                    url: '<?php echo $this->createUrl('/ajax/print');?>',
                    type: "POST",
                    data: {content: imgString, bill_number: '<?php echo $bill_number; ?>', lien: '<?php echo $lien; ?>'},
                    success: function(data, textStatus, jqXHR) {
                        <?php 
                        if($print==true){
                        ?>
                        myWindow=window.open('<?php echo $this->createUrl('/sxdv/print');?>',"_blank");                         
                        <?php 
                        }
                        else{
                        ?>
                        myWindow=window.open('<?php echo $this->createUrl('/sxdv/preview');?>',"_blank");  
                        <?php 
                        }                        
                        ?>
                        myWindow.focus();
                        <?php 
                        if($print==true){
                        ?>
                        myWindow.print();

                        
                        <?php 
                        }                        
                        ?>
                        
                        
                        
                        window.close();
//                        window.open(data,"_self");   
                        

                    }
                });

            }
        });
    });
</script>