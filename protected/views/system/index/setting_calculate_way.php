<?php
$calculate_way = Yii::app()->db->createCommand()
        ->select("way")
        ->from("calculate_way")        
        ->queryScalar();
?>
<li class="ptt1">Lựa chọn phương thức tính tổng tiền trên hoá đơn.</li>
<li class="ptt2">
    <label class="label_for">
        <input type="radio" name="calculate_way" value="1"<?php if ($calculate_way == '1') echo ' checked="checked"'; ?>> Làm tròn tổng tiền
    </label>
</li>
<li class="ptt2">
    <label class="label_for">
        <input type="radio" name="calculate_way" value="2"<?php if ($calculate_way == '2') echo ' checked="checked"'; ?>> Không làm tròn tổng tiền
    </label>
</li>
<li class="but-save" id="save_calculate_way"><a><?php echo Yii::app()->params['text_for_button_save']; ?></a></li>
<div class="middle saved">
    <label>Lưu thành công</label>
</div>
<!--<img style="width: 35px;height: 35px;margin-left: 30px;margin-top: 20px;display: none;" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/checked_icon.png"/>-->
<li class="clearfix"></li>

<script type="text/javascript">
    jQuery(function($) {
        $("#save_calculate_way").next("div").hide();
        $("#save_calculate_way").click(function() {
            saveDataFormat();
        });

        function saveDataFormat() {
            $("#div_loading_common").css("top",$("#save_calculate_way").offset().top-220).show();            
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/ajax/savecalculateway'); ?>',
                data: {
                    way: $('#w-admin-ptt input[name="calculate_way"]:checked').val()
                },
                success: function(data, textStatus, jqXHR) {
                    $("#div_loading_common").hide();
                    $("#save_calculate_way").next("div").show(500,function(){
                        setTimeout(function (){
                            $("#save_calculate_way").next("div").hide();
                        },2000);
                    });                    
                }
            });
        }

    });

</script>

