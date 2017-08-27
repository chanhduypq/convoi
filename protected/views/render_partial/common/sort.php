<?php 
$this->renderPartial('//render_partial/common/sort/event_click',array('session_key'=>$session_key));
$this->renderPartial('//render_partial/common/sort/background_up_down',array('session_key'=>$session_key,'field_array'=>$field_array));
?>