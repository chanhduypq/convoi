<?php
        
if(count($items)>0){
    foreach ($items as $item) {
        ?>
        
        
        <tr class="row-HD">
            <td class="row0-KHli row0-HDliw55 row0-HDli0 edit_user" id="<?php echo $item->id;?>">                
                    <b>
                        <a><?php echo $item->danh_xung.' '.$item->full_name;?></a>
                    </b>
                    <br/>
                    Địa chỉ: <?php echo $item->address;?>                    
                    <br/>
                    <?php echo $item->phone_email_birthday;?>                   
            </td>
            <td class="row0-KHli row0-HDliw25 row0-HDli0-r">    
                <?php echo $item->role;?>
            </td>

                                           
            <td class="row0-KHli row0-HDliw20 row0-HDli0-rb ptitle center middle<?php if(Yii::app()->session['user_id']!=$item->id){?> delete cancel_user<?php }?>">
                        <p class="ptitle center middle">
                        <?php
                        if($item->photo!=""){
//                            $url = ltrim('/upload/' . $item->photo, '/');
//                            $size = getimagesize($url);            
//                            $w = $size[0];
//                            $h = $size[1];
//                            $ratio=  $h/$w;
//                            $w=70;
//                            $h=ceil($w*$ratio);
                            ?>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/upload/<?php echo $item->photo;?>" rel="prettyPhoto">
                                <img src="<?php echo Yii::app()->baseUrl; ?>/upload/<?php echo $item->photo;?>" style="width: <?php echo 70;?>px;height: <?php echo 70;?>px;border-radius: 50%;">
                            </a>
                        <?php }
                        else{
                            echo '&nbsp;';
                        }
                        ?>

                        </p>               
            </td>           
        </tr>
        
<?php
    }
}
?>