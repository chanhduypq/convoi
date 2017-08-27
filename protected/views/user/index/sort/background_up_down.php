<script type="text/javascript">    
    jQuery(function($) {        
<?php
$session_sort = Yii::app()->session['user_list_sort'];
if (strpos($session_sort, "danh_xung")!== false||strpos($session_sort, "full_name")!== false) {    
    echo "$('.danh_xung__full_name').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.danh_xung__full_name').addClass('down');";
    } else {
        echo "$('.danh_xung__full_name').addClass('up');";
    }
} 
else if (strpos($session_sort, "role")!== false) {
    echo "$('.role').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.role').addClass('down');";
    } else {
        echo "$('.role').addClass('up');";
    }
}
?>
    });
</script>

