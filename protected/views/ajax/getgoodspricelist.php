<?php
if(FunctionCommon::get_role()!=Role::ADMIN&&FunctionCommon::get_role()!=Role::QUAN_LY_BAN_HANG){
    $action='view';
}
else{
    $action='update';
}
foreach ($rows as $row) {
    ?>
    <div class="item">
        <a style="color: blue;" href="<?php echo $this->createUrl('/invoicefull/'.$action);?>/id/<?php echo $row['bill_id']; ?>">
            <?php echo $row['price_has_tax']; ?>
        </a>
    </div>
    <?php
}
?>


