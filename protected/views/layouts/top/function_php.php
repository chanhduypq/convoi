<?php
function echo_search_not_date($goodsleft_equal_0,$controller_name,$label_for_branch_combobox){?>
    <div id="search">
        <li class="text-search date" style="width: 44%;">
            <div id="cancel_filter_date">
            </div>
        </li>  
        <li id="button-customer" class="text-search" title="Nhấn vào đây để chọn <?php echo lcfirst($label_for_branch_combobox);?>">
            <?php echo_customer_combobox($controller_name);?>
        </li>
        <li id="button-goods" style="border-right: 1px solid #ccc;" class="text-search" title="Nhấn vào đây để chọn hàng hóa">
            <?php echo_goods_combobox($controller_name);?>
        </li>
    </div>
<?php
}
function echo_search($start_date,$end_date,$all_time_common,$controller_name,$label_for_branch_combobox){?>
    <div id="search">
        <li class="text-search date" style="width: 44%;">
            
            <?php
            if($all_time_common=='0'){?>
            <label id="pre_month" style="float: left;margin-top: 10px;margin-right: 10px;" class="cursor" title="Tháng trước">&lt;&lt;</label>
            <input readonly="readonly" class="new hover" id="start_date" type="text" placeholder="Từ ngày" value="<?php echo $start_date; ?>">
            <input readonly="readonly" class="new hover" id="end_date" type="text" placeholder="Đến ngày" value="<?php echo $end_date; ?>">                
            <label id="next_month" style="float: left;margin-top: 10px;" class="cursor" title="Tháng sau">&gt;&gt;</label>
            <?php 
            }
            else{?>
            <input disabled='disabled' readonly='readonly' class='new' type='text' id='start_date1' value='Ngày'/>
            <input disabled='disabled' readonly='readonly' class='new' type='text' id='end_date1' value='Ngày'/>
            <?php
            }
            ?>
            <div style="clear: both;"></div>
            <div id="cancel_filter_date">
                <label>
                    <input type="checkbox" value="1" name="all_time" id="all_time"<?php if($all_time_common=='1'){?> checked="checked"<?php }?>/>
                    Từ ngày bắt đầu đến nay
                </label>
            </div>
        </li> 
        <?php 
        if($controller_name!='taikhoanacb'&&$controller_name!='thuchi'&&$controller_name!='socai'&&$controller_name!='laisuatfull'&&$controller_name!='chiphikhdfull'){?>
        <li id="button-customer" class="text-search" title="Nhấn vào đây để chọn <?php echo lcfirst($label_for_branch_combobox);?>">
            <?php echo_customer_combobox($controller_name);?>
        </li>
        <?php
        }
        if($controller_name!='chiphikhdfull'&&$controller_name!='laisuatfull'&&$controller_name!='sxdvfull'&&$controller_name!='kxhdfull'&&$controller_name!='invoicechiphifull'&&$controller_name!='thuchi'&&$controller_name!='taikhoanacb'&&$controller_name!='socai'&&$controller_name!='customerkxhdfull'&&$controller_name!='customersxdvfull'){?>
        <li id="button-goods" style="border-right: 1px solid #ccc;" class="text-search" title="Nhấn vào đây để chọn hàng hóa">
            <?php echo_goods_combobox($controller_name);?>
        </li>
        <?php
        }
        ?>
    </div>
<?php
}
function get_label_for_branch_combobox($controller_name){
    if($controller_name=='customerfull'||$controller_name=='invoicefull'||$controller_name=='sxdvfull'||$controller_name=='goodsfull'||$controller_name=='kxhdfull'){
        return 'Khách hàng';
    }
    else if($controller_name=='invoicechiphifull'||$controller_name=='supplierfull'||$controller_name=='invoiceinputfull'||$controller_name=='internationalinput'||$controller_name=='goodsleftfull'||$controller_name=='goodsinputfull'||$controller_name=='international'){
        return Yii::app()->params['label_for_supplier'];
    }
    else{
        return 'Khách hàng';
    }
}
function echo_customer_combobox($controller_name) { ?>
    <select id="customer" multiple="multiple" style="display: none;">
        <option title="Tất cả" value="">Tất cả</option>                    
        <?php
        if($controller_name=='customerfull'||$controller_name=='customersxdvfull'||$controller_name=='customerkxhdfull'||$controller_name=='invoicefull'||$controller_name=='goodsfull'){
            $condition='type_init='.Branch::CUSTOMER.' or type='.Branch::CUSTOMER.' or type='.Branch::BOTH_CUSTOMER_SUPPLIER;
        }
        else if($controller_name=='invoiceinputfull'||$controller_name=='goodsleftfull'||$controller_name=='goodsinputfull'){
            $condition='type_init='.Branch::SUPPLIER.' or type='.Branch::SUPPLIER.' or type='.Branch::BOTH_CUSTOMER_SUPPLIER;
        }
        else if($controller_name=='supplierfull'){
            $condition='(type_init='.Branch::SUPPLIER.' or type='.Branch::SUPPLIER.' or type='.Branch::BOTH_CUSTOMER_SUPPLIER.') and is_international='.Branch::NOI_DIA;
        }
        else if($controller_name=='international'||$controller_name=='internationalinput'){
            $condition='(type_init='.Branch::SUPPLIER.' or type='.Branch::SUPPLIER.' or type='.Branch::BOTH_CUSTOMER_SUPPLIER.') and is_international='.Branch::QUOC_TE;
        }
        else{
            $condition='';
        }
        
        $items = Branch::model()->findAll($condition);
        $array_length = array();
        foreach ($items as $value) {
            $array_length[] = mb_strlen($value->short_hand_name);
        }
        if (count($array_length) > 0) {
            $length = max($array_length);
        } else {
            $length = 0;
        }

        foreach ($items as $value) {
            $length_space = $length - mb_strlen($value->short_hand_name);
            ?>
            <option title="<?php echo $value->full_name; ?>" value="<?php echo $value->id; ?>"><?php echo $value->short_hand_name; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php
                for ($i = 0; $i < $length_space; $i++)
                    echo "&nbsp;";
                echo FunctionCommon::crop($value->full_name, 25, FALSE); 
                ?>
            </option>
            <?php
        }
        ?>
    </select>
    <?php
}

