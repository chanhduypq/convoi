<!DOCTYPE HTML>

<html>
    <head>
        <title>MNS Navigation</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--[if lte IE 8]><script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/ie/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/main.css" />
        <!--[if lte IE 8]><link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/ie8.css" /><![endif]-->
        <!--[if lte IE 9]><link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/ie9.css" /><![endif]-->
        <style>
            .error{
                border: red solid 1px;
            }
            .bnt_submit:hover{
                background-color: #666600;
                color: white;
                transition: background 1s;
                cursor: pointer;
            }
            .bnt_submit a{
                color: white;
            }
            .bnt_submit:hover a:not(:hover){
                color: white
            }
        </style>
    </head>
    <body id="top">

        <!-- Header -->
        <form id="login_form" style="margin: 0px" method="POST" action="<?php echo $this->createUrl("/index/login"); ?>">
            <header id="header">
                <div class="content content_login">
                    <li class="logo"><a href="http://mns.vn"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.png" width="110" height="35" /></a></li>

                    <div class="li-row" style="margin: 0 auto;text-align: center;">
                        <li class="li_title">Đăng nhập</li>
                    </div>
                    <li style="clear:both"></li>

                    <div class="li-row">
                        <li class="li_user">Username:</li>
                        <li class="li_fom"><input id="username" name="username" type="text" style="width:100%; height:auto; font-size:15px; border:none; background:none;"></li>
                    </div>
                    <li style="clear:both"></li>
                    <div class="li-row">
                        <li class="li_user">Password:</li>
                        <li class="li_fom"><input id="password" name="password" type="password" style="width:100%; height:auto; font-size:15px; border:none; background:none;"></li>
                    </div>
                    <li style="clear:both"></li>

                    <div class="li-row">
                        <li class="li_user">&nbsp;</li>
                        <li class="bnt_submit"><a style="cursor: pointer;">Login</a></li>
                    </div>
                    <li style="clear:both"></li>

<!--                    <li class="share">
                        <p><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/facebook.png" width="28" height="28" /></a></p>
                        <p><a href=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/youtube.png" width="28" height="28" /></a></p>
                        <span>Forgot your pasword?</span></li>-->
                </div>                
            </header>
        </form>

        <!-- Scripts -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-2.0.3.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.scrolly.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/skel.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/util.js"></script>
        <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/main.js"></script>
        <script type="text/javascript">
            jQuery(function (){
               $("#username").focus();
               $(".bnt_submit").click(function (){
                   $("form#login_form").submit();
               });
               $("#username").keyup(function (e){
                   
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if (code==13) {
                        $("#username").css('border','none');
                        $("#password").css('border','none');
                        if($.trim($(this).val())==""){    
                            $(this).css('border','red solid 1px');            
                        }
                        else{
                            if($.trim($("#password").val())==""){                                                               
                                $("#password").css('border','red solid 1px');
                                $("#password").focus();
                            }
                            else{
                                $("form#login_form").submit();
                            }
                        }
                    }
               });
               $("#password").keyup(function (e){
                   
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if (code==13) {
                        $("#username").css('border','none');
                        $("#password").css('border','none');                        
                        if($.trim($(this).val())==""){
                            $(this).css('border','red solid 1px');
                        }
                        else{
                            if($.trim($("#username").val())==""){                               
                                $("#username").css('border','red solid 1px');
                                $("#username").focus();
                            }
                            else{
                                $("form#login_form").submit();
                            }
                        }
                    }
               });
            });
        </script>

    </body>
</html>