<div id="mauHD" class="child">
    <div class="div-pro1">
        <h1>
            Lý do: <?php echo $row['reason']; ?>
        </h1>

        <li class="clearfix"></li>
    </div>

    <h1>
        Thông tin hàng hóa nhập
    </h1>
    <li class="clearfix"></li>

    <?php
    $i = 1;
    $sum_sum = 0;
    $sum_sum_tax = 0;
    foreach ($bill_details as $bill_detail) {
        ?>
        <div class="div-margin">
            <div class="div-pro1">
                <li class="pronametitle"><img title="Nhấn vào đây để hủy hàng này" class="cancel_goods" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/delete-icon.png" style="width: 16px;height: 16px;"/> Tên hàng</li>
                <li class="prostt"><input disabled="disabled" name="" value="<?php echo $i++; ?>" type="text" class="cus-auto18-input"></li>
                <li class="proname">
                    <input type="text" disabled="disabled" class="cus-auto18-input" value="<?php echo $bill_detail['goods_full_name']; ?>"/>
                </li>
                <li class="clearfix"></li>

                <li class="pro-13-1qte">Đơn vị</li>
                <li class="pro-13-2qte">Số lượng</li>
                <li class="pro-qte-thue">Thuế (%)</li>
                <li class="pro-qtemoneytitle price has_tax">Đ/G (có thuế)</li>
                <li class="pro-qtemoneytitle price">Đ/G</li>
                <li class="pro-qtemoneytitle">Tiền hàng</li>
                <li class="pro-qtemoneytitle">Tiền thuế</li>
                <li class="clearfix"></li>

                <li class="pro-13-1qteselect">
                    <input type="text" disabled="disabled" class="cus-auto18-input" value="<?php echo $bill_detail['unit_full_name']; ?>"/>
                </li>
                <li class="pro-13-2qteselect">
                    <input disabled="disabled" name="quantity[]" type="text" class="cus-auto18-input numeric" value="<?php echo number_format($bill_detail['quantity'], 0, ",", "."); ?>"/>                     
                </li>
                <li class="pro-qte-thueselect">
                    <ul>
                        <li class="li-left">TTĐB</li> 
                        <li class="li-right"><input type="text" class="cus-auto18-input tax" disabled="disabled" value="<?php echo $bill_detail['thue_tieu_thu_dac_biet']; ?>"></li>
                        <li class="clearfix"></li>
                        <li class="li-left">NK</li> 
                        <li class="li-right"><input type="text" class="cus-auto18-input tax" disabled="disabled" value="<?php echo $bill_detail['thue_nhap_khau']; ?>"></li>
                        <li class="clearfix"></li>
                        <li class="li-left">GTGT</li> 
                        <li class="li-right"><input type="text" class="cus-auto18-input tax" disabled="disabled" value="<?php echo $bill_detail['tax']; ?>"></li>
                    </ul>
                </li>
                <li class="pro-qtemoney-dg price has_tax">            
                    <input disabled="disabled" name="price_has_tax[]" value="<?php echo number_format($bill_detail['price_has_tax'], 0, ",", "."); ?>" type="text" class="pro-money-dgnput numeric price_has_tax">
                </li>
                <li class="pro-qtemoney-dg price">
                    <input disabled="disabled" name="price_not_tax[]" value="<?php echo number_format($bill_detail['price'], 0, ",", "."); ?>" type="text" class="pro-money-dgnput numeric price_not_tax"/>                     
                </li>
                <li class="pro-qtemoney-th"><?php echo number_format($bill_detail['price'] * $bill_detail['quantity'], 0, ",", "."); ?></li>
                <li class="pro-qtemoney-tt">
                    <ul>
                        <li class="li-left">TTĐB</li> 
                        <li class="li-right"><?php echo $bill_detail['sum_thue_tieu_thu_dac_biet']; ?></li>
                        <li class="clearfix"></li>
                        <li class="li-left">NK</li> 
                        <li class="li-right"><?php echo $bill_detail['sum_thue_nhap_khau']; ?></li>
                        <li class="clearfix"></li>
                        <li class="li-left">GTGT</li> 
                        <li class="li-right"><?php echo $bill_detail['sum_tax']; ?></li>
                    </ul>
                </li>
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