<script type="text/javascript">
    
    jQuery(function($) {
        
        $("div.title-HD.sort li.title-HDli").click(function() {
            if($(this).hasClass("short_hand_name__full_name")){
                field="short_hand_name__full_name";
            }
            else if($(this).hasClass("bill_count")){
                field="bill_count";
            }
            else if($(this).hasClass("quantity")){
                field="quantity";
            }
            else if($(this).hasClass("tong_tien")){
                field="tong_tien";
            }
            sort("<?php echo Yii::app()->controller->id;?>",field,"supplier_list_sort"); 
        });
        
    });
</script>