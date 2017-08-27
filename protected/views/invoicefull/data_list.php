<?php
        
if(count($items)>0){
    foreach ($items as $item) {
        if($item->payment_method_id==PaymentMethod::CHUA_THANH_TOAN||$item->payment_method_id==PaymentMethod::KHONG_THANH_TOAN){
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
        <tr class="row-HD<?php if($item->is_printed==0) echo ' is_not_printed'; ?>">
            <td class="row0-HDli row0-HDliw10"><a href="<?php echo $this->createUrl('/invoicefull/'.$action.'/id/'.$item->id);?>"><?php echo $item->bill_number;?></a></td>
            <td class="row0-HDli row0-HDliw10 date"><?php echo $item->created_at;?></td>
            <td class="row0-HDli row0-HDliw30 row0-HDli0 cursor1" title="<?php echo $item->full_name;?>" id="<?php echo $item->branch_id;?>"><p class="pcty"><a class="submit"><?php echo FunctionCommon::crop($item->full_name,50,false);?></a></p><p class="pname"><a class="submit" style="word-wrap: break-word;"><?php echo $item->short_hand_name;?></a></p></td>
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
                if($item->payment_method_id==PaymentMethod::KHONG_THANH_TOAN) echo '<i>'; echo $item->sum_and_sumtax;if($item->payment_method_id==PaymentMethod::KHONG_THANH_TOAN) echo '</i>';
                ?>
            </td>
            <td class="row0-HDli row0-HDliw15 row0-HDli0-r"><?php echo $item->tax_sum;?></td>
            <td class="row0-HDli row0-HDliw10 row0-HDli0"><p class="p1 print_bill1 cursor"><a><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-printer.png" width="25" height="25"></a></p><p class="p2 count_lien1 cursor<?php if($item->count_lien1>0) echo ' go';?>"> <?php echo $item->count_lien1;?></p></td>
            <td class="row0-HDli row0-HDliw10 row0-HDli0"><p class="p1 print_bill2 cursor"><a><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-printer.png" width="25" height="25"></a></p><p class="p2 count_lien2 cursor<?php if($item->count_lien2>0) echo ' go';?>"> <?php echo $item->count_lien2;?></p></td>     
        </tr>
<?php
    }
}
?>