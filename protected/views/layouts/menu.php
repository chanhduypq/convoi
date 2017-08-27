<div id="nav-left">
    <li style="padding:10px 0 25px 20px;">MNS INVOICE System</li>
    <?php
    $menus=Yii::app()->db->createCommand()->select()->from("menu")->where("is_parent=1")->order("order")->queryAll();
    foreach ($menus as $menu) {
        if($menu['text']=='Khách hàng'){?>
            <li class="nav-khachhang parent_menu"><a>Khách hàng</a></li>
    <?php
            $menus_temp=Yii::app()->db->createCommand()->select()->from("menu")->where("parent_id=".$menu['id'])->order("order")->queryAll();
            $array=array();
            foreach ($menus_temp as $value) {
                
                $array[]=array('controller_name'=>$value['controller'],'text'=>$value['text']);
            }
            echo_menu_items($this,Yii::app()->controller->id,$array);
        }
        else if($menu['text']=='Thống kê'){?>
            <li class="href nav-thongke"><a href="<?php echo $this->createUrl('/thongke/index');?>">Thống kê</a></li>
    <?php
        }
        else if($menu['text']=='Sổ cái'){?>
            <li class="href nav-socai"><a href="<?php echo $this->createUrl('/socai/index');?>">Sổ cái</a></li>
    <?php
        }
        else if($menu['text']=='Hóa đơn bán hàng'){?>
            <li class="nav-hoadon parent_menu"><a>Hóa đơn bán hàng</a></li>
    <?php        
            $menus_temp=Yii::app()->db->createCommand()->select()->from("menu")->where("parent_id=".$menu['id'])->order("order")->queryAll();
            $array=array();
            foreach ($menus_temp as $value) {
                
                $array[]=array('controller_name'=>$value['controller'],'text'=>$value['text']);
            }
            echo_menu_items($this,Yii::app()->controller->id,$array);
        }
        else if($menu['text']=='Nhập kho & Chi phí'){?>
            <li class="nav-hoadon1 parent_menu"><a>Nhập kho & Chi phí</a></li>
    <?php        
            $menus_temp=Yii::app()->db->createCommand()->select()->from("menu")->where("parent_id=".$menu['id'])->order("order")->queryAll();
            $array=array();
            foreach ($menus_temp as $value) {
                
                $array[]=array('controller_name'=>$value['controller'],'text'=>$value['text']);
            }
            echo_menu_items($this,Yii::app()->controller->id,$array);
        }
        else if($menu['text']=='Tài khoản MNS'){?>
            <li class="nav-thuchi parent_menu"><a>Tài khoản MNS</a></li>
    <?php        
            $menus_temp=Yii::app()->db->createCommand()->select()->from("menu")->where("parent_id=".$menu['id'])->order("order")->queryAll();
            $array=array();
            foreach ($menus_temp as $value) {
                
                $array[]=array('controller_name'=>$value['controller'],'text'=>$value['text']);
            }
            echo_menu_items($this,Yii::app()->controller->id,$array);
        }
        else if($menu['text']=='Nhà cung ứng'){?>
            <li class="nav-ncc parent_menu"><a><?php echo Yii::app()->params['label_for_supplier'];?></a></li>
    <?php        
            $menus_temp=Yii::app()->db->createCommand()->select()->from("menu")->where("parent_id=".$menu['id'])->order("order")->queryAll();
            $array=array();
            foreach ($menus_temp as $value) {
                $array[]=array('controller_name'=>$value['controller'],'text'=>$value['text']);
            }
            echo_menu_items($this,Yii::app()->controller->id,$array);
        }
        else if($menu['text']=='Hàng hóa'){?>
            <li class="nav-hanghoa parent_menu"><a>Hàng hóa</a></li>
    <?php        
            $menus_temp=Yii::app()->db->createCommand()->select()->from("menu")->where("parent_id=".$menu['id'])->order("order")->queryAll();
            $array=array();
            foreach ($menus_temp as $value) {
                $array[]=array('controller_name'=>$value['controller'],'text'=>$value['text']);
            }
            echo_menu_items($this,Yii::app()->controller->id,$array);
        }
    }
    ?>
            <?php
        if(FunctionCommon::get_role()==Role::ADMIN){
        ?>
        <li class="nav-adminsetting parent_menu" id="date_format"><a>Cài đặt</a></li>    
        <?php
             echo_menu_items($this,Yii::app()->controller->id, array(
                                          array('controller_name'=>'user','text'=>'Người dùng'),
                                          array('controller_name'=>'system','text'=>'Hệ thống'),
             ));
        }
        ?>
    <div class="clearfix"></div>
