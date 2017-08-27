<!--<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datedropper-master/datedropper.js"></script>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/datedropper-master/datedropper.css" rel="stylesheet"  media="screen" />-->
<?php 
//$DATE_FORMAT = Yii::app()->session['date_format'];
?>
<form id="form_user" enctype="multipart/form-data" action="<?php echo $this->createUrl('/user/create/'); ?>" method="POST">
<div class="back_button" title="Quay lại">
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon/back.png" alt=""/>
</div>
<h1>Tạo tài khoản user</h1>
<!-- Tạo tài khoản user -->
<div id="w-admin-adduser">
    <li class="add0"><b>Thông tin đăng nhập.</b></li>
    <li class="clearfix"></li>

    <li class="add1">Email đăng nhập:</li>
    <li class="add2">
        <input name="email" class="input-adduser1" type="text" value="<?php echo $email;?>">
        <div class="error right email"></div>
    </li>
    <li class="clearfix"></li>

    <li class="add1">Mật khẩu:</li>
    <li class="add2">
        <input name="password_for_show" class="input-adduser1" type="password" value="<?php echo $password_for_show;?>">
        <div class="error right password_for_show"></div>
    </li>
    <li class="clearfix"></li>

    <li class="add1">Nhập lại mật khẩu:</li>
    <li class="add2">
        <input name="re_password_for_show" class="input-adduser1" type="password" value="<?php echo $re_password_for_show;?>">
        <div class="error right re_password_for_show"></div>
    </li>
    <li class="clearfix"></li>

    <li class="add0"><b>Thông tin user chi tiết.</b></li>
    <li class="clearfix"></li>
    
    <li class="add1">Danh xưng:</li>
    <li class="add2">        
        <select class="input-adduser1" name="danh_xung">
            <option value="">---Chọn danh xưng---</option>
            <option value="anh">anh</option>
            <option value="chị">chị</option>
        </select>
        <div class="error right danh_xung"></div>
    </li>
    <li class="clearfix"></li>
    
    <li class="add1">Tên đầy đủ:</li>
    <li class="add2">
        <input name="full_name" class="input-adduser1" type="text" value="<?php echo $full_name;?>">
        <div class="error right full_name"></div>
    </li>
    <li class="clearfix"></li>
    
    <li class="add1">Quyền:</li>
    <li class="add2">
        <div style="float: left;width:250px; padding:5px;">
            <table style="width: 100%;">
                <tbody>
                    <?php   
        
                    foreach ($roles as $role) {
                        $check='';
                        if($role['id']== $user_role){
                            $check=' checked="checked"';
                        }
                    ?>
                    <tr>
                        <td style="width: 100%;">
                            <label class="label_for">
                                <input type="radio" name="role" value="<?php echo $role['id'];?>"<?php echo $check;?>> <?php echo $role['role'];?>
                            </label>    
                        </td>
                    </tr>
                            
                        
                    <?php    
                    }
                    ?>
                </tbody>
            </table>
        
        </div>
        <div class="error role right"></div>
    </li>
    <li class="clearfix"></li>

<!--    <li class="add1">Ngày sinh:</li>
    <li class="add2">
        <input id="birthday" name="birthday" class="input-adduser1 text_input" type="text" value="<?php //echo $birthday;?>">
        <div class="error right birthday"></div>
    </li>
    <li class="clearfix"></li>-->

    <li class="add1">Địa chỉ:</li>
    <li class="add2">
        <input name="address" class="input-adduser1" type="text" value="<?php echo $address;?>">
        <div class="error right address"></div>
    </li>
    <li class="clearfix"></li>

    <li class="add1">Số điện thoại:</li>
    <li class="add2">
        <input name="phone" class="input-adduser1" type="text" value="<?php echo $phone;?>">
        <div class="error right phone"></div>
    </li>
    <li class="clearfix"></li>

    <li class="add1">Ảnh đại diện:</li>
    <li class="add2">
        <input name="photo" class="input-adduser1" type="file" value="">
        <div class="error right photo"></div>
    </li>
    <li class="clearfix"></li>

    <li class="add1">&nbsp;</li>
    <li class="add2-button" id="save_user" style="cursor: pointer;"><a>Lưu</a></li>
    <li class="clearfix"></li>

    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<!-- Tạo tài khoản user -->

<div class="clearfix"></div>
</form>
<script type="text/javascript">  
    
    jQuery(function($) {
        
        $('select[name="danh_xung"]').val('<?php echo $danh_xung;?>');
        function show_error(errors){
            data=$.parseJSON(errors);
            for (key in data) {                
                temp=key.replace("User_","");
                selector="div.error."+temp; 
                if(data[key].indexOf("Vui lòng nhập Nhập lại mật khẩu")!=-1&&data[key].indexOf('Mật khẩu không trùng nhau.')!=-1){                                    
                    $(selector).html('Vui lòng nhập Nhập lại mật khẩu.').show();
                }
                else{
                    $(selector).html(data[key]).show();
                }                    

            }
        }
        <?php
        if(isset($errors)&&trim($errors)!=""&&trim($errors)!="[]"){
            echo "show_error('$errors');";
        }
        ?>
//        $("input[name='birthday']").dateDropper({ format:"<?php //echo $DATE_FORMAT; ?>" ,lang:"vi"});
        $('input[name="role"]').change(function (){            
            $("div.error.role").html('').hide();
        });
        $('input,select').on('input',function(e){
            node=$(this);
            delay(function(){
                name=$(node).attr("name");                  
                $("div.error."+name).html('').hide();
            
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/user/saveuser/'); ?>',
                data: $("#form_user").serialize(), 
                success: function(data, textStatus, jqXHR) {
               
                    if($.trim(data)!=""&&data.indexOf("User")!=-1){                    
                        data=$.parseJSON(data);
                        for (key in data) {                                 
                            temp=key.replace("User_","");
                            selector="div.error."+temp; 
                            if(selector=="div.error."+name){
                                if(data[key].indexOf("Vui lòng nhập Nhập lại mật khẩu")!=-1&&data[key].indexOf('Mật khẩu không trùng nhau.')!=-1){                                    
                                    $(selector).html('Vui lòng nhập Nhập lại mật khẩu.').show();
                                }
                                else{
                                    $(selector).html(data[key]).show();
                                }
                                
                            }                       
                            
                        }
                    }
                    
                }
            });
            }, 2000 );

        
        });       
        
        
        $("#save_user").click(function() {
            $("#form_user").submit();
        });       
    });
</script>