<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/prettyPhoto.css" rel="stylesheet"  media="screen" />
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.prettyPhoto.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/initPrettyPhoto.js"></script>
<h1>Thống kê user</h1>
<table class="title-HD1 sort">
    <tbody id="listing_container">
        <tr class="title-HD sort">
            <td class="title-HDli title-HDliw55 danh_xung__full_name">Danh xưng -  tên đầy đủ - thông tin khác</td>
            <td class="title-HDli title-HDliw25 role">Quyền</td>                                   
            <td class="title-HDli title-HDliw20" style="background-image: none;cursor: auto;">Ảnh đại diện</td>            
        </tr>
        <?php $this->renderPartial('//user/data_list',array('items'=>$items));?> 
    </tbody>
    <tfoot>
        <?php $this->renderPartial('//render_partial/common/distance_tbody_thead_for_list_page',array('colspan'=>'3'));?>
        <tr class="all-HD">
            <td class="all-HDli row0-HDliw100" colspan="3"><?php echo $count;?></td>
        </tr>
    </tfoot>
</table>
<?php
//$this->renderPartial('//user/index/sort');
$this->renderPartial('//render_partial/common/sort',array('session_key'=>'user_list_sort','field_array'=>array(
                                                                            'danh_xung__full_name',
                                                                            'role',                                                                            
                                                                            )
    ));
$this->renderPartial('//render_partial/common/delete_user');
$this->renderPartial('//render_partial/common/load_more_data',array('page_count'=>  ceil($count/Yii::app()->params['number_of_items_per_page']))); 
 
?>
<script type="text/javascript">

    jQuery(function($) {
        
//        $("body").delegate("td.edit_user", "click", function() {
//            user_id = $(this).attr("id");
//            window.location='<?php echo $this->createUrl('/user/update'); ?>/id/'+user_id;
//        });
        $("body").delegate("td.edit_user a", "click", function() {
            user_id = $(this).parent().parent().attr("id");
            window.location='<?php echo $this->createUrl('/user/update'); ?>/id/'+user_id;
        });
        
    });
</script>