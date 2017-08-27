<script type="text/javascript">    
    jQuery(function($) {        
<?php
$session_sort = Yii::app()->session['invoice_list_sort'];
if (strpos($session_sort, "full_name")!== false||strpos($session_sort, "short_hand_name")!== false) {    
    echo "$('.short_hand_name__full_name').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.short_hand_name__full_name').addClass('down');";
    } else {
        echo "$('.short_hand_name__full_name').addClass('up');";
    }
} 
else if (strpos($session_sort, "bill_number")!== false) {
    echo "$('.bill_number').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.bill_number').addClass('down');";
    } else {
        echo "$('.bill_number').addClass('up');";
    }
}
else if (strpos($session_sort, "created_at")!== false) {
    echo "$('.created_at').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.created_at').addClass('down');";
    } else {
        echo "$('.created_at').addClass('up');";
    }
}
else if (strpos($session_sort, "tax_sum_number")!== false) {
    echo "$('.tax_sum_number').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.tax_sum_number').addClass('down');";
    } else {
        echo "$('.tax_sum_number').addClass('up');";
    }
}
else if (strpos($session_sort, "sum_number")!== false) {
    echo "$('.sum_number').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.sum_number').addClass('down');";
    } else {
        echo "$('.sum_number').addClass('up');";
    }
}
else if (strpos($session_sort, "count_lien1")!== false) {
    echo "$('.count_lien1').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.count_lien1').addClass('down');";
    } else {
        echo "$('.count_lien1').addClass('up');";
    }
}
else if (strpos($session_sort, "count_lien2")!== false) {
    echo "$('.count_lien2').addClass('sort');";
    if (strpos($session_sort, "ASC") === FALSE) {
        echo "$('.count_lien2').addClass('down');";
    } else {
        echo "$('.count_lien2').addClass('up');";
    }
}
?>
    });
</script>

