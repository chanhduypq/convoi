<?php
function createGoodsDropdowlist($goods_array, $selected_value = "") {
    $has=FALSE;    
    $select_html = '<select class="goods cus-auto18-input">';
    for ($i = 0; $i < count($goods_array); $i++) {        
        $group_id = Yii::app()->db->createCommand()
                ->select("group_id")
                ->from("goods")
                ->where("id=$selected_value")
                ->queryScalar();  
        if($group_id ==$goods_array[$i]['group_id']){
            //new
            $goods_full_name=$goods_array[$i]['goods_full_name'];
            $has=true;
        }
        $select_html .= "<option value='" . $goods_array[$i]['group_id'] . "'" . ($group_id ==$goods_array[$i]['group_id'] ? ' selected="selected"' : '') . ">" . $goods_array[$i]['goods_full_name'] . "</option>";
    }
    /**
     * hàng hóa mang id $selected_value đã bị user xóa khỏi page hàng hóa đã bán
     *                                  hoặc số lượng tồn kho hiện tại = 0
     * nên nó không tồn tại trong array $goods_array. 
     * --->combobox hàng hóa này se bị thiếu đi hàng hóa mang id $selected_value.
     * --->chúng ta phai thêm một item vào combobox này
     */
    if($has==FALSE){
        $good = Yii::app()->db->createCommand()
                ->select("group_id,goods_full_name")
                ->from("goods")                
                ->where("id=$selected_value")
                ->group("group_id")
                ->queryRow();
        $select_html .= "<option value='" . $good['group_id'] . "' selected='selected'>" . $good['goods_full_name'] . "</option>";
        //new
        $goods_full_name=$good['goods_full_name'];
    }
    $select_html.='</select>';
    //new
    $select_html.='<input type="hidden" value="'.$goods_full_name.'"/>';
    return $select_html;
}

function createGoodsunitDropdowlist($selected_value = "") {
    $goods_unit_array = Yii::app()->db->createCommand()
            ->select("id,unit_full_name")
            ->from("goods")
            ->where("group_id IN (select group_id from goods where id=$selected_value)")
            ->queryAll();
    $select_html = '<select name="goods_id[]" class="cus-auto18-input unit">';
    for ($i = 0; $i < count($goods_unit_array); $i++) {
        $select_html .= "<option value='" . $goods_unit_array[$i]['id'] . "'" . ($selected_value == $goods_unit_array[$i]['id'] ? ' selected="selected"' : '') . ">" . $goods_unit_array[$i]['unit_full_name'] . "</option>";
    }
    $select_html.='</select>';
    return $select_html;
}
?>