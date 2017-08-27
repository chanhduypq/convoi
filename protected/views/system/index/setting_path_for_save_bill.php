<li class="nt1">
    Thư mục lưu hoá đơn. <input name="path" class="save-hoadon" type="text" value="<?php echo $path_for_save_bill;?>">
    <div class="error path"></div>
</li>
<li class="clearfix"></li>
<li class="but-save" id="save_path_for_save_bill"><a><?php echo Yii::app()->params['text_for_button_save']; ?></a></li>
<div class="middle saved">
    <label>Lưu thành công</label>
</div>
<!--<img style="width: 35px;height: 35px;margin-left: 30px;margin-top: 20px;display: none;" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/checked_icon.png"/>-->
<li class="clearfix"></li>
<script type="text/javascript">
    jQuery(function($) {
        $("#save_path_for_save_bill").next("div").hide();
        $("div.error.path").html('').hide();
        $("#save_path_for_save_bill").click(function() {
            saveDataFormat();
        });

        function saveDataFormat() {
            $("div.error.path").html('').hide();
            $("#div_loading_common").css("top",$("#save_path_for_save_bill").offset().top-200).show();            
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/ajax/savepathforsavebill'); ?>',
                data: {
                    path: $('#w-admin-nt input[name="path"]').val()
                },
                success: function(data, textStatus, jqXHR) {
                    $("#div_loading_common").hide();
                    if($.trim(data)!=""){
                        $("div.error.path").html(data).show();
                    }
                    else{
                        $("#save_path_for_save_bill").next("div").show(500,function(){
                            setTimeout(function (){
                                $("#save_path_for_save_bill").next("div").hide();
                            },2000);
                        });                         
                    }
                }
            });
        }

    });

</script>