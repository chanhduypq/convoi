<html>
    <head>
        <meta name="viewport" content="width=device-width, minimum-scale=0.1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>
        <title>MNS VAT</title>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-2.0.3.js"></script>
        <script type="text/javascript">
            jQuery(function ($){
               $('img').bind('contextmenu', function(e) {
                    return false;
                });  
                
            });
        </script>
        <style> 
            body{
                margin: 0 auto;
                text-align: center;
                padding: 20px;
            }
            img{
                -webkit-touch-callout:none;
                -webkit-user-select:none;
            }
            div.parent{
                width: 100%;
                margin: 0 auto;
                text-align: center;
            }
            body,div,a{
                    font-family:'Roboto', Arial, Helvetica, sans-serif;  
            }
            a{
                color: black;
            }
            
        </style>
    </head>
    <body>
        <div class="parent">
            <a href="<?php echo Yii::app()->session['url']; ?>">Quay lại</a>
        </div>
        <div class="parent">&nbsp;</div>
        <div class="parent">
            <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->params['DOMAIN_NAME']."/".$file_name;?>"/>
        </div>
        <div class="parent">&nbsp;</div>
        <div class="parent">
            <a href="<?php echo Yii::app()->session['url']; ?>">Quay lại</a>
        </div>
        
    </body>
</html>