<script type="text/javascript">
    
    jQuery(function($) {
        /**
         * 
         * table.title-HD1.sort td.title-HDli1 là xử lý riêng cho trang sổ cái, chỉ sắp xếp tăng/giảm theo ngày tháng
         * trang sổ cái mấy td của header không còn là class title-HDli mà là title-HDli1
         */
        $("table.title-HD1.sort td.title-HDli,table.title-HD1.sort td.title-HDli1").click(function() {
            /**
             * đây là xử lý riêng cho trang sổ cái, chỉ sắp xếp tăng/giảm theo ngày tháng
             */
            if($(this).hasClass("title-HDli1")&&!$(this).hasClass("created_at")){
                return;
            }
            field='';
            attr_class=$.trim($(this).attr('class'));
            class_array=attr_class.split(/[\s]+/);
            for(i=0;i<class_array.length;i++){
                if(class_array[i].indexOf('title')==-1&&class_array[i]!='date'){
                    field=class_array[i];
                    break;
                }
            }            
            if(field!=''){                
                sort("<?php echo Yii::app()->controller->id;?>",field,"<?php echo $session_key;?>");
            }
            
        });
    });
</script>