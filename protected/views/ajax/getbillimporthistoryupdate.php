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
</div>
<?php
$i = 1;
foreach ($bill_details as $bill_detail) {
    ?>
<div class="div-margin list">
    <div class="div-pro1">
        <li class="pronametitle"> Tên hàng</li>
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

        <table style="width: 100%" class="auto_height">
            <tbody>
                <tr>
                    <td class="pro-13-1qteselect">
                        <input type="text" disabled="disabled" class="cus-auto18-input" value="<?php echo $bill_detail['unit_full_name']; ?>"/>
                    </td>
                    <td class="pro-13-2qteselect">
                        <input disabled="disabled" name="quantity[]" type="text" class="cus-auto18-input numeric" value="<?php echo $bill_detail['quantity']; ?>"/>                     
                    </td>
                    <td class="pro-qte-thueselect">
                        <ul>
                            <?php
                            if($bill_detail['thue_tieu_thu_dac_biet']!='0'){
                            ?>                
                            <li class="li-left">TTĐB</li> 
                            <li class="li-right"><input type="text" class="cus-auto18-input tax tax_ttdb" disabled="disabled" value="<?php echo $bill_detail['thue_tieu_thu_dac_biet']; ?>"></li>
                            <li class="clearfix"></li>
                            <?php
                            }
                            if($bill_detail['thue_nhap_khau']!='0'){
                            ?>                
                            <li class="li-left">NK</li> 
                            <li class="li-right"><input type="text" class="cus-auto18-input tax tax_nk" disabled="disabled" value="<?php echo $bill_detail['thue_nhap_khau']; ?>"></li>
                            <li class="clearfix"></li>
                            <?php
                            }                
                            ?>   
                            <li class="li-left">GTGT</li> 
                            <li class="li-right"><input type="text" class="cus-auto18-input tax tax_vat" disabled="disabled" value="<?php echo $bill_detail['tax']; ?>"></li>
                        </ul>
                    </td>
                    <td class="pro-qtemoney-dg price has_tax">
                        <input disabled="disabled" name="price_has_tax[]" value="<?php echo $bill_detail['price_has_tax']; ?>" type="text" class="pro-money-dgnput numeric price_has_tax">
                    </td>
                    <td class="pro-qtemoney-dg price">
                        <input disabled="disabled" name="price_not_tax[]" value="<?php echo $bill_detail['price']; ?>" type="text" class="pro-money-dgnput numeric price_not_tax"/>                     
                    </td>
                    <td class="pro-qtemoney-th">
                        <input value="<?php echo number_format($bill_detail['price'] * $bill_detail['quantity'], 0, ",", "."); ?>" disabled="disabled" type="text" class="pro-money-dgnput numeric price_not_tax"/>                                             
                    </td>
                    <td  class="pro-qtemoney-tt">
                        <ul>
                            <?php
                            if($bill_detail['sum_thue_tieu_thu_dac_biet']!='0'){
                            ?>    
                            <li class="li-left">TTĐB</li> 
                            <li class="li-right tax_ttdb"><?php echo $bill_detail['sum_thue_tieu_thu_dac_biet']; ?></li>
                            <li class="clearfix"></li>
                            <?php
                            }
                            if($bill_detail['sum_thue_nhap_khau']!='0'){
                            ?>                
                            <li class="li-left">NK</li> 
                            <li class="li-right tax_nk"><?php echo $bill_detail['sum_thue_nhap_khau']; ?></li>
                            <li class="clearfix"></li>
                            <?php
                            }                
                            ?> 
                            <li class="li-left">GTGT</li> 
                            <li class="li-right tax_vat"><?php echo $bill_detail['sum_tax']; ?></li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php

?>
    </div>
    <div class="clearfix"></div>
</div>


<?php
}
$sum_sum = str_replace(".", "", $invoicefull_model['sum']);
$sum_sum_tax = str_replace(".", "", $invoicefull_model['tax_sum']);
?>
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
<script type="text/javascript"> 
    
    jQuery(function($) {
        
        quantities = $("input[name='quantity[]']");
        for (i = 0; i < quantities.length; i++) {
            if($(quantities[i]).parent().parent().find(".tax_ttdb").eq(0).length>0){
                tax_ttdb = $(quantities[i]).parent().parent().find(".tax_ttdb").eq(0).val();
            }
            else{
                tax_ttdb=0;
            }
            if($(quantities[i]).parent().parent().find(".tax_nk").eq(0).length>0){
                tax_nk = $(quantities[i]).parent().parent().find(".tax_nk").eq(0).val();
            }
            else{
                tax_nk=0;
            }                   
            tax = $(quantities[i]).parent().parent().find(".tax_vat").eq(0).val();           
            if(tax=='/'){
                tax=0;
            }
            tax = parseInt(tax);
            if(tax_ttdb=='/'){
                tax_ttdb=0;
            }
            tax_ttdb = parseInt(tax_ttdb);
            if(tax_nk=='/'){
                tax_nk=0;
            }
            tax_nk = parseInt(tax_nk);
            sum_node = $(this).parent().parent().find(".pro-qtemoney-th").eq(0).find("input").eq(0);
            if($(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_ttdb").eq(0).length>0){
                tax_ttdb_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_ttdb").eq(0);            
            }
            else{
                tax_ttdb_sum_node=null;
            }
            if($(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_nk").eq(0).length>0){
                tax_nk_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_nk").eq(0);            
            }
            else{
                tax_nk_sum_node=null;
            }
            
            
            tax_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_vat").eq(0);
            price_has_tax_node = $(quantities[i]).parent().next().next().find("input").eq(0);
            //
            price = $(price_has_tax_node).val();
            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            price_not_tax_double=temp.toFixed(2);
            //
                
            setTienHangAndTax($(quantities[i]).val(), price_not_tax_double, tax_ttdb,tax_nk,tax, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node);
            <?php
            if(Yii::app()->session['calculate_way']=='1'){
                echo "editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node, tax);";
            }
            ?>  
            

        }
    });
</script>