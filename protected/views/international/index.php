<h1>Thống kê <?php echo lcfirst(Yii::app()->params['label_for_supplier']);?> quốc tế<?php if($all_time_common=='0') echo ": $start_date_common - $end_date_common";?></h1>
<table class="title-HD1 sort">
    <tbody id="listing_container">
        <tr class="title-HD sort">
            <td class="title-HDli title-HDliw65 short_hand_name__full_name"><?php echo Yii::app()->params['label_for_supplier'];?></td>
            <td class="title-HDli title-HDliw10 bill_count">Tờ khai</td>
            <td class="title-HDli title-HDliw10 quantity">Hàng hóa</td>
            <td class="title-HDli title-HDliw15 tong_tien">Tổng tiền</td>
        </tr>
        <?php $this->renderPartial('//international/data_list',array('items'=>$items));?> 
    </tbody>
    <tfoot>
        <?php $this->renderPartial('//render_partial/common/distance_tbody_thead_for_list_page',array('colspan'=>'4'));?>
        <tr class="all-HD">
            <td class="all-HDli row0-HDliw65"><?php echo $count;?></td>
            <td class="all-HDli row0-HDliw10"><?php echo $bill_count;?></td>
            <td class="all-HDli row0-HDliw10"><?php echo $sum_quantity;?></td>
            <td class="all-HDli row0-HDliw15"><?php echo $sum;?></td>
        </tr>
    </tfoot>
</table>
<input type="hidden" name="start_date_common" id="start_date_common" value="<?php echo $start_date_common;?>"/>
<input type="hidden" name="end_date_common" id="end_date_common" value="<?php echo $end_date_common;?>"/>
<input type="hidden" name="customer_id_common" id="customer_id_common" value="<?php echo $customer_id_common;?>"/>
<input type="hidden" name="goods_id_common" id="goods_id_common" value="<?php echo $goods_id_common;?>"/>
<input type="hidden" name="all_time_common" id="all_time_common" value="<?php echo $all_time_common;?>"/>
<?php
$this->renderPartial('//render_partial/common/create_international',array('type'=>Branch::SUPPLIER));
$this->renderPartial('//render_partial/common/edit_international',array('type'=>Branch::SUPPLIER));
//$this->renderPartial('//international/index/sort');
$this->renderPartial('//render_partial/common/sort',array('session_key'=>'international_list_sort','field_array'=>array(
                                                                            'short_hand_name__full_name',
                                                                            'bill_count',
                                                                            'quantity',
                                                                            'tong_tien',                                                                            
                                                                            )
    ));
$this->renderPartial('//render_partial/common/delete_branch',array('from_page'=>'international'));
$this->renderPartial('//render_partial/common/load_more_data',array('page_count'=>  ceil($count/Yii::app()->params['number_of_items_per_page']))); 
 
?>
<script type="text/javascript">
    jQuery(function ($){
        $("body").delegate("td.cursor", "click", function() {
            if($(this).find("a").length==1){//link số hóa đơn/ số tờ khai
                if ($(this).find("a").eq(0).hasClass("internationalinput")) {//link đến page hóa đơn
                    id_str = $(this).prev().attr("id");
                    action_for_form_common="<?php echo $this->createUrl('/internationalinput/index'); ?>";
                }
                else if ($(this).find("a").eq(0).hasClass("goodsinput")) {//link đến page hàng hóa đã bán
                    id_str = $(this).prev().prev().attr("id");
                    action_for_form_common="<?php echo $this->createUrl('/goodsinputfull/index'); ?>";
                }
                id_str = $(this).parent().find("td").eq(0).attr("id");
                $("#customer_id_common").val(id_str);
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