function echo_goods_combobox($controller_name) {
    ?>
    <select id="goods" multiple="multiple" style="display: none;">
        <option title="Tất cả" value="">Tất cả</option>
        <?php
        if($controller_name=='customerfull'||$controller_name=='customersxdvfull'||$controller_name=='customerkxhdfull'||$controller_name=='invoicefull'||$controller_name=='goodsfull'){
            $condition='show_goodsfull=1';
        }
        else if($controller_name=='goodsleftfull'){
            $condition='show_goodsleftfull=1';
        }
        else{
            $condition='true';
        }
        $items = Yii::app()->db->createCommand()
                ->select("group_id,goods_full_name,goods_short_hand_name")
                ->from("goods")
                ->where($condition)
                ->group("group_id")
                ->queryAll();
        $array_length = array();
        foreach ($items as $value) {
            $array_length[] = mb_strlen(FunctionCommon::crop($value['goods_full_name'], 25, true));
        }
        if (count($array_length) > 0) {
            $length = max($array_length);
        } else {
            $length = 0;
        }

        foreach ($items as $value) {
            $length_space = $length - mb_strlen(FunctionCommon::crop($value['goods_full_name'], 25, true));
            ?>
            <option title="<?php echo $value['goods_full_name']; ?>" value="<?php echo $value['group_id']; ?>"><?php echo FunctionCommon::crop($value['goods_full_name'], 25, true); ?>&nbsp;&nbsp;&nbsp;<?php
            for ($i = 0; $i < $length_space; $i++)
                echo "&nbsp;";
            ?><?php echo $value['goods_short_hand_name']; ?></option>
        <?php
    }
    ?>
    </select>
    <?php
}

function echo_icon_user($baseUrl) {
    ?>
    <li class="but-add" id="expand_for_user">
        <a>
            <?php
            $photo=Yii::app()->session['photo'];            
            if($photo==""){
                $photo=$baseUrl.'/images/icon/icon-user.png';
                $style='';
            }
            else{
                $photo=Yii::app()->baseUrl.'/upload/'.$photo;
                $style=' style="border-radius: 50%;"';
            }
            ?>
            <img src="<?php echo $photo; ?>" width="70" height="70"<?php echo $style;?>>
        </a>
    </li>
    <?php
}

