<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery-ui.css" />
<form id="form_menu" method="POST" action="<?php echo $this->createUrl('/ajax/savemenu'); ?>">
<li class="nt1">Thứ tự hiển thị menu.</li>
<ul id="sortable">
<?php
$menus=Yii::app()->db->createCommand()->select()->from("menu")->where("is_parent=1")->order("order")->queryAll();
foreach ($menus as $menu) {
?>
    <li class="ui-state-default" style="margin-bottom: 5px;width: 50%;">
        <input type="hidden" name="parent[]" value="<?php echo $menu['id'];?>"/>
        <?php 
        echo $menu['text'];
        $menus_temp=Yii::app()->db->createCommand()->select()->from("menu")->where("parent_id=".$menu['id'])->order("order")->queryAll();
        ?>
        <ul class="ui-sortable sortable" style="margin-left: 25%;">
            <?php
            foreach ($menus_temp as $menu_temp) {?>
            <li class="ui-state-default" style="margin-bottom: 5px;width: 50%;">
                <input type="hidden" name="child[]" value="<?php echo $menu_temp['id'];?>"/>
                <?php echo $menu_temp['text'];?>
            </li>
            <?php
            }
            ?>
        </ul>
    </li>    
<?php
}
?>
</ul>       
<li class="clearfix"></li>
<li class="but-save" id="save_date_format1"><a><?php echo Yii::app()->params['text_for_button_save']; ?></a></li>
<div class="middle saved">
    <label>Lưu thành công</label>
</div>
<!--<img style="width: 35px;height: 35px;margin-left: 30px;margin-top: 20px;display: none;" src="<?php echo Yii::app()->theme->baseUrl;?>/images/icon/checked_icon.png"/>-->
<li class="clearfix"></li>
</form>
<script type="text/javascript">
    jQuery(function($) {
        $( "#sortable" ).sortable({
          placeholder: "ui-state-highlight"
        });
        $( "#sortable" ).disableSelection();
        $( ".sortable" ).sortable({
          placeholder: "ui-state-highlight"
        });
        $( ".sortable" ).disableSelection();
        $("#save_date_format1").next("div").hide();

        $("#save_date_format1").click(function() {   
//            $("#form_menu").submit();
            saveMenu();
        });

        function saveMenu() {
            $("#div_loading_common").css("top",$("#save_date_format1").offset().top-300).show();            
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/ajax/savemenu'); ?>',
                data: $("#form_menu").serialize(),
                success: function(data, textStatus, jqXHR) {
                    
                    $("#div_loading_common").hide();                    
                    $("#save_date_format1").next("div").show(500,function(){
                        setTimeout(function (){
                            window.location.reload();
                        },2000);
                    }); 
                }
            });
        }

    });

</script>