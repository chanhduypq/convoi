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
        <tr class="row-HD heightauto">
            <td class="row0-HDli row0-HDliw10 cursor"><a href="<?php echo $this->createUrl('/chiphikhdfull/'.$action.'/id/'.$item->id);?>"><?php echo $item->stt;?></a></td>
            <td class="row0-HDli row0-HDliw10 date"><?php echo $item->created_at;?></td>
            <td class="row0-HDli row0-HDliw45 row0-HDli0"><p class="p1"><?php echo nl2br($item->description);?></p></td>            
            <td class="row0-HDli row0-HDliw20 <?php echo $class_for_bold_sum;?>">
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
            <td class="row0-HDli row0-HDliw15"><?php echo $item->payment_method_text;?>
            </td> 
        </tr>
        
<?php
    }
}
?>