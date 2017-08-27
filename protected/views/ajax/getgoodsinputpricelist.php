<?php
if(FunctionCommon::get_role()!=Role::ADMIN&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG){
    $action='view';
}
else{
    $action='update';
}
foreach ($rows as $row){
    if($row['is_international']=='1'){
        $controler='internationalinput';
    }
    else{
        $controler='invoiceinputfull';
    }
?>
<div class="item">
        <a style="color: blue;" href="<?php echo $this->createUrl("/$controler/".$action);?>/id/<?php echo $row['bill_id'];?>">
            <?php echo $row['price_has_tax'];?>
        </a>
    </div>
<?php 
} 
?>


