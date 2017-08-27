<div id="div_loading_common" style="position: absolute;z-index: 99999;display: none;">
    <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
</div> 
<h1>Hóa đơn</h1>

<!-- Hoá đơn -->
<div id="w-admin-hd">
    <?php $this->renderPartial('//system/index/setting_bill_type',array('bill_type_array'=>$bill_type_array)); ?>    
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<hr class="system">
<!-- End Hoá đơn -->

<h1>Phương thức tính</h1>
<!-- Phương thức tính -->
<div id="w-admin-ptt">
    <?php $this->renderPartial('//system/index/setting_calculate_way'); ?>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<hr class="system">
<!-- End Phương thức tính -->


<!--<h1>Lưu hoá đơn</h1>
 Lưu hoá đơn 
<div id="w-admin-nt">
    <?php //$this->renderPartial('//system/index/setting_path_for_save_bill',array('path_for_save_bill'=>$path_for_save_bill)); ?>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<hr>-->
<!-- End Lưu hoá đơn -->
<!-- Ngày tháng -->
<h1>Ngày tháng</h1>
<div id="w-admin-nt">
    <?php $this->renderPartial('//system/index/setting_date_format'); ?>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<hr class="system">

<h1>Thứ tự hiển thị menu</h1>
<div style="width: 100%;margin:0 0 30px 0;">
    <?php $this->renderPartial('//system/index/setting_menu'); ?>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<hr class="system">
<!-- End Ngày tháng -->

<!--<h1>Layout</h1>
 Layout 
<div id="w-admin-lo">
    <li class="lo1">Lựa chọn layout để sử dụng cho thuận tiện.</li>
    <li class="clearfix"></li>

    <li class="lo2"><input type="radio" name="Radio" ></li>
    <li class="lo3"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/layout01.jpg" width="100" height="82" alt="" /></li>
    <li class="lo2"><input type="radio" name="Radio" ></li>
    <li class="lo3"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/layout01.jpg" width="100" height="82" alt="" /></li>
    <li class="clearfix"></li>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<hr>
 End Layout 

<h1>Màu sắc</h1>
 Màu sắc 
<div id="w-admin-lo">
    <li class="lo1">Tuỳ chọn màu sắc cho giao diện phần mềm in hoá đơn.</li>
    <li class="clearfix"></li>

    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color01.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color02.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color03.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color04.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color05.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color06.jpg" width="33" height="33" alt="" /></a></li>
    <li class="clearfix"></li>

    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color07.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color08.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color09.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color10.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color11.jpg" width="33" height="33" alt="" /></a></li>
    <li class="lo4"><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/color12.jpg" width="33" height="33" alt="" /></a></li>
    <li class="clearfix"></li>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>-->
<div class="clearfix"></div>