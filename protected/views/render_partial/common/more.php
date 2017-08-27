<?php 
$params=array();
$params['items'] = $items;  
if(isset($action)){
    $params['action']=  $action;
}
if(isset($index)){
    $params['index']=$index;
}
$this->renderPartial('//'.Yii::app()->controller->id.'/data_list',$params);
?>