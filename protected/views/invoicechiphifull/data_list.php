<?php
        
if(count($items)>0){
    foreach ($items as $item) {
        if($item->payment_method_id==PaymentMethod::CHUA_THANH_TOAN||$item->payment_method_id==PaymentMethod::KHONG_THANH_TOAN||($item->bill_number=='0000000'&&$item->sum_and_sumtax==0)){
            $class_for_bold_sum='row0-HDli0-r';
        }
        else{
            $class_for_bold_sum='row0-HDli0-rb';
        }
        if($item->is_complete=='1'){
            $class_for_cell=' is_complete';
        }
        else{
            if($item->is_paying=='1'){
                $class_for_cell=' is_paying';
            }
            else{
                $class_for_cell='';
            }            
        }
        ?>
        <tr class="row-HD heightauto<?php if($item->bill_number=='0000000'&&$item->sum_and_sumtax==0) echo ' is_not_printed1';?>">
            <td class="row0-HDli row0-HDliw10"><a href="<?php echo $this->createUrl('/invoicechiphifull/'.$action.'/id/'.$item->id);?>"><?php echo $item->bill_number;?></a></td>
            <td class="row0-HDli row0-HDliw10 date"><?php echo $item->created_at;?></td>
            <td class="row0-HDli row0-HDliw40 row0-HDli0 cursor1" title="<?php echo $item->full_name;?>" id="<?php echo $item->branch_id;?>"><p class="pcty"><a class="submit"><?php echo FunctionCommon::crop($item->full_name,50,false);?></a></p><p class="pname"><a class="submit"><?php echo $item->short_hand_name;?></a></p></td>
            <td class="row0-HDli row0-HDliw15 <?php echo $class_for_bold_sum;?>">
                <?php 
                if($class_for_cell==' is_complete'){?>
                    <img style="width: 15px;height: 15px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/socai/complete.png"/>  
                <?php    
                }
                else if($class_for_cell==' is_paying'){?>
                    <img style="width: 15px;height: 15px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/socai/paying.png"/>
                <?php    
                }
                if($item->payment_method_id==PaymentMethod::KHONG_THANH_TOAN) echo '<i>';echo $item->sum_and_sumtax;if($item->payment_method_id==PaymentMethod::KHONG_THANH_TOAN) echo '</i>';
                ?>
            </td>
            <td class="row0-HDli row0-HDliw10 row0-HDli0-r"><?php echo $item->tax_sum;?></td>
            <td class="row0-HDli row0-HDliw15 row0-HDli0"><p class="p1"><?php echo nl2br($item->description);?></p></td>            
            
        </tr>
        
<?php
    }
}
?>