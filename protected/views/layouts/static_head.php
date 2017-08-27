<meta charset="utf-8" />
<title><?php echo Yii::app()->params['title'];?></title>

<style type="text/css">@-ms-viewport{width: device-width}</style>
<style type="text/css">html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,form,label,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:'';content:none}table{border-collapse:collapse;border-spacing:0}body{-webkit-text-size-adjust:none}</style><style type="text/css">*,*:before,*:after{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box}</style>
<style type="text/css">body{min-width:1200px}.container{margin-left:auto;margin-right:auto;width:1200px}.container.small{width:900px}.container.big{width:100%;max-width:1500px;min-width:1200px}</style><style type="text/css">.\31 2u{width:100%}.\31 1u{width:91.6666666667%}.\31 0u{width:83.3333333333%}.\39 u{width:75%}.\38 u{width:66.6666666667%}.\37 u{width:58.3333333333%}.\36 u{width:50%}.\35 u{width:41.6666666667%}.\34 u{width:33.3333333333%}.\33 u{width:25%}.\32 u{width:16.6666666667%}.\31 u{width:8.3333333333%}.\-11u{margin-left:91.6666666667%}.\-10u{margin-left:83.3333333333%}.\-9u{margin-left:75%}.\-8u{margin-left:66.6666666667%}.\-7u{margin-left:58.3333333333%}.\-6u{margin-left:50%}.\-5u{margin-left:41.6666666667%}.\-4u{margin-left:33.3333333333%}.\-3u{margin-left:25%}.\-2u{margin-left:16.6666666667%}.\-1u{margin-left:8.3333333333%}</style><style type="text/css">.row>*{float:left}.row:after{content:'';display:block;clear:both;height:0}.row:first-child>*{padding-top:0!important}</style><style type="text/css">.row>*{padding-left:50px}.row+.row>*{padding:50px 0 0 50px}.row{margin-left:-50px}.row.flush>*{padding-left:0}.row+.row.flush>*{padding:0}.row.flush{margin-left:0}.row.half>*{padding-left:25px}.row+.row.half>*{padding:25px 0 0 25px}.row.half{margin-left:-25px}.row.quarter>*{padding-left:12.5px}.row+.row.quarter>*{padding:12.5px 0 0 12.5px}.row.quarter{margin-left:-12.5px}.row.oneandhalf>*{padding-left:75px}.row+.row.oneandhalf>*{padding:75px 0 0 75px}.row.oneandhalf{margin-left:-75px}.row.double>*{padding-left:100px}.row+.row.double>*{padding:100px 0 0 100px}.row.double{margin-left:-100px}</style><style type="text/css">.not-global,.not-desktop{display:none!important}.only-1000px,.only-mobile{display:none!important}</style>

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style-desktop.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/search.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/list.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/menu.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/top.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/footer.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ui-multiselect-customize.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ui-dialog-customize.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/fonts.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/responsive.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/more.css" />

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-2.0.3.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.dropotron.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/skel.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/skel-layers.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/init.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/html2canvas.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/numeric/jquery.number.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.textarea-expander.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/chart.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.filtertable.min.js"></script>

<style>
.filter-table .quick { margin-left: 0.5em; font-size: 0.8em; text-decoration: none; }
td.alt { background-color: #ffc; background-color: rgba(255, 255, 0, 0.2); }
.filter-table{
    margin-bottom: 20px;
}
</style>