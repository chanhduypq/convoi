
<h1>Thống kê hàng hóa đã bán<?php if($all_time_common=='0') echo ": $start_date_common - $end_date_common";?></h1>

<table class="title-HD1 sort">
    <tbody id="listing_container">
        <tr class="title-HD sort">
            <td class="title-HDli title-HDliw50 goods_full_name__goods_short_hand_name">Tên hàng hóa</td>
            <td class="title-HDli title-HDliw10 price_number">Giá bán</td>
            <td class="title-HDli title-HDliw10 so_luong_da_ban_number">SL bán</td>
            <td class="title-HDli title-HDliw5 so_hoa_don" title="Hóa đơn">HĐ</td>
            <td class="title-HDli title-HDliw5 so_khach_hang" title="Khách hàng">K/hàng</td>
            <td class="title-HDli title-HDliw20 tong_tien_number">Tổng tiền</td>
        </tr>
        <?php $this->renderPartial('//goodsfull/data_list',array('items'=>$items));?> 
    </tbody>
    <tfoot>
        <?php $this->renderPartial('//render_partial/common/distance_tbody_thead_for_list_page',array('colspan'=>'6'));?>
        <tr class="all-HD">
            <td class="all-HDli row0-HDliw50"><?php echo $count;?></td>
            <td class="all-HDli row0-HDliw10"></td>
            <td class="all-HDli row0-HDliw10"><?php echo $sum_quantity;?></td>
            <td class="all-HDli row0-HDliw5"><?php echo $bill_count;?></td>
            <td class="all-HDli row0-HDliw5"><?php echo $customer_count;?></td>
            <td class="all-HDli row0-HDliw20"><?php echo $sum;?></td>
        </tr>
    </tfoot>
</table>

<input type="hidden" name="start_date_common" id="start_date_common" value="<?php echo $start_date_common;?>"/>
<input type="hidden" name="end_date_common" id="end_date_common" value="<?php echo $end_date_common;?>"/>
<input type="hidden" name="customer_id_common" id="customer_id_common" value="<?php echo $customer_id_common;?>"/>
<input type="hidden" name="goods_id_common" id="goods_id_common" value="<?php echo $goods_id_common;?>"/>
<input type="hidden" name="all_time_common" id="all_time_common" value="<?php echo $all_time_common;?>"/>
<?php
$this->renderPartial('//goodsfull/index/edit_goods');
$this->renderPartial('//render_partial/common/sort',array('session_key'=>'goods_list_sort','field_array'=>array(
                                                                            'goods_full_name__goods_short_hand_name',
                                                                            'price_number',
                                                                            'so_luong_da_ban_number',
                                                                            'so_hoa_don',
                                                                            'so_khach_hang',
                                                                            'tong_tien_number',                                                                            
                                                                            )
    ));
$this->renderPartial('//render_partial/common/delete_goods',array('from_page'=>'goodsfull'));
$this->renderPartial('//render_partial/common/load_more_data',array('page_count'=>  ceil($count/Yii::app()->params['number_of_items_per_page'])));
 
$this->renderPartial('//render_partial/common/popup_for_select_noidia_quocte'); 
?>
<script type="text/javascript">
    jQuery(function ($){
        $("body").delegate("td.cursor", "click", function() {
            if($(this).find("a").length==1){//link số hóa đơn/ số tờ khai
                if ($(this).find("a").eq(0).hasClass("invoice")) {//link đến page hóa đơn
                    action_for_form_common="<?php echo $this->createUrl('/invoicefull/index'); ?>";
                }
                else if ($(this).find("a").eq(0).hasClass("customer")) {//link đến page hàng hóa đã bán
                    action_for_form_common="<?php echo $this->createUrl('/customerfull/index'); ?>";
                }
                id_str = $(this).parent().find("td").eq(0).attr("id");
                $("#goods_id_common").val(id_str);
                submit_form_common(action_for_form_common,'<?php echo $this->createUrl("/ajax/search"); ?>');
            }
        });
        $("#all_time").click(function (){
            if($(this).is(':checked')){   
                $("#all_time_common").val('1');                
            }
            else{
                $("#all_time_common").val('0');                
            }
            submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');
            
        });
    });
</script>