
<h1>Thống kê hàng hóa đã nhập<?php if($all_time_common=='0') echo ": $start_date_common - $end_date_common";?></h1>

<table class="title-HD1 sort">
    <tbody id="listing_container">
        <tr class="title-HD sort">
            <td class="title-HDli title-HDliw40 goods_full_name__goods_short_hand_name">Tên hàng hóa</td>
            <td class="title-HDli title-HDliw10 price_number">Giá nhập</td>
            <td class="title-HDli title-HDliw10 so_luong_da_ban_number" title="Số lượng nhập">SL nhập</td>
            <td class="title-HDli title-HDliw5 so_hoa_don" title="Hóa đơn">HĐ</td>
            <td class="title-HDli title-HDliw5 so_to_khai" title="Tờ khai">TK</td>
            <td class="title-HDli title-HDliw5 so_khach_hang" title="Nội địa">NĐ</td>
            <td class="title-HDli title-HDliw5 so_nguoi_nuoc_ngoai" title="Quốc tế">QT</td>
            <td class="title-HDli title-HDliw20 tong_tien_number">Tổng tiền</td>
        </tr>
        
        <?php $this->renderPartial('//goodsinputfull/data_list',array('items'=>$items));?>   
        
    </tbody>
    <tfoot>
        <?php $this->renderPartial('//render_partial/common/distance_tbody_thead_for_list_page',array('colspan'=>'8'));?>
        <tr class="all-HD">
            <td class="all-HDli row0-HDliw40"><?php echo $count;?></td>
            <td class="all-HDli row0-HDliw10"></td>
            <td class="all-HDli row0-HDliw10"><?php echo $sum_quantity;?></td>
            <td class="all-HDli row0-HDliw5"><?php echo $bill_count;?></td>
            <td class="all-HDli row0-HDliw5"><?php echo $bill_count_is_international;?></td>
            <td class="all-HDli row0-HDliw5"><?php echo $customer_count;?></td>
            <td class="all-HDli row0-HDliw5"><?php echo $customer_count_is_international;?></td>
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
$this->renderPartial('//goodsinputfull/index/edit_goods');
//$this->renderPartial('//goodsinputfull/index/sort');
$this->renderPartial('//render_partial/common/sort',array('session_key'=>'goods_input_list_sort','field_array'=>array(
                                                                            'goods_full_name__goods_short_hand_name',
                                                                            'price_number',
                                                                            'so_luong_da_ban_number',
                                                                            'so_hoa_don',
                                                                            'so_to_khai',
                                                                            'so_khach_hang',
                                                                            'so_nguoi_nuoc_ngoai',
                                                                            'tong_tien_number',
                                                                            )
    ));
$this->renderPartial('//render_partial/common/delete_goods',array('from_page'=>'goodsinputfull'));
$this->renderPartial('//render_partial/common/load_more_data',array('page_count'=>  ceil($count/Yii::app()->params['number_of_items_per_page'])));
 
$this->renderPartial('//render_partial/common/popup_for_select_noidia_quocte'); 
?>
<script type="text/javascript">
    jQuery(function ($){
        $("body").delegate("td.cursor", "click", function() {
            if($(this).find("a").length==1){//link số hóa đơn/ số tờ khai
                if ($(this).find("a").eq(0).hasClass("so_hoa_don")) {//link đến page hóa đơn                
                    action_for_form_common="<?php echo $this->createUrl('/invoiceinputfull/index'); ?>";
                }
                else if ($(this).find("a").eq(0).hasClass("so_to_khai")) {//link đến page hàng hóa đã bán                
                    action_for_form_common="<?php echo $this->createUrl('/internationalinput/index'); ?>";
                }
                else if ($(this).find("a").eq(0).hasClass("supplier")) {//link đến page hóa đơn               
                    action_for_form_common="<?php echo $this->createUrl('/supplierfull/index'); ?>";
                }
                else if ($(this).find("a").eq(0).hasClass("so_nguoi_nuoc_ngoai")) {//link đến page hàng hóa đã bán              
                    action_for_form_common="<?php echo $this->createUrl('/international/index'); ?>";
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