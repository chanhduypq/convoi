<h1>Thống kê bán hàng không xuất HĐ<?php if($all_time_common=='0') echo ": $start_date_common - $end_date_common";?></h1>
<table class="title-HD1 sort">
    <tbody id="listing_container">
        <tr class="title-HD sort">
            <td class="title-HDli title-HDliw10 stt"><p class="ptitle">STT</p> <p class="pupdown"></p> <p class="pupdown"></p></td>
            <td class="title-HDli title-HDliw10 date created_at">Ngày</td>
            <td class="title-HDli title-HDliw40 short_hand_name__full_name">Khách hàng</td>
            <td class="title-HDli title-HDliw15 sum_and_sumtax_number">Tổng tiền</td>
            <td class="title-HDli title-HDliw10 tax_sum_number">Thuế</td>
            <td class="title-HDli title-HDliw15 description">Nội dung</td>    
        </tr>
        <?php $this->renderPartial('//kxhdfull/data_list',array('items'=>$items,'action'=>$action));?> 
    </tbody>
    <tfoot>
        <?php $this->renderPartial('//render_partial/common/distance_tbody_thead_for_list_page',array('colspan'=>'6'));?>
        <tr class="all-HD">
            <td class="all-HDli row0-HDliw10"><?php echo $bill_count;?></td>
            <td class="all-HDli row0-HDliw10"></td>
            <td class="all-HDli row0-HDliw40" style="border-left: none;"></td>
            <td class="all-HDli row0-HDliw15"><?php echo $sum_and_sumtax;?></td>
            <td class="all-HDli row0-HDliw10"><?php echo $tax_sum;?></td>
            <td class="all-HDli row0-HDliw15">&nbsp;</td>
    </tr>
    </tfoot>
</table>
<input type="hidden" name="start_date_common" id="start_date_common" value="<?php echo $start_date_common;?>"/>
<input type="hidden" name="end_date_common" id="end_date_common" value="<?php echo $end_date_common;?>"/>
<input type="hidden" name="customer_id_common" id="customer_id_common" value="<?php echo $customer_id_common;?>"/>
<input type="hidden" name="goods_id_common" id="goods_id_common" value="<?php echo $goods_id_common;?>"/>
<input type="hidden" name="all_time_common" id="all_time_common" value="<?php echo $all_time_common;?>"/>
<input type="hidden" name="id" id="bill_id"/>
<?php 
$this->renderPartial('//render_partial/common/load_more_data',array('page_count'=>  ceil($bill_count/Yii::app()->params['number_of_items_per_page']))); 
$this->renderPartial('//render_partial/common/sort',array('session_key'=>'kxhd_list_sort','field_array'=>array(
                                                                            'stt',
                                                                            'created_at',
                                                                            'short_hand_name__full_name',
                                                                            'sum_and_sumtax_number',
                                                                            'tax_sum_number',
                                                                            'description', 
                                                                            )
    ));
$this->renderPartial('//kxhdfull/index/popup_for socai'); 
?>
<script type="text/javascript">
    jQuery(function ($){
        $("body").delegate("td.cursor", "click", function() {
            if($(this).find("a").length==1){//link số hóa đơn/ số tờ khai
                a_node=$(this).find("a").eq(0);
                if($(a_node).attr("href")!=""){
                    window.location=$(a_node).attr("href");
                }
            }
        });
        $("body").delegate("td.cursor1 a", "click", function() {
            id_str = $(this).parent().parent().attr("id");
            $("#customer_id_common").val(id_str);                
            submit_form_common("<?php echo $this->createUrl('/customerkxhdfull/index'); ?>","<?php echo $this->createUrl("/ajax/search"); ?>");
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