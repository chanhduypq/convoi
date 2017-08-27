<script type="text/javascript">
    
    jQuery(function($) {
        
        $("div.title-HD.sort li.title-HDli").click(function() {
            if($(this).hasClass("goods_full_name__goods_short_hand_name")){
                field="goods_full_name__goods_short_hand_name";
            }
            else if($(this).hasClass("price_number")){
                field="price_number";
            }
            else if($(this).hasClass("so_luong_da_ban_number")){
                field="so_luong_da_ban_number";
            }
            else if($(this).hasClass("so_hoa_don")){
                field="so_hoa_don";
            }
            else if($(this).hasClass("so_khach_hang")){
                field="so_khach_hang";
            }
            else if($(this).hasClass("tong_tien_number")){
                field="tong_tien_number";
            }
            
            sort("<?php echo Yii::app()->controller->id;?>",field,"goods_list_sort");                      
            
        });
        
    });
</script>