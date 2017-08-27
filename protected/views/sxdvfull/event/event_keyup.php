<div style="display: none;" id="dialog-modal-error">
</div>
<script type="text/javascript">
    jQuery(function($) {
        $("body").delegate(".numeric", "keyup", function() {

            
            if ($(this).hasClass("price_has_tax")) {//nếu user đang nhập đơn giá có thuế
                tax = $(this).parent().parent().find(".tax").eq(0).val();
                if($.trim(tax)==""){
                    tax='0';
                }
                if (tax.indexOf(".") != -1) {
                    tax = tax.split(".").join("");
                }
                tax = parseInt(tax);
                price = $(this).val();
                if($.trim(price)==""){
                    price='0';
                }


                if (price.indexOf(".") != -1) {
                    price = price.split(".").join("");
                }
                temp = price / ((100 + tax) / 100);
                price_not_tax_double = temp.toFixed(2);
                $(this).parent().next().find("input").eq(0).val(temp);

                price = $(this).parent().next().find("input").eq(0).val();
                if($.trim(price)==""){
                    price='0';
                }
                quantity = $(this).parent().parent().find("input[name='quantity[]']").eq(0).val();
                if($.trim(quantity)==""){
                    quantity='0';
                }
            }
            else if ($(this).hasClass("price_not_tax")) {//nếu user đang nhập đơn giá chưa thuế
                tax = $(this).parent().parent().find(".tax").eq(0).val();
                if($.trim(tax)==""){
                    tax='0';
                }
                if (tax.indexOf(".") != -1) {
                    tax = tax.split(".").join("");
                }
                tax = parseInt(tax);
                price = $(this).val();
                if($.trim(price)==""){
                    price='0';
                }

                if (price.indexOf(".") != -1) {
                    price = price.split(".").join("");
                }
                price_not_tax_double = price;
                price = price * ((100 + tax) / 100);



                $(this).parent().prev().find("input").eq(0).val(price);

                price = $(this).val();
                if($.trim(price)==""){
                    price='0';
                }
                quantity = $(this).parent().parent().find("input[name='quantity[]']").eq(0).val();
                if($.trim(quantity)==""){
                    quantity='0';
                }
            }
            else if ($(this).attr("name") == "quantity[]") {//nếu user đang nhập số lượng
                tax = $(this).parent().parent().find(".tax").eq(0).val();
                if($.trim(tax)==""){
                    tax='0';
                }
                
                if (tax.indexOf(".") != -1) {
                    tax = tax.split(".").join("");
                }
                tax = parseInt(tax);
                
                price = $(this).parent().parent().find(".price_has_tax").eq(0).val();
                if($.trim(price)==""){
                    price='0';
                }
                if (price.indexOf(".") != -1) {
                    price = price.split(".").join("");
                }
                temp = price / ((100 + tax) / 100);
                price_not_tax_double = temp.toFixed(2);
                quantity = $(this).val();      
                if($.trim(quantity)==""){
                    quantity='0';
                }
                
                
            }
            else if($(this).hasClass("tax")){//nếu user đang nhập thuế
                tax = $(this).val();
                if($.trim(tax)==""){
                    tax='0';
                }
                if (tax > 100)
                {
                    tax='100';
                    $(this).val('100');
                }
                if (tax.indexOf(".") != -1) {
                    tax = tax.split(".").join("");
                }
                tax = parseInt(tax);
                
                price = $(this).parent().parent().find(".price_has_tax").eq(0).val();
                if($.trim(price)==""){
                    price='0';
                }
                if (price.indexOf(".") != -1) {
                    price = price.split(".").join("");
                }
                temp = price / ((100 + tax) / 100);
                price_not_tax_double = temp.toFixed(2);
                
                $(this).parent().parent().find("input.price_not_tax").eq(0).val(price_not_tax_double);
                quantity = $(this).parent().parent().find("input[name='quantity[]']").eq(0).val();
                if($.trim(quantity)==""){
                    quantity='0';
                }
            }


            sum_node = $(this).parent().parent().find(".pro-money").eq(0);
            tax_sum_node = $(this).parent().parent().find(".pro-money").eq(1);
            
            setTienHangAndTax(quantity, price_not_tax_double, tax, sum_node, tax_sum_node);

            if ($(this).hasClass("price_has_tax")) {
                price_has_tax_node = $(this);
                quantity_node = $(this).parent().prev().find("input").eq(0);
            }
            else if ($(this).hasClass("price_not_tax")) {
                price_has_tax_node = $(this).parent().prev().find("input").eq(0);
                quantity_node = $(this).parent().prev().prev().find("input").eq(0);
            }
            else if ($(this).attr("name") == "quantity[]") {
                price_has_tax_node = $(this).parent().next().find("input").eq(0);
                quantity_node = $(this);
            }
            else if ($(this).hasClass("tax")) {
                price_has_tax_node = $(this).parent().parent().find("input.price_has_tax").eq(0);
                quantity_node = $(this).parent().parent().find("input[name='quantity[]']").eq(0);
            }
            <?php
            //nếu admin đang setting kiểu làm tròn
            if (Yii::app()->session['calculate_way'] == '1') {
                echo "editAutoTienHangAndTax(quantity_node, price_has_tax_node, sum_node, tax_sum_node, tax);";
            }
            ?>
            setSumForHiddenInputs($(".div-pro1"), $('input[name="sum"]'), $('input[name="tax_sum"]'));
            setTong($(".div-pro1"), $("#sum_sum label"), $("#sum_sum_tax label"), $("#sum_sum_and_tax"));
            init_sum=$('input[name="sum"]').val();
            init_tax_sum=$('input[name="tax_sum"]').val();
        });
    });
</script>