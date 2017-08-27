<script type="text/javascript">    
    
    jQuery(function($) {        
<?php

$session_sort = Yii::app()->session["$session_key"];

foreach ($field_array as $value) { 
    /**
     * $field_array chứa các field trong table/view, nói đúng hơn là chứa các attribute trong model
     * ngoài ra trong $field_array có chứa một trong 3 value không có trong attribute của model:
     *     short_hand_name__full_name, goods_full_name__goods_short_hand_name, danh_xung__full_name
     *     value goods_full_name__goods_short_hand_name ứng với 3 page hàng hóa NHẬP KHO/LƯU KHO/ĐÃ BÁN
     *     value danh_xung__full_name ứng với 3 page user
     *     value short_hand_name__full_name ứng với các page còn lại
     * do đó cần xử lý riêng khi gặp 1 trong 3 value trong $field_array
     */
    //value short_hand_name__full_name
    if (strpos($session_sort, "goods_short_hand_name")=== false&&strpos($session_sort, "danh_xung")=== false&&(strpos($session_sort, "full_name")!== false||strpos($session_sort, "short_hand_name")!== false)) { 
        echo "$('.short_hand_name__full_name').addClass('sort');";
        if (strpos($session_sort, "ASC") === FALSE) {
            echo "$('.short_hand_name__full_name').addClass('down');";
        } else {
            echo "$('.short_hand_name__full_name').addClass('up');";
        } 
    }
    //value goods_full_name__goods_short_hand_name
    else if (strpos($session_sort, "goods_full_name")!== false||strpos($session_sort, "goods_short_hand_name")!== false) {
        echo "$('.goods_full_name__goods_short_hand_name').addClass('sort');";
        if (strpos($session_sort, "ASC") === FALSE) {
            echo "$('.goods_full_name__goods_short_hand_name').addClass('down');";
        } else {
            echo "$('.goods_full_name__goods_short_hand_name').addClass('up');";
        } 
    }
    //value danh_xung__full_name
    else if (strpos($session_sort, "short_hand_name")=== false&&(strpos($session_sort, "danh_xung")!== false||strpos($session_sort, "full_name")!== false)) {
        echo "$('.danh_xung__full_name').addClass('sort');";
        if (strpos($session_sort, "ASC") === FALSE) {
            echo "$('.danh_xung__full_name').addClass('down');";
        } else {
            echo "$('.danh_xung__full_name').addClass('up');";
        } 
    }
    // không rơi vào 3 value: short_hand_name__full_name, goods_full_name__goods_short_hand_name, danh_xung__full_name
    else if (preg_match("/\b$value\b/", $session_sort)){
        echo "$('.$value').addClass('sort');";
        if (strpos($session_sort, "ASC") === FALSE) {
            echo "$('.$value').addClass('down');";
        } else {
            echo "$('.$value').addClass('up');";
        }
    }
    
}

?>
    });
</script>

