<div class="div-margin">
    <li class="cus-info cus-info16">Số hóa đơn</li>
    <li class="cus-auto18"><input disabled="disabled" name="" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->bill_number; ?>"></li>

    <li class="cus-info cus-info15">Ngày</li>
    <li class="cus-auto18"><input disabled="disabled" name="" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->created_at; ?>"></li>

    <li class="cus-info cus-info15" title="Mã số thuế">MST</li>
    <li class="cus-tax18"><input id="mst" name="" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->tax_code;if(trim($invoicefull_model->tax_code_chinhanh)!='') echo ' - '. $invoicefull_model->tax_code_chinhanh ; ?>"></li>

    <li class="clearfix"></li>
    <li class="cus-info cus-info16">Đơn vị mua hàng</li>
    <li class="cus-cty84"><input id="branch_full_name" name="" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->full_name; ?>"></li>

    <li class="clearfix"></li>
    <li class="cus-info cus-info16">Địa chỉ</li>
    <li class="cus-cty84"><textarea disabled="disabled" id="branch_address" name="" cols="" rows="2" class="cus-cty84-input"><?php echo $invoicefull_model->address; ?></textarea></li>
    
    <li class="cus-info cus-info16">&nbsp;</li>
    <li style="float: left;width: 84%;">                
        <select name="payment_method" id="payment_method" disabled="disabled">
            <option value="">--Chọn phương thức thanh toán--</option>
            <?php
            foreach ($payment_method as $value) {
                $selected='';
                if($value->id==$payment_method_id){
                    $selected=' selected="selected"';
                }
                ?>
            <option<?php echo $selected;?> value="<?php echo $value->id;?>"><?php echo $value->method;?></option>
            <?php    
            }
            ?>
        </select>
    </li>
    <div class="clearfix"></div>
</div>