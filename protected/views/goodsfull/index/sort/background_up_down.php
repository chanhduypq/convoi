<script type="text/javascript">    
    jQuery(function($) {        
<?php
$session_sort = Yii::app()->session['goods_list_sort'];
if (strpos($session_sort, "goods_full_name")!== false||strpos($session_sort, "goods_short_hand_name")!== false) {    
    echo "$('.goods_full_name__goods_short_hand_name').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.goods_full_name__goods_short_hand_name').addClass('down');";
    } else {
        echo "$('.goods_full_name__goods_short_hand_name').addClass('up');";
    }
} 
else if (strpos($session_sort, "price_number")!== false) {
    echo "$('.price_number').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.price_number').addClass('down');";
    } else {
        echo "$('.price_number').addClass('up');";
    }
}
else if (strpos($session_sort, "so_luong_da_ban_number")!== false) {
    echo "$('.so_luong_da_ban_number').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.so_luong_da_ban_number').addClass('down');";
    } else {
        echo "$('.so_luong_da_ban_number').addClass('up');";
    }
}
else if (strpos($session_sort, "so_hoa_don")!== false) {
    echo "$('.so_hoa_don').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.so_hoa_don').addClass('down');";
    } else {
        echo "$('.so_hoa_don').addClass('up');";
    }
}
else if (strpos($session_sort, "so_khach_hang")!== false) {
    echo "$('.so_khach_hang').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.so_khach_hang').addClass('down');";
    } else {
        echo "$('.so_khach_hang').addClass('up');";
    }
}
else if (strpos($session_sort, "tong_tien_number")!== false) {
    echo "$('.tong_tien_number').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.tong_tien_number').addClass('down');";
    } else {
        echo "$('.tong_tien_number').addClass('up');";
    }
}
?>
    });
</script>

