<script type="text/javascript">
    
    jQuery(function($) {
        
        $("div.title-HD.sort li.title-HDli").click(function() {
            if($(this).hasClass("short_hand_name__full_name")){
                field="short_hand_name__full_name";
            }
            else if($(this).hasClass("bill_number")){
                field="bill_number";
            }
            else if($(this).hasClass("created_at")){
                field="created_at";
            }
            else if($(this).hasClass("sum_number")){
                field="sum_number";
            }
            else if($(this).hasClass("tax_sum_number")){
                field="tax_sum_number";
            }
            else if($(this).hasClass("description")){
                field="description";
            }          
            sort("<?php echo Yii::app()->controller->id;?>",field,"invoice_input_list_sort"); 
        });
        
    });
</script>