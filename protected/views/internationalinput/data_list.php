<?php
        
if(count($items)>0){
    foreach ($items as $item) {
        if($item->chi_phi_ngan_hang_vnd==''){
            $item->chi_phi_ngan_hang_vnd=0;
        } 
        if(
                ($item->payment_method_id3==PaymentMethod::CHUA_THANH_TOAN&&$item->payment_method_id4==PaymentMethod::CHUA_THANH_TOAN&&$item->payment_method_id5==PaymentMethod::CHUA_THANH_TOAN)
                ||($item->payment_method_id3==PaymentMethod::KHONG_THANH_TOAN&&$item->payment_method_id4==PaymentMethod::KHONG_THANH_TOAN&&$item->payment_method_id5==PaymentMethod::KHONG_THANH_TOAN)
        ){
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
            <td class="row0-HDli row0-HDliw15" style="padding-top: auto;word-wrap: break-word;"><a href="<?php echo $this->createUrl('/internationalinput/'.$action.'/id/'.$item->id);?>"><?php echo $item->bill_number;?></a><?php if($item->tax_sum=='0') echo ' (Đang nhập hàng)';?></td>
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
                echo number_format($item->gia_tri_hang_hoa_vnd+$item->chi_phi_ngan_hang_vnd+  str_replace(".", "", $item->tax_sum), 0, ",", ".");
// echo $item->sum_and_sumtax;
                ?>
            </td>
            <td class="row0-HDli row0-HDliw15 row0-HDli0-r"><?php echo $item->tax_sum;?></td>
            <td class="row0-HDli row0-HDliw15 row0-HDli0"><p class="p1"><?php echo nl2br($item->description);?></p></td>            
            
        </tr>
        
<?php
    }
}
?>