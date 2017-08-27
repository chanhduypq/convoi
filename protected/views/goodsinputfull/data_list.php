<?php
if(FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN){
    $class_for_click_for_popup_edit='';
}        
else{
    $class_for_click_for_popup_edit=' edit_goods';
}         
if(count($items)>0){    
    foreach ($items as $item) {      
        $class_append='';
        $title_for_delete='';    
        if(FunctionCommon::get_role()==Role::QUAN_LY_KHO_HANG||FunctionCommon::get_role()==Role::ADMIN){
                if($item->tong_tien=='0'){
                    $class_append=' delete cancel_goods';
                    $title_for_delete=' title="Nhấn vào đây để xóa hàng hóa này"';
                }
        }
        ?>
        <tr class="row-HD heightauto">
            <td class="row0-HDli row0-HDliw40 row0-HDli0<?php echo $class_for_click_for_popup_edit;?>" id="<?php echo $item->goodsunit_id;?>" title="<?php echo $item->goods_full_name;?>">
                <p class="pcty">
                    <?php if(FunctionCommon::get_role()!=Role::NHAN_VIEN){?>
                    <a><?php echo FunctionCommon::crop($item->goods_full_name,55,true);?></a>
                    <?php }else { echo FunctionCommon::crop($item->goods_full_name,55,true);}?>
                </p>
                <p class="pname">
                    <?php if(FunctionCommon::get_role()!=Role::NHAN_VIEN){?>
                    <a><?php echo $item->goods_short_hand_name;?></a>
                    <?php }else { echo $item->goods_short_hand_name;}?>
                </p>
            </td>            
            <td class="row0-HDli row0-HDliw10 row0-HDli0-r" style="padding-left: 0px;"><?php echo $item->price;?></td>
            <td class="row0-HDli row0-HDliw10" style="padding-left: 0;"><p class="p_dvt"><?php echo $item->unit_full_name;?></p><p class="p_dvtnumber"><?php echo $item->so_luong_da_ban;?></p></td>
            <td class="row0-HDli row0-HDliw5 row0-HDli0-r<?php if($item->so_hoa_don!='0'){?> cursor<?php }?>">
                <?php 
                if($item->so_hoa_don!='0'){
                ?>
                <a class="submit so_hoa_don"><?php echo $item->so_hoa_don;?></a>
                <?php
                }
                else{
                    echo $item->so_hoa_don;
                }
                ?>
            </td>
            <td class="row0-HDli row0-HDliw5 row0-HDli0-r<?php if($item->so_to_khai!='0'){?> cursor<?php }?>">
                <?php 
                if($item->so_to_khai!='0'){
                ?>
                <a class="submit so_to_khai"><?php echo $item->so_to_khai;?></a>
                <?php
                }
                else{
                    echo $item->so_to_khai;
                }
                ?>
            </td>
            <td class="row0-HDli row0-HDliw5 row0-HDli0-r<?php if($item->so_khach_hang!='0'){?> cursor<?php }?>">
                <?php 
                if($item->so_khach_hang!='0'){
                ?>
                <a class="submit supplier"><?php echo number_format($item->so_khach_hang, 0, ",", ".");?></a>
                <?php
                }
                else{
                    echo $item->so_khach_hang;
                }
                ?>
                
            </td>
            <td class="row0-HDli row0-HDliw5 row0-HDli0-r<?php if($item->so_nguoi_nuoc_ngoai!='0'){?> cursor<?php }?>">
                <?php 
                if($item->so_nguoi_nuoc_ngoai!='0'){
                ?>
                <a class="submit so_nguoi_nuoc_ngoai"><?php echo number_format($item->so_nguoi_nuoc_ngoai, 0, ",", ".");?></a>
                <?php
                }
                else{
                    echo $item->so_nguoi_nuoc_ngoai;
                }
                ?>
                
            </td>
            <td class="row0-HDli row0-HDliw20 row0-HDli0-rb<?php echo $class_append;?>"<?php echo $title_for_delete;?>>
                <?php 
                echo $item->tong_tien;                
                ?>
            </td>
            
        </tr>
        
<?php
    }
}
?>