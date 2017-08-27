<?php
$tm=Yii::app()->session['tm'];
for($i=0;$i<count($items);$i++){?>
<tr class="row-HD<?php if($items[$i]->is_edit==true) echo ' edit';if(trim($items[$i]->kho_hang)!="") echo ' goods';if(trim($items[$i]->is_init)=="1") echo ' is_init';?>">
        <input type="hidden" name="thuchi_id[]" value="<?php echo $items[$i]->id;?>"/>
        <input type="hidden" name="created_at[]" value="<?php echo $items[$i]->created_at;?>"/>
        <td class="row0-KHli row0-HDliw5 history cursor">
            <?php
            if($items[$i]->is_init==1&&$items[$i]->content!="Giá trị ban đầu"){
                echo "<b>".(++$index)."</b>";
            }
            else{
                echo ++$index;
            }
            
            ?>
        </td>
        <td class="row0-KHli row0-HDliw10">
            <?php
            if($items[$i]->bill_id==""&&$items[$i]->bill_input_id==""&&$items[$i]->bill_chi_phi_id==""&&$items[$i]->is_init=='0'&&$items[$i]->is_edit==true){?>                
                <?php echo $items[$i]->created_at;?>
            <?php    
            }
            else{
                if($items[$i]->is_init==1&&$items[$i]->content!="Giá trị ban đầu"){
                    echo "<b>".$items[$i]->created_at."</b>";
                }
                else{
                    echo $items[$i]->created_at;
                }                
            }
            ?>
            
        </td>
        <td class="row0-KHli row0-HDliw20">
            <?php 
            if($items[$i]->link!=""){
                echo '<a href="'.$this->createUrl($items[$i]->link).'">'.nl2br($items[$i]->content).'</a>';
            }
            else{
                if($items[$i]->is_init==1&&$items[$i]->content!="Giá trị ban đầu"){
                    echo "<b>".$items[$i]->content."</b>";
                }
                else{
                    echo nl2br($items[$i]->content);
                }
                
            }            
            ?>
        </td>
        <td class="row0-KHli row0-HDliw15 row0-HDli0-r">&nbsp;<?php echo $items[$i]->thu;?></td>
        <td class="row0-KHli row0-HDliw15 row0-HDli0-r">&nbsp;<?php echo $items[$i]->chi;?></td>
        <td class="row0-KHli row0-HDliw10">&nbsp;<?php echo nl2br($items[$i]->kho_hang);?></td>
        <td class="row0-KHli row0-HDliw25 row0-HDli0-r">
            <?php 
//            if($items[$i]->is_init==1&&$items[$i]->content!="Giá trị ban đầu"){
//                echo "<b>".$items[$i]->tm."</b>";
//            }
//            else{
//                echo $items[$i]->tm;
//            }
            
            $tm+=str_replace(".", "",$items[$i]->thu)-str_replace(".", "",$items[$i]->chi);
            
            if($items[$i]->is_init==1&&$items[$i]->content!="Giá trị ban đầu"){
                echo "<b>".number_format($tm,0,",",".")."</b>";
            }
            else{
                echo number_format($tm,0,",",".");
            }
            
            ?>
        </td>
    </tr>
<?php    
}
Yii::app()->session['tm']=$tm;
?>

