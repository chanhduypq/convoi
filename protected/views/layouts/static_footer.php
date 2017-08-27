<script type="text/javascript">
    jQuery(function ($){  
        $('table.sort').filterTable({minRows:2,label:'Tìm kiếm:',placeholder:'Nhập thông tin cần tìm'});
        
        $("textarea").not('#content11').textareaExpander();
//        $("body").delegate(".numeric", "focusin", function(){
//            $('.numeric').number(true);
//        });
        $('.numeric').number(true);
        
        /**
         * tất cả những nơi nhập thuế (vat,ttdb,nk) thi không được vượt quá 100
         * nếu user nhập vượt quá 100 thi hệ thống se tự động cho nó về giá trị 100
         */
        $("#tax_vat,#tax_ttdb,#tax_nk,#tax,#tax1").keyup(function() {
            if ($(this).val() > 100)
            {
                $(this).val('100');
            }
            else if ($(this).val() < 0)
            {
                $(this).val(0);
            }
        });
        /**
         * độ rộng của footer luôn bằng độ rộng của menu
         */   
        width=$("#footer").width();
        width-=10;
        $("#footer").css("width",width+"px");
        /**
         * nếu là page index nằm trong danh sách page controller_list_for_show_list (xem tại file params.php để biet danh sách này)
         */
        <?php 
        if(Yii::app()->controller->action->id == "index"&&in_array(Yii::app()->controller->id,Yii::app()->params['controller_list_for_show_list'])){?>
            setSameWidthForColumn($("tr.title-HD.sort td"),$("tr.all-HD td"),$("tr.title-HD.sort"),$("tr.all-HD"));
        <?php 
        }
        ?>
        /**
         * user đang ở page create/update user/hóa đơn bán hàng/...
         * luôn có một nút back phía trên cùng để quay về trang index user/hóa đơn bán hàng/...
         * nút đó chính là div.back_button
         */
       $("div.back_button").click(function (){
           window.location='<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>';
       }); 
       /**
        * user thay đổi kích thước màn hình
        */
       $(window).on('resize', function() {
            /**
             * nếu là page index nằm trong danh sách page controller_list_for_show_list (xem tại file params.php để biet danh sách này)
             */
            <?php 
            if(Yii::app()->controller->action->id == "index"&&in_array(Yii::app()->controller->id,Yii::app()->params['controller_list_for_show_list'])){?>
            setSameWidthForColumn($("tr.title-HD.sort td"),$("tr.all-HD td"),$("tr.title-HD.sort"),$("tr.all-HD"));
            if (page <= page_count) {
                loadnewdata();
                page++;
            }
            <?php 
            }
            ?>
            /**
             * độ rộng của footer luôn bằng độ rộng của menu khi user thay đổi màn hình
             */            
            width = $("#nav-left").width();
            width -= 10;
            $("#footer").css("width", width + "px");
        });
    });
</script>
