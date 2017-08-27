<script type="text/javascript">    
    jQuery(function($) {        
<?php
$session_sort = Yii::app()->session['supplier_list_sort'];
if (strpos($session_sort, "short_hand_name")!== false||strpos($session_sort, "full_name")!== false) {    
    echo "$('.short_hand_name__full_name').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.short_hand_name__full_name').addClass('down');";
    } else {
        echo "$('.short_hand_name__full_name').addClass('up');";
    }
} 
else if (strpos($session_sort, "bill_count")!== false) {
    echo "$('.bill_count').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.bill_count').addClass('down');";
    } else {
        echo "$('.bill_count').addClass('up');";
    }
}
else if (strpos($session_sort, "quantity")!== false) {
    echo "$('.quantity').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.quantity').addClass('down');";
    } else {
        echo "$('.quantity').addClass('up');";
    }
}
else if (strpos($session_sort, "tong_tien")!== false) {
    echo "$('.tong_tien').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.tong_tien').addClass('down');";
    } else {
        echo "$('.tong_tien').addClass('up');";
    }
}
?>
    });
</script>

