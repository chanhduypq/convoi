<html class="global desktop">
    <head>
        <?php 
        $this->renderPartial('//layouts/static_head');
        $this->renderPartial('//render_partial/common/common_js_function');
        ?>
    </head>
    <body class=""><div id="skel-layers-wrapper1" style="position: relative; left: 0px; right: 0px; top: 0px; backface-visibility: hidden; transition: -webkit-transform 0.25s ease-in-out;">

            <center>
                <!-- Header -->
                <?php $this->renderPartial('//layouts/top');?>
                <!-- Banner -->
                <div id="nav-wrapper">
                    <div id="banner" style="overflow:hidden;">
                        <!-- nav left -->
                        <div id="nav-left-parent">
                        <?php $this->renderPartial('//layouts/menu');?>
                            <div id="footer">
                                <?php echo Yii::app()->params['copyrightInfo'];?>       
                            </div>    
                        </div>
                        
                        <div id="admin-setting">
                            <li class="a_li" style="cursor: auto;text-align: center;" id="danh_xung_full_name"><b><?php echo Yii::app()->session['danh_xung_full_name'];?></b></li>
                            <li class="a_li" id="edit_profile"><p class="a_img"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-admin.png" width="14" height="14"></p> <p class="a_text"><?php echo Yii::app()->session['username'];?></p></li>
                            <?php if(FunctionCommon::get_role()==Role::ADMIN){?>
                            <li class="a_li" id="setting"><p class="a_img"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-setting.png" width="14" height="14"></p> <p class="a_text">Cài đặt</p></li>
                            <div style="display: none;">
                                <a href="<?php echo $this->createUrl('/user/index');?>"><li class="a_chidren"><p class="a_text">Người dùng</p></li></a>
                                <a href="<?php echo $this->createUrl('/system/index');?>"><li class="a_chidren"><p class="a_text">Hệ thống</p></li></a>
                            </div>
                            <?php }?>
                            <li id="logout" class="a_li"><p class="a_img"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-logout.png" width="14" height="14"></p> <p class="a_text">Thoát</p></li>
                        </div>
                        <!-- content right -->
                        <div id="content">    
                            <?php
                            echo_content($content, $this, Yii::app()->controller->id,Yii::app()->controller->action->id,Yii::app()->params['controller_list_for_search']);
                            ?>                             
                        </div>
                        <div class="clearfix"></div>

                    </div>
                </div>
                <div class="clearfix"></div>
            </center>

        </div>
        <div id="skel-layers-inactiveWrapper" style="height: 100%;"></div>
        <div id="skel-layers-activeWrapper" style="position: relative;"></div>
        <?php $this->renderPartial('//layouts/static_footer');?>
    </body></html>
<?php
function echo_content($content,$view,$controller_name,$action_name,$controller_list_for_search){
    if (in_array($controller_name, $controller_list_for_search)&&$action_name == "index") {                             
    ?>
        <form id="form_common" onsubmit="return false;" method="POST" action="<?php echo $view->createUrl('/' . $controller_name . '/index'); ?>">
        <?php                             
        echo $content;                            
        $view->renderPartial('//layouts/search');                                
        ?>  
            <input type="hidden" id="field" value=""/>
            <input type="hidden" id="session_key" value=""/>
        </form>
    <?php 
    }
    else if($action_name == "index"&&$controller_name!='system'&&$controller_name!='thongke'){?>
        <form id="form_common" onsubmit="return false;" method="POST" action="<?php echo $view->createUrl('/' . $controller_name . '/index'); ?>">
        <?php                             
        echo $content;            
        ?>
            <input type="hidden" id="field" value=""/>
            <input type="hidden" id="session_key" value=""/>
        </form>
        
    <?php 
    }
    else{
        echo $content;    
    }
    if($controller_name!="system"){//&&$action_name=='index'){?>
        <div id="div_loading_common" style="position: absolute;z-index: 99999;display: none;">
            <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
        </div>
    <?php
    }
}
?>