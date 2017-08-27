<div id="div-savefix">
    <li class="li_title1">Stt</li>
    <li class="li_title1">Người thực hiện</li>
    <li class="li_title2">Nội dung</li>
    <li class="li_title3">Liên 1</li>
    <li class="li_title4">Liên 2</li>
    <li class="clearfix"></li>
    
    <?php
    $DATE_FORMAT = Yii::app()->session['date_format'];
    $i = 1;
//    if(is_array($created_user)&&  count($created_user)>0&&$created_user['danh_xung']!=""){
//        $date=$created_user['created_at_date'];
//        $temp=  explode(" - ", $date);
//        $temp=  FunctionCommon::convertDateForDB($temp[0])." ".$temp[1];
//        $newtime = strtotime($temp) + (7 * 60 * 60); 
//        $date=date($DATE_FORMAT.' - H:i:s', $newtime);  
        ?>
<!--        <li class="li_raw1"><?php // echo $i++ . "/ " . $date; ?></li>
        <li class="li_raw1"><?php // echo $created_user['danh_xung'] . " " . $created_user['full_name']; ?></li>
        <li title="" class="li_raw2"><div>Tạo mới</div></li>
        <li class="li_raw3">X</li>
        <li class="li_raw4">X</li>
        <li class="clearfix"></li>-->
    <?php     
//    }
    foreach ($histoty_array as $value) {  
        $date=$value['date'];
        $temp=  explode(" - ", $date);
        $temp=  FunctionCommon::convertDateForDB($temp[0])." ".$temp[1];
        $newtime = strtotime($temp) + (7 * 60 * 60); 
        $date=date($DATE_FORMAT.' - H:i:s', $newtime);   
        ?>
        <li class="li_raw1"><?php echo $i++ . "/ " . $date; ?></li>
        <li class="li_raw1"><?php echo $value['danh_xung'] . " " . $value['full_name']; ?></li>
        <li title="<?php echo $value['reason']; ?>" class="li_raw2"><div class="link" id="<?php echo $value['id']; ?>"><?php echo FunctionCommon::crop($value['reason'], 30, true); ?></div></li>
        <li class="li_raw3"><?php echo $value['count_lien1']; ?></li>
        <li class="li_raw4"><?php echo $value['count_lien2']; ?></li>
        <li class="clearfix"></li>
        <?php
    }
    ?>


</div>
<li class="clearfix"></li>