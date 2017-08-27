<script type="text/javascript">
    
    jQuery(function($) {
        
        $("div.title-HD.sort li.title-HDli").click(function() { 
            field='';
            if($(this).hasClass("danh_xung__full_name")){
                field="danh_xung__full_name";
            }
            else if($(this).hasClass("role")){
                field="role";
            }
            
            if(field!=""){
                sort("<?php echo Yii::app()->controller->id;?>",field,"user_list_sort"); 
            }       
            
        });
        
    });
</script>