<h1>Thống kê hàng hóa lưu kho</h1>

<table class="title-HD1 sort">
    <tbody id="listing_container">
        <tr class="title-HD sort">
            <td class="title-HDli title-HDliw40 goods_full_name__goods_short_hand_name">Tên hàng hóa (<label class="yes_no" id="yes">còn hàng</label> / <label class="yes_no" id="no">hết hàng</label>)</td>
            <td class="title-HDli title-HDliw10 price_number">Giá nhập</td>
            <td class="title-HDli title-HDliw10 so_luong_da_ban_number">Tồn kho</td>
            <td class="title-HDli title-HDliw5 so_hoa_don" title="Hóa đơn">HĐ</td>
            <td class="title-HDli title-HDliw5 so_to_khai" title="Tờ khai">TK</td>
            <td class="title-HDli title-HDliw5 so_khach_hang" title="Nội địa">NĐ</td>
            <td class="title-HDli title-HDliw5 so_nguoi_nuoc_ngoai" title="Quốc tế">QT</td>
            <td class="title-HDli title-HDliw20 tong_tien">Tổng tiền</td>
        </tr>
        <?php $this->renderPartial('//goodsleftfull/data_list',array('items'=>$items));?> 
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
<input type="hidden" name="all_time_common" id="all_time_common" value="1"/>
<input type="hidden" name="goodsleft_equal_0" id="goodsleft_equal_0" value="<?php echo $goodsleft_equal_0;?>"/>
<?php 
$this->renderPartial('//goodsleftfull/index/edit_goods');
//$this->renderPartial('//goodsleftfull/index/sort');
$this->renderPartial('//render_partial/common/sort',array('session_key'=>'goods_left_list_sort','field_array'=>array(
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
$this->renderPartial('//render_partial/common/delete_goods',array('from_page'=>'goodsleftfull'));
$this->renderPartial('//render_partial/common/load_more_data',array('page_count'=>  ceil($count/Yii::app()->params['number_of_items_per_page'])));
 
$this->renderPartial('//render_partial/common/popup_for_select_noidia_quocte'); 
?>

<script type="text/javascript">
    jQuery(function ($){
        if($("#goodsleft_equal_0").val()=='1'){
            $('#no').css("font-weight","bold");
        }
        else{
            $('#yes').css("font-weight","bold");
        }
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
        $("#yes").click(function (){
            $("#goodsleft_equal_0").val('0');              
            submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');
            
        });
        $("#no").click(function (){
            $("#goodsleft_equal_0").val('1');              
            submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');
            
        });
    });
</script>