</div>
<script type="text/javascript">
        jQuery(function($) { 
              //toggle the componenet with class msg_body
            jQuery(".parent_menu").click(function(){
                jQuery(this).next(".content_children").slideToggle();
                node=$(this);
                $("#nav-left").animate({
                    scrollTop: $(node).offset().top-100
	        });
            });
            ctrl=false;
            $(document).keydown(function (e){
               if(e.keyCode==17){
                   ctrl=true;
               }
            });
            $(document).keyup(function (e){
               if(e.keyCode==17){
                   ctrl=false;
               }
            });
            $("#nav-left .href").click(function() {
                node=$(this);
                $.ajax({ 
                    async: false,
                    cache: false,
                    url: '<?php echo $this->createUrl("/ajax/resetfilter"); ?>',
                    type:'GET',
                    success: function(data) {      
                        if(ctrl==false){
                            window.location=$(node).find("a").eq(0).attr("href");
                        }
                        
                    }
                });
                
            });
            <?php 
            if((Yii::app()->controller->id=='user'||Yii::app()->controller->id=='system')&&FunctionCommon::get_role()==Role::ADMIN){?>
                $("#nav-left").animate({
                    scrollTop: $(".nav-adminsetting").next().find("li").eq(0).offset().top-100
	        });
            <?php 
            }
            else if(Yii::app()->controller->id=='thuchi'||Yii::app()->controller->id=='taikhoanacb'){?>
                $("#nav-left").animate({
                    scrollTop: $(".nav-thuchi").next().find("li").eq(0).offset().top-100
	        });
            <?php 
            }
            else if(Yii::app()->controller->id=='invoicefull'||Yii::app()->controller->id=='sxdvfull'||Yii::app()->controller->id=='kxhdfull'||Yii::app()->controller->id=='laisuatfull'){?>
                $("#nav-left").animate({
                    scrollTop: $(".nav-hoadon").next().find("li").eq(0).offset().top-100
	        });
            <?php 
            }
            else if(Yii::app()->controller->id=='invoicechiphifull'||Yii::app()->controller->id=='invoiceinputfull'||Yii::app()->controller->id=='internationalinput'||Yii::app()->controller->id=='chiphikhdfull'){?>
                $("#nav-left").animate({
                    scrollTop: $(".nav-hoadon1").next().find("li").eq(0).offset().top-100
	        });
            <?php 
            }
            else if(Yii::app()->controller->id=='supplierfull'||Yii::app()->controller->id=='international'){?>
                $("#nav-left").animate({
                    scrollTop: $(".nav-ncc").next().find("li").eq(0).offset().top-100
	        });
            <?php 
            }
            else if(Yii::app()->controller->id=='goodsfull'||Yii::app()->controller->id=='goodsleftfull'||Yii::app()->controller->id=='goodsinputfull'){?>
                $("#nav-left").animate({
                    scrollTop: $(".nav-hanghoa").next().find("li").eq(0).offset().top-100
	        });
            <?php 
            }
            else if(Yii::app()->controller->id=='customerfull'){?>                
                $(".nav-khachhang").addClass("active-khachhang");
                $("#nav-left").animate({
                    scrollTop: $(".nav-khachhang").offset().top-100
	        });
            <?php 
            }
            else if(Yii::app()->controller->id=='thongke'){?>                
                $(".nav-thongke").addClass("active-thongke");
                $("#nav-left").animate({
                    scrollTop: $(".nav-thongke").offset().top-100
	        });
            <?php 
            }
            else if(Yii::app()->controller->id=='socai'){?>                
                $(".nav-socai").addClass("active-socai");
                $("#nav-left").animate({
                    scrollTop: $(".nav-socai").offset().top-100
	        });
            <?php 
            }
            ?>
                    
            
        });
</script>
<?php 
function echo_menu_items($view,$curren_controller_name,$menu_item_array){
    $show= FALSE;
    foreach ($menu_item_array as $menu_item) {
        if($curren_controller_name==$menu_item['controller_name']){
            $show=true;
            break;
        }
    }
?>
    <div class="content_children"<?php if($show) echo ' style="display:block;"';?>>
        <?php
        foreach ($menu_item_array as $menu_item) {
            echo_menu_item($view,$curren_controller_name, $menu_item);
        }
        ?>        
    </div>
<?php
}
function echo_menu_item($view,$curren_controller_name,$menu_item){?>
    <li class="href sub-navegater<?php if($curren_controller_name==$menu_item['controller_name']) echo ' active-sub-navegater';?>"><a href="<?php echo $view->createUrl('/'.$menu_item['controller_name'].'/index');?>"><?php echo $menu_item['text'];?></a></li>
<?php
}
?>
          



  