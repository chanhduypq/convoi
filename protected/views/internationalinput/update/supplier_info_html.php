<!-- Div Customer info -->
<div class="div-margin">
    <li class="cus-info cus-info16">Số tờ khai</li>
    <li class="cus-tax18"><input id="bill_number" name="bill_number" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->bill_number; ?>"></li>

    <li class="cus-info cus-info15">Ngày</li>
    <li class="cus-auto18" style="background-color: #ffffff;"><input readonly="readonly" id="created_at" name="created_at" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->created_at; ?>"></li>

    <li class="cus-info cus-info15" title="Mã số thuế">MST</li>
    <li class="cus-tax18"><input id="mst" name="" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->tax_code; ?>"></li>

    <li class="clearfix"></li>
    <li class="cus-info cus-info16">Đơn vị bán hàng</li>
    <li class="cus-cty84"><input id="branch_full_name" name="" type="text" class="cus-auto18-input" value="<?php echo $invoicefull_model->full_name; ?>"></li>

    <li class="clearfix"></li>
    <li class="cus-info cus-info16">Địa chỉ</li>
    <li class="cus-cty84"><textarea disabled="disabled" id="branch_address" name="" cols="" rows="2" class="cus-cty84-input"><?php echo $invoicefull_model->address; ?></textarea></li>
    <div class="clearfix"></div>

    <li class="clearfix"></li>
    <li class="cus-info cus-info16">Ghi chú hóa đơn</li>
    <li class="cus-cty84"><textarea name="description" id="description" name="" cols="" rows="2" class="cus-cty84-input"><?php echo $invoicefull_model->description; ?></textarea></li>
    <div class="clearfix"></div>
    
    
</div>