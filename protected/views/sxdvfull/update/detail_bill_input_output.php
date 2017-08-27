<?php
$i = 1;
foreach ($bill_details as $bill_detail) {
    ?>
    <div class="div-margin list">
        <div class="div-pro1">
            <li class="pronametitle">
                <img title="Nhấn vào đây để hủy hàng này" class="cancel_goods" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/delete-icon.png" style="width: 16px;height: 16px;"/> Tên hàng</li>
            <li class="prostt">
                <input disabled="disabled" name="" type="text" class="cus-auto18-input" value="<?php echo $i++; ?>">
            </li>
            <li class="proname">
                <input value="<?php echo $bill_detail->sxdv_name;?>" type="text" name="sxdv_name[]" style="width: 100%; height: 35px"/>
            </li>
            <li class="clearfix"></li>
            <li class="pro-13-1">Đơn vị</li>
            <li class="pro-13-2">Số lượng</li>           
            <li class="pro-moneytitle price">Đ/G (có thuế)</li>
            <li class="pro-moneytitle price">Đ/G</li>
            <li class="pro-moneytitle">Tiền hàng</li>
            <li class="pro-13-2">Thuế (%)</li>
            <li class="pro-moneytitle">Thuế GTGT</li>
            <li class="clearfix"></li>
            <li class="pro-13-1select">
                <input value="<?php echo $bill_detail->sxdv_donvi;?>" type="text" name="sxdv_donvi[]" class="cus-auto18-input" style="width: 100%;"/>
            </li>
            <li class="pro-13-2select">
                <input name="quantity[]" type="text" class="cus-auto18-input numeric" value="<?php echo $bill_detail->quantity; ?>"/>                     
            </li>            
            <li class="pro-money-dg price">
                <input name="price_has_tax[]" value="<?php echo $bill_detail->price_has_tax; ?>" type="text" class="pro-money-dgnput numeric price_has_tax">

            </li>
            <li class="pro-money-dg price">
                <input name="price_not_tax[]" value="<?php echo $bill_detail->price; ?>" type="text" class="pro-money-dgnput numeric price_not_tax"/>                     
            </li>
            <li class="pro-money"><?php echo $bill_detail->sum; ?></li>
            <li class="pro-13-2select">
                <input name="tax[]" type="text" class="cus-auto18-input tax numeric" value="<?php echo $bill_detail->tax; ?>">
            </li>
            <li class="pro-money"><?php echo $bill_detail->sum_tax; ?></li>
            <li class="clearfix"></li>
        </div>
        <div class="clearfix"></div>                 
    </div>
    <?php
}
?>