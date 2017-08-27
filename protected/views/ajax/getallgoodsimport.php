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
                        <select name="goods_id[]" class="cus-auto18-input unit">
                        </select>
                    </td>
                    <td class="pro-13-2qteselect">
                        <input name="quantity[]" type="text" class="cus-auto18-input numeric" value=""/>
                    </td>                    
                    <td class="pro-qtemoney-dg price has_tax">
                        <input name="price_has_tax[]" value="" type="text" class="pro-money-dgnput numeric price_has_tax">
                    </td>
                    <td class="pro-qtemoney-dg price">
                        <input name="price_not_tax[]" value="" type="text" class="pro-money-dgnput numeric price_not_tax"/>                     
                    </td>
                    <td class="pro-qtemoney-th">
                        <input value="" disabled="disabled" type="text" class="pro-money-dgnput numeric price_not_tax"/>                                             
                    </td>
                    <td class="pro-qte-thueselect">
                        <ul>               
                            <li class="li-left">TTĐB</li>
                            <li class="li-right"><input type="text" class="cus-auto18-input tax tax_ttdb" disabled="disabled" value=""></li>
                            <li class="clearfix"></li>              
                            <li class="li-left">NK</li>
                            <li class="li-right"><input type="text" class="cus-auto18-input tax tax_nk" disabled="disabled" value=""></li>
                            <li class="clearfix"></li>
                            <li class="li-left">GTGT</li>
                            <li class="li-right"><input type="text" class="cus-auto18-input tax tax_vat" disabled="disabled" value=""></li>
                        </ul>
                    </td>
                    <td  class="pro-qtemoney-tt">
                        <ul>   
                            <li class="li-left">TTĐB</li> 
                            <li class="li-right tax_ttdb">0</li>
                            <li class="clearfix"></li>                
                            <li class="li-left">NK</li> 
                            <li class="li-right tax_nk">0</li>
                            <li class="clearfix"></li> 
                            <li class="li-left">GTGT</li> 
                            <li class="li-right tax_vat">0</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
