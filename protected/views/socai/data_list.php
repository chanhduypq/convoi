<?php
$tm=Yii::app()->session['tm'];
for($i=0;$i<count($items);$i++){?>
<tr class="row-HD<?php if($items[$i]->is_edit==true) echo ' edit';if($items[$i]->thanh_toan==PaymentMethod::CHUA_THANH_TOAN) echo ' is_not_printed'?>">
        <input type="hidden" name="thuchi_id[]" value="<?php echo $items[$i]->id;?>"/>
        <td class="row0-KHli row0-HDliw5 history cursor" style="width: 4%;">
            <?php
            if($items[$i]->thanh_toan!=PaymentMethod::CHUA_THANH_TOAN){
                echo ++$index;    
            }
            else{
                echo '#';
            }
            
            ?>
        </td>
        <td class="row0-KHli row0-HDliw8">
            <?php
            if($items[$i]->thanh_toan!=PaymentMethod::CHUA_THANH_TOAN){
                echo $items[$i]->created_at;
            }            
            ?>
            
        </td>
        <td class="row0-KHli row0-HDliw16">
            <?php 
//            if($items[$i]->link!=""){
//                echo '<a href="'.$this->createUrl($items[$i]->link).'">'.nl2br($items[$i]->content).'</a>';
//            }
//            else{
//                echo nl2br($items[$i]->content);         
//            }
            echo nl2br($items[$i]->content);           
            ?>
        </td>
        <td class="row0-KHli row0-HDliw10"><?php echo nl2br($items[$i]->giao_dich);?></td>
        <td class="row0-KHli row0-HDliw12 row0-HDli0-r">&nbsp;<?php echo $items[$i]->thu;?></td>
        <td class="row0-KHli row0-HDliw12 row0-HDli0-r">&nbsp;<?php echo $items[$i]->chi;?></td>
        <td class="row0-KHli row0-HDliw10"><?php echo $items[$i]->thanh_toan_text;?></td>
        <td class="row0-KHli row0-HDliw10 row0-HDli0-r">
            <?php 
            if($items[$i]->thanh_toan!= PaymentMethod::KHONG_THANH_TOAN){
                $tm+=str_replace(".", "",$items[$i]->thu)-str_replace(".", "",$items[$i]->chi);
            } 
            
            if($items[$i]->thu==0&&$items[$i]->chi==0){
                echo $items[$i]->tm;
            }
            else{
                echo number_format($tm,0,",",".");
            }
            
//            if($i==0){
//                echo number_format(str_replace(".", "",$items[0]->thu)-str_replace(".", "",$items[0]->chi),0,",",".");
//            }
//            else{
//                echo number_format(str_replace(".", "",$items[$i]->thu)-str_replace(".", "",$items[$i]->chi)+str_replace(".", "",$items[$i-1]->thu)-str_replace(".", "",$items[$i-1]->chi),0,",",".");;
//            }
//            echo $items[$i]->tm;
            ?>
        </td>
        <td class="row0-KHli row0-HDliw8" title="<?php echo $items[$i]->tham_chieu;?>">&nbsp;
            <?php 
            if($items[$i]->link!=""){
                echo '<a href="'.$this->createUrl($items[$i]->link).'">'.FunctionCommon::crop($items[$i]->tham_chieu, 7, $after=false).'</a>';
            }
            else{
                echo FunctionCommon::crop($items[$i]->tham_chieu, 7, $after=false) ;                
            }       
            ?>
        </td>
        <td class="row0-KHli row0-HDliw10" style="vertical-align: middle;display: table-cell;margin: 0 auto; position: relative; text-align: center;padding-top: 30px;">&nbsp;
            <?php 
            echo $items[$i]->trang_thai;
            ?>
        </td>
    </tr>
<?php    
}
Yii::app()->session['tm']=$tm;
?>

