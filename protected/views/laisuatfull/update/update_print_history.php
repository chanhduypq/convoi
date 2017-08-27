<div id="div-savefix">
    <li class="li_title1">Stt</li>
    <li class="li_title1">Người thực hiện</li>
    <li class="li_title2">Nội dung</li>    
    <li class="clearfix"></li>
    
    <?php
    $DATE_FORMAT = Yii::app()->session['date_format'];
    $i = 1;
    if(is_array($created_user)&&  count($created_user)>0&&$created_user['danh_xung']!=""){
        $date=$created_user['created_at_date'];
        $temp=  explode(" - ", $date);
        $temp=  FunctionCommon::convertDateForDB($temp[0])." ".$temp[1];
        $newtime = strtotime($temp) + (7 * 60 * 60); 
        $date=date($DATE_FORMAT.' - H:i:s', $newtime);  
        ?>
        <li class="li_raw1"><?php echo $i++ . "/ " . $date; ?></li>
        <li class="li_raw1"><?php echo $created_user['danh_xung'] . " " . $created_user['full_name']; ?></li>
        <li title="" class="li_raw2"><div>Tạo mới</div></li>
        <li class="clearfix"></li>
    <?php     
    }   

    foreach ($update_histoty_array as $value) {
        $date=$value['updated_at_date'];
        $temp=  explode(" - ", $date);
        $temp=  FunctionCommon::convertDateForDB($temp[0])." ".$temp[1];
        $newtime = strtotime($temp) + (7 * 60 * 60); 
        $date=date($DATE_FORMAT.' - H:i:s', $newtime);   
        ?>
        <li class="li_raw1"><?php echo $i++ . "/ " . $date; ?></li>
        <li class="li_raw1"><?php echo $value['danh_xung'] . " " . $value['full_name']; ?></li>
        <li title="<?php echo $value['reason']; ?>" class="li_raw2"><div><?php echo FunctionCommon::crop($value['reason'], 30, true); ?></div></li>
        
        <li class="clearfix"></li>
        <?php
    }
    ?>


</div>
<li class="clearfix"></li>