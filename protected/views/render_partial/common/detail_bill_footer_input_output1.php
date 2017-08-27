<div id="div-margin">
    <div class="div-pro1">
        <li class="all-total1">Tổng cộng</li>
        <li class="all-total2" id="sum_sum"><?php echo $invoicefull_model->sum; ?></li>
        <li class="all-total2" id="sum_sum_tax"><?php echo $invoicefull_model->tax_sum; ?></li>
        <li class="clearfix"></li>

        <li class="all-total1" style="height: 48px;">Tổng tiền thanh toán</li>
        <li class="all-total3">
            <span class="p_left" id="sum_sum_and_tax"><?php echo $invoicefull_model->sum_all; ?></span>                    
        </li>
        <li class="clearfix"></li>
    </div>
    <div class="clearfix"></div>
</div>