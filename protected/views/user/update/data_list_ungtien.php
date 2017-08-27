<?php
        
if(count($ung_tiens)>0){
    foreach ($ung_tiens as $item) {        
        ?>
        <tr class="row-HD heightauto<?php if($item->dong_y=='0') echo ' is_not_printed';?>">
            <input type="hidden" name="ungtien_id[]" value="<?php echo $item->id;?>"/>
            <td class="row0-HDli row0-HDliw5"><?php echo $item->stt;?></a></td>
            <td class="row0-HDli row0-HDliw10"><?php echo $item->created_at;?></td>
            <td class="row0-HDli row0-HDliw25"><?php echo $item->content;?></td>
            <td class="row0-HDli row0-HDliw15"><?php echo $item->ung_tien;?></td>
            <td class="row0-HDli row0-HDliw15"><?php echo $item->hoan_tra;?></td>
            <td class="row0-HDli row0-HDliw15"><?php echo $item->tm;?></p></td>            
        <td class="row0-HDli row0-HDliw15"><?php if(trim($item->xac_nhan)!='') echo $item->xac_nhan;else echo '<button class="cursor">Xác nhận</button>';?></td>           
        </tr>
        
<?php
    }
}
?>