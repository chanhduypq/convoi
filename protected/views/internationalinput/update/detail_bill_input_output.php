<?php
$i = 1;
foreach ($bill_details as $bill_detail) {
    ?>
<div class="div-margin list">
    <div class="div-pro1">
        <li class="pronametitle"><img title="Nhấn vào đây để hủy hàng này" class="cancel_goods" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/delete-icon.png" style="width: 16px;height: 16px;"/> Tên hàng</li>
        <li class="prostt"><input disabled="disabled" name="" value="<?php echo $i++; ?>" type="text" class="cus-auto18-input"></li>
        <li class="proname">
            <?php
            echo createGoodsDropdowlist($goods, $bill_detail->goods_id);
            ?>
        </li>
        <li class="clearfix"></li>

        <li class="pro-13-1qte">Đơn vị</li>
        <li class="pro-13-2qte">Số lượng</li>       
        <li class="pro-qtemoneytitle price has_tax">Đ/G (có thuế)</li>
        <li class="pro-qtemoneytitle price">Đ/G</li>
        <li class="pro-qtemoneytitle">Tiền hàng</li>
        <li class="pro-qte-thue">Thuế (%)</li>
        <li class="pro-qtemoneytitle">Tiền thuế</li>
        <li class="clearfix"></li>

        <table style="width: 100%" class="auto_height">
            <tbody>
                <tr>
                    <td class="pro-13-1qteselect">
                        <?php
                        echo createGoodsunitDropdowlist($bill_detail->goods_id);
                        ?>
                    </td>
                    <td class="pro-13-2qteselect">
                        <input name="quantity[]" type="text" class="cus-auto18-input numeric" value="<?php echo $bill_detail->quantity; ?>"/>                     
                    </td>                    
                    <td class="pro-qtemoney-dg price has_tax">
                        <input name="price_has_tax[]" value="<?php echo $bill_detail->price_has_tax; ?>" type="text" class="pro-money-dgnput numeric price_has_tax">
                    </td>
                    <td class="pro-qtemoney-dg price">
                        <input name="price_not_tax[]" value="<?php echo $bill_detail->price; ?>" type="text" class="pro-money-dgnput numeric price_not_tax"/>                     
                    </td>
                    <td class="pro-qtemoney-th">
                        <input value="<?php echo $bill_detail->sum; ?>" disabled="disabled" type="text" class="pro-money-dgnput numeric price_not_tax"/>                                             
                    </td>
                    <td class="pro-qte-thueselect">
                        <ul>
                            <?php
                            if($bill_detail->thue_tieu_thu_dac_biet!='0'){
                            ?>                
                            <li class="li-left">TTĐB</li> 
                            <li class="li-right"><input type="text" class="cus-auto18-input tax tax_ttdb" disabled="disabled" value="<?php echo $bill_detail->thue_tieu_thu_dac_biet; ?>"></li>
                            <li class="clearfix"></li>
                            <?php
                            }
                            else{?>
                            <li class="li-left" style="display: none;">TTĐB</li> 
                            <li class="li-right"><input style="display: none;" type="text" class="cus-auto18-input tax tax_ttdb" disabled="disabled" value=""></li>
                            <li class="clearfix"></li>
                            <?php
                            }
                            if($bill_detail->thue_nhap_khau!='0'){
                            ?>                
                            <li class="li-left">NK</li> 
                            <li class="li-right"><input type="text" class="cus-auto18-input tax tax_nk" disabled="disabled" value="<?php echo $bill_detail->thue_nhap_khau; ?>"></li>
                            <li class="clearfix"></li>
                            <?php
                            } 
                            else{?>
                            <li class="li-left" style="display: none;">NK</li> 
                            <li class="li-right"><input style="display: none;" type="text" class="cus-auto18-input tax tax_nk" disabled="disabled" value=""></li>
                            <li class="clearfix"></li>
                            <?php
                            }
                            ?>   
                            <li class="li-left">GTGT</li> 
                            <li class="li-right"><input type="text" class="cus-auto18-input tax tax_vat" disabled="disabled" value="<?php echo $bill_detail->tax; ?>"></li>
                        </ul>
                    </td>
                    <td  class="pro-qtemoney-tt">
                        <ul>
                            <?php
                            if($bill_detail->sum_thue_tieu_thu_dac_biet!='0'){
                            ?>    
                            <li class="li-left">TTĐB</li> 
                            <li class="li-right tax_ttdb"><?php echo $bill_detail->sum_thue_tieu_thu_dac_biet; ?></li>
                            <li class="clearfix"></li>
                            <?php
                            }
                            else{?>
                            <li class="li-left" style="display: none;">TTĐB</li> 
                            <li class="li-right tax_ttdb" style="display: none;"></li>
                            <li class="clearfix"></li>
                            <?php
                            }
                            if($bill_detail->sum_thue_nhap_khau!='0'){
                            ?>                
                            <li class="li-left">NK</li> 
                            <li class="li-right tax_nk"><?php echo $bill_detail->sum_thue_nhap_khau; ?></li>
                            <li class="clearfix"></li>
                            <?php
                            }  
                            else{?>
                            <li class="li-left" style="display: none;">NK</li> 
                            <li class="li-right tax_nk" style="display: none;"></li>
                            <li class="clearfix"></li>
                            <?php
                            }
                            ?> 
                            <li class="li-left">GTGT</li> 
                            <li class="li-right tax_vat"><?php echo $bill_detail->sum_tax; ?></li>
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
?>
