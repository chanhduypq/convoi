<div style="display: none;" id="dialog-modal-error">
</div>
<script type="text/javascript">
    jQuery(function($) {
        $("body").delegate(".numeric", "keyup", function() {

            tax = $(this).parent().parent().find(".tax").eq(0).val();
            tax = parseInt(tax);
            if ($(this).hasClass("price_has_tax")) {//nếu user đang nhập đơn giá có thuế
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
            else if ($(this).hasClass("price_not_tax")) {//nếu user đang nhập đơn giá chưa thuế
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
            else if ($(this).attr("name") == "quantity[]") {//nếu user đang nhập số lượng
                price = $(this).parent().parent().find(".price_has_tax").eq(0).val();
                if (price.indexOf(".") != -1) {
                    price = price.split(".").join("");
                }
                temp = price / ((100 + tax) / 100);
                price_not_tax_double = temp.toFixed(2);
                quantity = $(this).val();
                if (quantity != "" && quantity.indexOf(".") != -1) {
                    quantity_temp = quantity.split(".").join("");
                }
                else {
                    quantity_temp = quantity;
                }
                if (quantity_temp == "") {
                    quantity_temp = "0";
                }
                quantity_node = $(this);                
                /**
                 * kiểm tra số lượng muốn bán (user vừa nhập vào) có vượt quá số lượng tồn kho hay không
                 */
                error_text='';
                if ($(this).parent().prev().find("input").length > 0) {//nếu đã có <input type="hidden" class="quantity_left" value="xxx">
                    if (parseInt(quantity_temp) > parseInt($(this).parent().prev().find("input").eq(0).val())) {
                        error_text='Số lượng hàng hóa này trong kho chỉ còn: ' + $(quantity_node).parent().prev().find("input").eq(0).val();                        
                    }
                }
                else {//nếu chưa có <input type="hidden" class="quantity_left" value="xxx">
                    $.ajax({
                        async: false, 
                        cache: false,
                        type: "POST",
                        data: {goods_id: $(quantity_node).parent().prev().find("select").eq(0).val(), quantity: quantity_temp, bill_id: $('input[name="bill_id"]').val()},
                        url: '<?php echo $this->createUrl("/ajax/checkerrorquantityonegoods"); ?>',
                        success: function(data, textStatus, jqXHR) {
                            data = $.parseJSON(data);
                            if (data.error == '1') {
                                error_text='Số lượng hàng hóa này trong kho chỉ còn: ' + data.quantity;
                                
                            }
                        }

                    });
                }
                /**
                 * nếu số lượng hàng muốn bán (user vừa nhập vào) vượt quá số lượng tồn thi báo lỗi
                 */
                if(error_text!=""){
                    jQuery("#dialog-modal-error").dialog({
                        title: error_text,
                        create: function(event, ui) {
                            $("body").css({overflow: 'hidden'})
                        },
                        beforeClose: function(event, ui) {
                            $("body").css({overflow: 'inherit'})
                        },
                        position: ['top', 110],
                        height: 100,
                        width: 700,
                        show: {effect: "slide", duration: 500},
                        hide: {effect: "slide", duration: 500},
                        modal: true,
                        open: function(event, ui) {
                            $(".ui-dialog-buttonset").find("button").eq(0).addClass("close");
                        },
                        buttons: {
                            "Đóng": function() {
                                jQuery("#dialog-modal-error").dialog('close');
                                $(".ui-dialog-buttonset").html('');
                                $(quantity_node).focus();
                            }
                        }
                    });
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("close");
                
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