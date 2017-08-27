<div class="div-margin list">
    <div class="div-pro1">
        <li class="pronametitle"><img title="Nhấn vào đây để hủy hàng này" class="cancel_goods" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/delete-icon.png" style="width: 16px;height: 16px;"/> Tên hàng</li>
        <li class="prostt"><input disabled="disabled" name="" type="text" class="cus-auto18-input"></li>
        <li class="proname">
            <select class="goods cus-auto18-input" multiple="multiple" style="display: none;">
                <?php 
                foreach ($goods as $value){?>
                     <option value='<?php echo $value['group_id'];?>'><?php echo $value['goods_full_name'];?></option>
                <?php    
                }
                ?>
            </select>
        </li>
        <li class="clearfix"></li>
        <?php
            echo_header_unit();
            echo_header_quantity();
            echo_header_tax();
            echo_header_price_has_tax();
            echo_header_price_has_not_tax();
            echo_header_sum();
            echo_header_sum_tax();
        ?>
        <li class="clearfix"></li>        
        <?php
            echo_content_unit();
            echo_content_quantity();
            echo_content_tax();
            echo_content_price_has_tax();
            echo_content_price_has_not_tax();
            echo_content_sum();
            echo_content_sum_tax();
        ?>
        <li class="clearfix"></li>
    </div>
    <div class="clearfix"></div>
</div>
      
<?php
function echo_content_unit(){?>
    <li class="pro-13-1select">
        <select name="goods_id[]" class="cus-auto18-input unit">
        </select>
        <input type="hidden" class="quantity_left"/>
    </li>
<?php    
}
function echo_content_quantity(){
    echo '<li class="pro-13-2select"><input name="quantity[]" type="text" class="cus-auto18-input numeric" disabled="disabled"></li>';
}
function echo_content_tax(){
    echo '<li class="pro-13-2select"><input type="text" class="cus-auto18-input tax" disabled="disabled"></li>';
}
function echo_content_price_has_tax(){
    echo '<li class="pro-money-dg price"><input name="price_has_tax[]" type="text" class="pro-money-dgnput numeric price_has_tax" disabled="disabled"></li>';
}
function echo_content_price_has_not_tax(){
    echo '<li class="pro-money-dg price"><input name="price_not_tax[]" type="text" class="pro-money-dgnput numeric price_not_tax" disabled="disabled"></li>';
}
function echo_content_sum(){
    echo '<li class="pro-money">0</li>';
}
function echo_content_sum_tax(){
    echo '<li class="pro-money">0</li>';
}

function echo_header_unit(){
    echo '<li class="pro-13-1">Đơn vị</li>';
}
function echo_header_quantity(){
    echo '<li class="pro-13-2">Số lượng</li>';
}
function echo_header_tax(){
    echo '<li class="pro-13-2">Thuế (%)</li>';
}
function echo_header_price_has_tax(){
    echo '<li class="pro-moneytitle price">Đ/G (có thuế)</li>';
}
function echo_header_price_has_not_tax(){
    echo '<li class="pro-moneytitle price">Đ/G</li>';
}
function echo_header_sum(){
    echo '<li class="pro-moneytitle">Tiền hàng</li>';
}
function echo_header_sum_tax(){
    echo '<li class="pro-moneytitle">Thuế GTGT</li>';
}
?>