function echo_icon_create($baseUrlTheme, $controller_name, $view, $title_attr_for_create_icon) {
    if (!in_array($controller_name, Yii::app()->params['controller_list_has_create_button'])){
        echo '&nbsp;';
        return;
    }    
    if(
        ($controller_name=='invoiceinputfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN)
        ||($controller_name=='sxdvfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_BAN_HANG&&FunctionCommon::get_role()!=Role::ADMIN)    
        ||($controller_name=='invoicechiphifull'&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN)    
        ||($controller_name=='chiphikhdfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN)            
        ||($controller_name=='internationalinput'&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN)
        ||($controller_name=='invoicefull'&&FunctionCommon::get_role()!=Role::QUAN_LY_BAN_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
        ||($controller_name=='kxhdfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_BAN_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
        ||($controller_name=='supplierfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
        ||($controller_name=='international'&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
        ||($controller_name=='customerfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_BAN_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
        ||($controller_name=='customersxdvfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_BAN_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
        ||($controller_name=='customerkxhdfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_BAN_HANG&&FunctionCommon::get_role()!=Role::ADMIN)             
        ||($controller_name=='goodsinputfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
        ||($controller_name=='goodsfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
        ||($controller_name=='goodsleftfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_KHO_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
        ||($controller_name=='laisuatfull'&&FunctionCommon::get_role()!=Role::QUAN_LY_BAN_HANG&&FunctionCommon::get_role()!=Role::ADMIN)     
    ){
        echo '&nbsp;';
        return;
    }
    if ($controller_name == "invoicefull") {
        ?>                
<!--        <a href="<?php echo $view->createUrl('/invoicefull/create');?>"><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>                -->
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>                

        <?php
    } else if ($controller_name == "invoiceinputfull" ) {
        ?>
<!--        <a href="<?php echo $view->createUrl('/invoiceinputfull/create');?>"><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>-->
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>

        <?php    
    }
    else if ($controller_name == "invoicechiphifull" ) {
        ?>
<!--        <a href="<?php echo $view->createUrl('/invoicechiphifull/create');?>"><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>-->
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>
        <?php    
    }
    else if ($controller_name == "chiphikhdfull" ) {
        ?>
<!--        <a href="<?php echo $view->createUrl('/chiphikhdfull/create');?>"><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>-->
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>

        <?php    
    }
    else if ($controller_name == "internationalinput" ) {
        ?>
<!--        <a href="<?php echo $view->createUrl('/internationalinput/create');?>"><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>-->
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>

        <?php
    
    } else if ($controller_name == "goodsinputfull" || $controller_name == "goodsfull" || $controller_name == "goodsleftfull") {
        ?>
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>

        <?php
        
    } else if ($controller_name == "customerkxhdfull" ||$controller_name == "customersxdvfull" ||$controller_name == "customerfull" || $controller_name == 'supplierfull'||$controller_name=='international') {//thêm khách hàng (popup)            
        ?>
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>
        <?php
    }
    else if ($controller_name == "user") {
        ?> 
        <a href="<?php echo $view->createUrl('/user/create');?>"><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>
        <?php
    }
    else if ($controller_name == "kxhdfull") {
        ?> 
<!--        <a href="<?php echo $view->createUrl('/kxhdfull/create');?>"><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>-->
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>
        <?php
    }
    else if ($controller_name == "laisuatfull") {
        ?> 
<!--        <a href="<?php echo $view->createUrl('/laisuatfull/create');?>"><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>-->
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>
        <?php
    }
    else if ($controller_name == "sxdvfull") {
        ?> 
        <a><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>
<!--        <a href="<?php echo $view->createUrl('/sxdvfull/create');?>"><img title="<?php echo $title_attr_for_create_icon; ?>" src="<?php echo $baseUrlTheme; ?>/images/icon-add-new.png" width="70" height="70"></a>-->
        <?php
    }
}


function get_class_attr_for_addnew_div_element($view, $controller_name) {
    if (
            $controller_name != "invoicefull" && $controller_name != "invoiceinputfull" && $controller_name != "goodsfull" && $controller_name != "goodsinputfull"
    ) {
        return $view->createUrl('/' . $controller_name . '/create');
    }
    return '';
}

function get_title_attr_for_create_icon($controller_name) {
    if ($controller_name == "invoicefull") {
        return 'Thêm mới hóa đơn bán hàng';
    }
    else if ($controller_name == "sxdvfull") {
        return 'Thêm mới sản xuất & dịch vụ';
    }
    else if ($controller_name == 'invoiceinputfull') {
        return 'Thêm mới hóa đơn nhập kho';
    } else if ($controller_name == 'invoicechiphifull') {
        return 'Thêm mới hóa đơn chi phí';    
    } else if ($controller_name == 'goodsfull' || $controller_name == 'goodsinputfull' || $controller_name == 'goodsleftfull') {
        return 'Thêm mới hàng hóa';
    } else if ($controller_name == 'customerfull'||$controller_name == 'customersxdvfull'||$controller_name == 'customerkxhdfull') {
        return 'Thêm mới khách hàng';
    } else if ($controller_name == 'supplierfull'||$controller_name=='international') {
        return 'Thêm mới nhà cung ứng';
    } else if ($controller_name == 'user') {
        return 'Thêm mới user';
    } 
    else if ($controller_name == 'internationalinput') {
        return 'Thêm mới tờ khai';
    } 
    else if ($controller_name == 'kxhdfull') {
        return 'Thêm mới bán hàng không xuất HĐ';
    } 
    else if ($controller_name == 'laisuatfull') {
        return 'Thêm mới lãi suất';
    } 
    else if ($controller_name == 'chiphikhdfull') {
        return 'Thêm mới chi phí dịch vụ không HĐ';
    } 
    return '';
}
function echo_js_css_for_datetimepicker_multiselect($baseUrl){?>
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/css/multiselect/jquery.multiselect.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/css/multiselect/jquery.multiselect.filter.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/css/multiselect/jquery-ui.css" />    
    <script type="text/javascript" src="<?php echo $baseUrl; ?>/js/multiselect/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseUrl; ?>/js/multiselect/jquery.multiselect.js"></script>
    <script type="text/javascript" src="<?php echo $baseUrl; ?>/js/multiselect/jquery.multiselect.filter.js"></script>
    <script type="text/javascript" src="<?php echo $baseUrl; ?>/js/jquery.ui.datepicker-vi.min.js"></script>
<?php    
}