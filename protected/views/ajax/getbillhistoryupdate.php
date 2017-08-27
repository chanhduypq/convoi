<div id="mauHD" class="child">
    <div class="div-pro1">
        <h1>
            Lý do: <?php echo $row['reason']; ?>
        </h1>

        <li class="clearfix"></li>
    </div>

    <h1>
        Thông tin hàng hóa <?php echo $is_input=='1'?'nhập':'xuất';?>
    </h1>
    <li class="clearfix"></li>

    <?php
    $i = 1;
    $sum_sum = 0;
    $sum_sum_tax = 0;
    foreach ($bill_details as $bill_detail) {
        $tax = $bill_detail['tax'];
        ?>
        <div class="div-margin">
            <div class="div-pro1">
                <li class="pronametitle">
                    Tên hàng</li>
                <li class="prostt">
                    <input disabled="disabled" name="" type="text" class="cus-auto18-input" value="<?php echo $i++; ?>">
                </li>
                <li class="proname">
                    <input type="text" disabled="disabled" class="cus-auto18-input" value="<?php echo $bill_detail['goods_full_name']; ?>"/>

                </li>
                <li class="clearfix"></li>
                <li class="pro-13-1">Đơn vị</li>
                <li class="pro-13-2">Số lượng</li>
                <li class="pro-13-2">Thuế (%)</li>
                <li class="pro-moneytitle price">Đ/G (có thuế)</li>
                <li class="pro-moneytitle price">Đ/G</li>
                <li class="pro-moneytitle">Tiền hàng</li>
                <li class="pro-moneytitle">Thuế GTGT</li>
                <li class="clearfix"></li>
                <li class="pro-13-1select">
                    <input type="text" disabled="disabled" class="cus-auto18-input" value="<?php echo $bill_detail['unit_full_name']; ?>"/>

                </li>
                <li class="pro-13-2select">
                    <input disabled="disabled" name="quantity[]" type="text" class="cus-auto18-input numeric" value="<?php echo number_format($bill_detail['quantity'], 0, ",", "."); ?>"/>                     
                </li>
                <li class="pro-13-2select">
                    <input type="text" class="cus-auto18-input tax" disabled="disabled" value="<?php echo $tax; ?>">

                </li>
                <li class="pro-money-dg price">

                    <input disabled="disabled" name="price_has_tax[]" value="<?php echo number_format($bill_detail['price_has_tax'], 0, ",", "."); ?>" type="text" class="pro-money-dgnput numeric price_has_tax">

                </li>
                <li class="pro-money-dg price">
                    <input disabled="disabled" name="price_not_tax[]" value="<?php echo number_format($bill_detail['price'], 0, ",", "."); ?>" type="text" class="pro-money-dgnput numeric price_not_tax"/>                     
                </li>
                <li class="pro-money"><?php echo number_format($bill_detail['price'] * $bill_detail['quantity'], 0, ",", "."); ?></li>
                <li class="pro-money"><?php echo number_format($bill_detail['price'] * $bill_detail['quantity'] * $tax / 100, 0, ",", "."); ?></li>
                <li class="clearfix"></li>
            </div>
            <div class="clearfix"></div>                 
        </div>
        <?php
    }
    $sum_sum = str_replace(".", "", $invoicefull_model['sum']);
    $sum_sum_tax = str_replace(".", "", $invoicefull_model['tax_sum']);
    ?>





    <!-- Total -->
    <div id="div-margin">
        <div class="div-pro1">
            <li class="all-total1">Tổng cộng</li>
            <li class="all-total2" id="sum_sum"><?php echo number_format($sum_sum, 0, ",", "."); ?></li>
            <li class="all-total2" id="sum_sum_tax"><?php echo number_format($sum_sum_tax, 0, ",", "."); ?></li>
            <li class="clearfix"></li>

            <li class="all-total1" style="height: 48px;">Tổng tiền thanh toán</li>
            <li class="all-total3">
                <span class="p_left" id="sum_sum_and_tax"><?php echo number_format($sum_sum + $sum_sum_tax, 0, ",", "."); ?></span>                    
            </li>
            <li class="clearfix"></li>
        </div>
        <div class="clearfix"></div>
    </div>

    <li class="clearfix"></li>
</div>
<script type="text/javascript">
    jQuery(function($) {        
        quantities = $("input[name='quantity[]']");
        for (i = 0; i < quantities.length; i++) {
            tax = $(quantities[i]).parent().parent().find(".tax").eq(0).val();
            tax = parseInt(tax);
            sum_node = $(quantities[i]).parent().parent().find(".pro-money").eq(0);
            tax_sum_node = $(quantities[i]).parent().parent().find(".pro-money").eq(1);
            price_has_tax_node = $(quantities[i]).parent().next().next().find("input").eq(0);
            //
            price = $(price_has_tax_node).val();
            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            price_not_tax_double=temp.toFixed(2);
            //
            setTienHangAndTax($(quantities[i]).val(), price_not_tax_double, tax, sum_node, tax_sum_node);             
            editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_sum_node, tax);
            
        }

    });
</script>