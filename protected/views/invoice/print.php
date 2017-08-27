<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, minimum-scale=0.1"/>
         <meta charset="UTF-8"/>
        <title></title>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-2.0.3.js"></script>
        <script type="text/javascript">
            (function() {

                var beforePrint = function() {
//                    $("body").html('');
                };

                var afterPrint = function() {                    
                    window.location='<?php echo Yii::app()->session['url']; ?>';
                };

                if (window.matchMedia) {
                    var mediaQueryList = window.matchMedia('print');
                    mediaQueryList.addListener(function(mql) {
                        if (mql.matches) {
                            beforePrint();
                        } else {
                            afterPrint();
                        }
                    });
                }

                window.onbeforeprint = beforePrint;
                window.onafterprint = afterPrint;

            }());
        </script>
        <script type="text/javascript">
           
            jQuery(function ($){                
               $('img').bind('contextmenu', function(e) {
                    return false;
                }); 
                window.print();
                
            });
        </script>
        <style type="text/css" media="print">
/*            @page 
            {
                size: auto;    auto is the initial value 
                margin: 0mm;   this affects the margin in the printer settings 
            }*/
            body{
               
				padding: 11px 6px 3px 4px !important;


    margin: 0 auto !important;
    text-align: center !important;
/*                    display: table-cell !important;
                    vertical-align: middle !important;*/
    width: 1112.52px !important;
    height: 773.7px !important;
            }

            img 
            {                
                margin: 0 !important;   
    padding: 0 !important;
    width: 1102.52px !important;
    height: 773.7px !important;
    -webkit-touch-callout:none;
    -webkit-user-select:none;
            }
            @media print {
                #Header, #Footer { display: none !important; }
            }
            @media print {
              @page { margin: 0;padding: 0; }
              
            }
            #Header, #Footer { display: none !important; }            
        </style>
        
    </head>
    <body>
        <img src="<?php echo Yii::app()->params['DOMAIN_NAME']."/".$file_name;?>"/>
    </body>
</html>