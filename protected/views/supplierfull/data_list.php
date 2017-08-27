<?php
if(FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN){
    $class_for_click_for_popup_edit='';
}        
else{
    $class_for_click_for_popup_edit=' edit_customer';
}        
if(count($items)>0){
    foreach ($items as $item) {
        $class_append='';
        $title_for_delete='';    
        if(FunctionCommon::get_role()==Role::QUAN_LY_KHO_HANG||FunctionCommon::get_role()==Role::ADMIN){
                if($item->bill_count=='0'&&$item->branch_type!='3'){
                    $class_append=' delete cancel_branch';
                    $title_for_delete=' title="Nhấn vào đây để xóa '.lcfirst (Yii::app()->params['label_for_supplier']).' này"';
                }
        }
        ?>
        
        
        <tr class="row-HD">
            <td class="row0-KHli row0-HDliw65 row0-HDli0<?php echo $class_for_click_for_popup_edit;?>" id="<?php echo $item->branch_id1;?>">
                <p class="pcty">
                    <b>
                        <?php if(FunctionCommon::get_role()!=Role::NHAN_VIEN){?>
                        <a><?php echo $item->full_name;?></a>
                        <?php }else { echo $item->full_name;}?>
                        <br/>
                        <?php echo $item->address;?>
                    </b>
                    <br/>
                    MST: <?php echo $item->tax_code;?>
                    <br/>                    
                    <?php echo $item->firstname_lastname_phone_email;?><br/>
                </p>
                <p class="pname"><?php echo $item->short_hand_name;?></p>
            </td>
            <td class="row0-KHli row0-HDliw10 row0-HDli0-r<?php if($item->bill_count!='0'){?> cursor<?php }?>">
                <?php 
                if($item->bill_count!='0'){
                    if(is_numeric($item->quantity)){
                ?>
                        <a class="submit invoiceinput"><?php echo $item->bill_count;?></a>
                <?php
                    }
                    else{//chính là cái này else if($item->quantity=='Hàng hóa chi phí và dịch vụ.')?>
                        <a class="submit invoicechiphi"><?php echo $item->bill_count;?></a>
                    <?php
                    }
                }
                else{
                    echo $item->bill_count;
                }
                ?>
            </td>
            <td class="row0-KHli row0-HDliw10 row0-HDli0-r<?php if($item->quantity!='0'){?> cursor<?php }?>">
                <?php 
                if($item->quantity!='0'&&is_numeric($item->quantity)){
                ?>
                <a class="submit goodsinput"><?php echo number_format($item->quantity, 0, ",", ".");?></a>
                <?php
                }
                else{
                    echo $item->quantity;
                }
                ?>
                
            </td>
            <td class="row0-KHli row0-HDliw15 row0-HDli0-rb<?php echo $class_append;?>"<?php echo $title_for_delete;?>><?php echo number_format($item->tong_tien,0,",",".");?></td>
            
            
        </tr>
        
<?php
    }
}
?>