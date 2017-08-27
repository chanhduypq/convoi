<script type="text/javascript">
jQuery(function($) {
    $("body").delegate(".numeric", "keyup", function() {

        tax = $(this).parent().parent().find(".tax").eq(0).val();
        tax = parseInt(tax);
        if ($(this).hasClass("price_has_tax")) {
            price = $(this).val();



            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            price_not_tax_double = temp.toFixed(2);
            $(this).parent().next().find("input").eq(0).val(temp);

            price = $(this).parent().next().find("input").eq(0).val();
            quantity = $(this).parent().parent().find("input[name='quantity[]']").eq(0).val();
        }
        else if ($(this).hasClass("price_not_tax")) {
            price = $(this).val();

            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            price_not_tax_double = price;
            price = price * ((100 + tax) / 100);



            $(this).parent().prev().find("input").eq(0).val(price);

            price = $(this).val();
            quantity = $(this).parent().parent().find("input[name='quantity[]']").eq(0).val();
        }
        else if ($(this).attr("name") == "quantity[]") {
            price = $(this).parent().parent().find(".price_has_tax").eq(0).val();
            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            price_not_tax_double = temp.toFixed(2);
            quantity = $(this).val();
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
        <?php
        if(Yii::app()->session['calculate_way']=='1'){
            echo "editAutoTienHangAndTax(quantity_node, price_has_tax_node, sum_node, tax_sum_node, tax);";
        }
        ?>           
        setSumForHiddenInputs($(".div-pro1"),$('input[name="sum"]'),$('input[name="tax_sum"]'));
        setTong($(".div-pro1"),$("#sum_sum label"),$("#sum_sum_tax label"),$("#sum_sum_and_tax"));
        init_sum=$('input[name="sum"]').val();
        init_tax_sum=$('input[name="tax_sum"]').val();
    });
});
</script>