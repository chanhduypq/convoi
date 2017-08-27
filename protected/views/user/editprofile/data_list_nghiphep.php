<?php
        
if(count($nghi_pheps)>0){
    foreach ($nghi_pheps as $item) {        
        ?>
        <tr class="row-HD heightauto edit1">
            <input type="hidden" name="ungtien_id1[]" value="<?php echo $item->id;?>"/>
            <td class="row0-HDli row0-HDliw5"><?php echo $item->stt;?></a></td>
            <td class="row0-HDli row0-HDliw25"><?php echo $item->content;?></td>
            <td class="row0-HDli row0-HDliw20"><?php echo $item->start_date;?></td>
            <td class="row0-HDli row0-HDliw20"><?php echo $item->end_date;?></td>
            <td class="row0-HDli row0-HDliw15"><?php echo $item->so_ngay_nghi;?></td>
            <td class="row0-HDli row0-HDliw15"><?php echo $item->so_ngay_con_lai;?></td>
        </tr>
        
<?php
    }
}
?>