<li class="nt1">Lựa chọn phương thức tính tổng tiền trên hoá đơn.</li>
<?php
$date_format_id = Yii::app()->db->createCommand()
        ->select("id")
        ->from("date_format")
        ->where("active=1")
        ->queryScalar();
?>
<li class="nt2">
    <label class="label_for">
        <input type="radio" name="date_format_id" value="1" <?php if ($date_format_id == '1') echo ' checked="checked"'; ?>>
        Năm.Tháng.Ngày
        <?php echo date("Y.m.d"); ?>
    </label>
</li>
<li class="clearfix"></li>
<li class="nt2">
    <label class="label_for">
        <input type="radio" name="date_format_id" value="2" <?php if ($date_format_id == '2') echo ' checked="checked"'; ?>>
        Năm/Tháng/Ngày
        <?php echo date("Y/m/d"); ?>
    </label>
</li>
<li class="clearfix"></li>
<li class="nt2">
    <label class="label_for">
        <input type="radio" name="date_format_id" value="4" <?php if ($date_format_id == '4') echo ' checked="checked"'; ?>>
        Năm-Tháng-Ngày
        <?php echo date("Y-m-d"); ?>
    </label>
</li>
<li class="clearfix"></li>
<li class="nt2">
    <label class="label_for">
        <input type="radio" name="date_format_id" value="3" <?php if ($date_format_id == '3') echo ' checked="checked"'; ?>>
        NămThángNgày
        <?php echo date("Ymd"); ?>
    </label>
</li>           
<li class="clearfix"></li>
<li class="but-save" id="save_date_format"><a><?php echo Yii::app()->params['text_for_button_save']; ?></a></li>
<div class="middle saved">
    <label>Lưu thành công</label>
</div>
<!--<img style="width: 35px;height: 35px;margin-left: 30px;margin-top: 20px;display: none;" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/checked_icon.png"/>-->
<li class="clearfix"></li>

<script type="text/javascript">
    jQuery(function($) {
        $("#save_date_format").next("div").hide();

        $("#save_date_format").click(function() {
            saveDataFormat();
        });

        function saveDataFormat() {
            $("#div_loading_common").css("top",$("#save_date_format").offset().top-300).show();            
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/ajax/savedateformat'); ?>',
                data: {
                    date_format_id: $('#w-admin-nt input[name="date_format_id"]:checked').val()
                },
                success: function(data, textStatus, jqXHR) {
                    $("#div_loading_common").hide();                    
                    $("#save_date_format").next("div").show(500,function(){
                        setTimeout(function (){
                            $("#save_date_format").next("div").hide();
                        },2000);
                    }); 
                }
            });
        }

    });

</script>