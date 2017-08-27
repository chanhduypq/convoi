<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MNS VAT</title>
<meta http-equiv="X-UA-Compatible" content="IE = edge, chrome = 1">
<meta name="description" content="MNS VAT">
<meta name="viewport" content="width = device-width, initial-scale = 1">

<link href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>
    <style>

/* Reset CSS */
body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td, span {
    font-family:'Roboto', Arial, Helvetica, sans-serif; 
	padding: 0; 
	margin: 0; 
}
body{
	font-family:'Roboto', Arial, Helvetica, sans-serif; 
	text-align:center; 
}
a{
	text-decoration: none; 
	color: #2696c6;
}
table {
    border-spacing: 0;    
}
fieldset, img {
    border: 0;
}
address, caption, cite, code, dfn, em, strong, th, var {
    font-weight: normal; 
	font-style: normal;
}
strong{
	font-weight: bold;
}
ol, ul, li, p{
    list-style: none; 
	margin: 0; 
	padding: 0;
}
span{
	font-size:15px; 
	text-align:justify;
}
caption, th {
    text-align: left;

}
h1, h2, h3, h4, h5, h6 {
    font-family:'Roboto', Arial, Helvetica, sans-serif; 
	font-weight: normal; 
	font-size: 100%; 
	margin: 0; 
	padding: 0; 
	color: #444; 
}
q:before, q:after {
    content:'';
}
abbr, acronym { 
	border: 0;
}
.clearfix{
	clear:both; 
	list-style:none; 
	padding:0; 
	margin:0;
}

/* Custom CSS */
body{
	font-family:'Roboto', Arial, Helvetica, sans-serif; 
	color: #333; 
	color: rgba(0,0,0,0.5);
}
#main-wrap{
	width: 100%; 
	height: auto;
}
.maintop-wraptop1{
	width: 1760px; 
	height: 720px; 
	background: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/hoa_don_new/bg-1a.jpg) center top no-repeat;
}
.mainbottom-wrapbottom1{
	width: 1760px; 
	height: 520px; 
	background: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/hoa_don_new/bg-1b.jpg) center top no-repeat;
}
.maintop-wraptop2{
	width: 1760px; 
	height: 720px; 
	background: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/hoa_don_new/bg-2a.jpg) center top no-repeat;
}
.mainbottom-wrapbottom2{
	width: 1760px; 
	height: 520px; 
	background: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/hoa_don_new/bg-2b.jpg) center top no-repeat;
}

/* So hoa don */
.div-invoicenumber{
	float: left; padding-top:23px;	
}
.div-invoicenumber .li-1{
	float: left; 
	width: 110px;
}
.div-invoicenumber .li-2{
	float: left; 
	width: 155px;
}
.div-invoicenumber .li-3{
	float: left; 
	width: 190px;
}
.div-invoicenumber .li-4{
	float: left; 
	width: 445px;	
}
.div-invoicenumber .li-sohoadon{
	float: left; 
	width: 200px; 
	font-size:16pt; 
	color: #000; 
	text-align: left;	
}
.div-invoicenumber .input-shd{
	width:100%; 
	height:auto; 
	font-size: 16pt; 
	text-align:left; 
	background:none; 
	border:none; 
	letter-spacing:1px;
}

/* Customer */
.div-customer{
	padding-top:48px;	 
}
.div-customer .li-c1{
	float:left; 
	width:610px;	
}
.div-customer .li-c2{
	float:left; 
	width:560px;	
}
.div-customer .li-c3{
	float:left; 
	width:600px;	
}
.div-customer .li-customer{
	float:left; 
	width:1130px; 
	font-size:16pt; 
	color:#000; 
	text-align:left;	
}
.div-customer .input-customer{
	width:100%; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	background:none; 
	border:none;
}
.div-customer .li-address{
	float:left; 
	width:1180px; 
	font-size:16pt; 
	color:#000; 
	padding-top:22px; 
	text-align:left;	
}
.div-customer .input-add{
	width:100%; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	background:none; 
	border:none;
}
.div-customer .li-mst{
	float:left; 
	width:400px; 
	font-size:16pt; 
	color:#000; 
	padding-top:23px; 
	text-align:left;	
}
.div-customer .input-mst{
	width:100%; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	background:none; 
	border:none; 
	letter-spacing:5px;	
}

/* Products */
.div-products{
	 float:left; 
	 padding:77px 0 0 20px; 
	 font-size:16pt; 
	 color:#000;
}
.div-products .div-raw{
	margin-top:18px;	
}
.div-products .div-raw-hidden{
	overflow:hidden;
}
.div-products .li-raw-1, .li-raw-2, .li-raw-3, .li-raw-4, .li-raw-5, .li-raw-6, .li-raw-7, .li-raw-8{
	float:left;
}
.div-products .li-raw-1{
	width:55px;
}
.div-products .li-raw-2{
	width:885px;
	padding-left:15px;
}
.div-products .li-raw-3{
	width:60px;
}
.div-products .li-raw-4{
	width:60px;
}
.div-products .li-raw-5{
	width:180px; 
	padding-right:10px;
}
.div-products .li-raw-6{
	width:190px;
	padding-right:10px;
}
.div-products .li-raw-7{
	width:60px;
}
.div-products .li-raw-8{
	width:180px;
	padding-right:10px;
}
.div-products .li-raw-1-input, .li-raw-2-input, .li-raw-3-input, .li-raw-4-input, .li-raw-5-input, .li-raw-6-input, .li-raw-7-input, .li-raw-8-input{
	width:100%; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	background:none; 
	border:none;	
}
.div-products .li-raw-1-input{
	text-align:center;
}
.div-products .li-raw-2-input{
	text-align:left;
}
.div-products .li-raw-3-input{
	text-align:center;
}
.div-products .li-raw-4-input{
	text-align:center;
}
.div-products .li-raw-5-input{
	text-align:right;
}
.div-products .li-raw-6-input{
	text-align:right;
}
.div-products .li-raw-7-input{
	text-align:center;
}
.div-products .li-raw-8-input{
	text-align:right;
}


/* Payments */
.div-sign{
	float:left; 
	width:52%;
}
.div-payment-tien{
	float:left; 
	width:47%; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	text-align:left;
	padding-top:4px;
}
.li-tienhang{
	float:left;
	width:200px; 
	padding:18px 0 0 365px;	
}
.li-tienhang-input{
	width:100%; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	text-align:right; 
	background:none; 
	border:none;	
}
.li-tienvat{
	float:left; 
	width:200px; 
	padding:18px 0 0 50px;	
}
.li-tienvat-input{
	width:100%; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	text-align:right; 
	background:none; 
	border:none;	
}
.li-tongtien{
	width:250px; 
	padding:20px 0 0 565px;	
}
.li-tongtien-input{
	width:100%; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	text-align:right; 
	background:none; 
	border:none;	
}
.li-payment-text{
	float:left; 
	width:500px; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	text-align:left; 
	padding-top:10px; 
	margin-left:320px;
	letter-spacing:-1px;	
}
.li-payment-text-input{
	width:99%; 
	height:auto; 
	font-size:16pt; 
	color:#000; 
	text-align:left; 
	line-height:45px; 
	background:none; 
	border:none;
}
    </style>    
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-2.0.3.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common/function.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/invoice/create/function.js"></script>
<script type="text/javascript">
    function editAutoTienHangAndTax(quantity_node, price_has_tax_node, sum_node, tax_sum_node, tax) {
        <?php
        if(Yii::app()->session['calculate_way']=='2'){
            echo "return;";
        }
        ?>
        if(tax=='/'){
            tax=0;
        }
        quantity = $(quantity_node).val() + "";
        price_has_tax = $(price_has_tax_node).val() + "";
        sum = $(sum_node).val() + "";
        tax_sum = $(tax_sum_node).val() + "";
        /**
         * 
         */
        if (quantity.indexOf(".") != -1) {
            quantity = quantity.split(".").join("");
        }
        if (price_has_tax.indexOf(".") != -1) {
            price_has_tax = price_has_tax.split(".").join("");
        }
        if (sum.indexOf(".") != -1) {
            sum = sum.split(".").join("");
        }
        if (tax_sum.indexOf(".") != -1) {
            tax_sum = tax_sum.split(".").join("");
        }
        /**
         * 
         */
        quantity = parseInt(quantity);
        price_has_tax = parseInt(price_has_tax);
        //new
        price_not_tax = price_has_tax / ((tax + 100) / 100);
        price_not_tax = price_not_tax.toFixed(2);
        //end new
        sum = parseInt(sum);
        tax_sum = parseInt(tax_sum);
        tax = parseInt(tax);
        hieu = Math.abs(quantity * price_has_tax - (sum + tax_sum));

        if (hieu == 0) {
            return;
        }

        if (quantity * price_has_tax > sum + tax_sum) {//tăng thuế lên
            tax_sum += hieu;
            tax_sum = numberWithCommas(tax_sum);
            $(tax_sum_node).val(tax_sum);
            
        }
        else {//giảm tiền hàng xuống
            sum -= hieu;
            sum = numberWithCommas(sum);
            $(sum_node).val(sum);

            

        }
    }
    function setTienHangAndTax(quantity, price, tax, sum_node, tax_sum_node) {
        if(tax=='/'){
            tax=0;
        }

        /**
         * bỏ dấu phẩy và dấu chấm trong chuỗi số để cộng trừ nhân chia với số
         */
        if (quantity.indexOf(".") != -1) {
            quantity = quantity.split(".").join("");
        }
        /**
         *          
         */
        sum = quantity * price;
        tax_sum = sum * tax / 100;
        sum = sum.toFixed(0);
        tax_sum = tax_sum.toFixed(0);
        sum = numberWithCommas(sum);
        
        $(sum_node).val(sum);
        tax_sum = numberWithCommas(tax_sum);
        $(tax_sum_node).val(tax_sum);
    }
    jQuery(function($) {
        if ($("li.li-address").css('height') == '50px') {
            $("li.li-mst").css("padding-top", "0px");
        }
        quantities = $("input[name='sl']");

        for (i = 0; i < quantities.length; i++) {
            tax = $(quantities[i]).parent().next().next().next().find("input").eq(0).val();
            if(tax=='/'){
                tax=0;
            }
            tax = parseInt(tax);
            sum_node = $(quantities[i]).parent().next().next().find("input").eq(0);
            tax_sum_node = $(quantities[i]).parent().next().next().next().next().find("input").eq(0);
            price_has_tax_node = $(quantities[i]).parent().parent().next();
            //
            price = $(price_has_tax_node).val();

            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            price_not_tax_double = temp.toFixed(2);
            //
            setTienHangAndTax($(quantities[i]).val(), price_not_tax_double, tax, sum_node, tax_sum_node);
            <?php
            if(Yii::app()->session['calculate_way']=='1'){
                echo "editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_sum_node, tax);";
            }
            ?>
            

        }        
    });
</script>
</head>

<body>
<center>
<?php 
$created_at=  explode(".", $invoicefull_model->created_at);
$created_at=  $created_at[2].".".$created_at[1].".".$created_at[0];
?>
<!-- main -->
<div id="main-wrap">
	<!-- main top -->
	<div class="maintop-wraptop<?php echo trim($lien);?>">
	    
        <!-- Invoice number -->
        <div class="div-invoicenumber">
            <ul>
                <li class="li-1">&nbsp;</li>
                <li class="li-sohoadon"><input name="" class="input-shd" value="<?php echo Yii::app()->session['mau_so'];?>" type="text" /></li>
                <li class="li-2">&nbsp;</li>
                <li class="li-sohoadon"><input name="" class="input-shd" value="<?php echo Yii::app()->session['sign'];?>" type="text" /></li>
                <li class="li-3">&nbsp;</li>
                <li class="li-sohoadon"><input name="" class="input-shd" value="<?php echo $invoicefull_model->bill_number; ?>" type="text" /></li>
                <li class="li-4">&nbsp;</li>
                <li class="li-sohoadon"><input name="" class="input-shd" value="<?php echo $created_at;// date(Yii::app()->session['date_format']);?>" type="text" /></li>
            </ul>
        </div>
        <div class="clearfix"></div>
        
        <!-- Customer -->
        <div class="div-customer">
            <ul>
                <li class="li-c1">&nbsp;</li>
                <li class="li-customer"><input type="text" name="customer" value="<?php echo $invoicefull_model->full_name; ?>" class="input-customer" /></li>
                <li class="clearfix"></li>
                <li class="li-c2">&nbsp;</li>
                <li class="li-address"><input type="text" name="add" value="<?php echo $invoicefull_model->address; ?>" class="input-customer" /></li>
                <li class="clearfix"></li>
                <li class="li-c3">&nbsp;</li>
                <li class="li-mst"><input type="text" name="customer" value="<?php echo implode(' ',str_split($invoicefull_model->tax_code)) ;if(trim($invoicefull_model->tax_code_chinhanh)!='') echo ' - &nbsp;'. implode(' ',str_split($invoicefull_model->tax_code_chinhanh)) ;?>" class="input-mst" /></li>
            </ul>
        </div>
        <div class="clearfix"></div>
        
        <!-- div-products -->
        <div class="div-products">
             <?php
            $i = 1;            
            foreach ($bill_details as $bill_detail) {
                ?>   
        	<div class="div-raw">
                    <ul>
                        <li class="li-raw-1"><input type="text" name="number" value="<?php echo $i++; ?>" class="li-raw-1-input" /></li>
                        <li class="li-raw-2"><input type="text" name="namepro" value="<?php echo $bill_detail->goods_full_name; ?>" class="li-raw-2-input" /></li>
                        <li class="li-raw-3"><input type="text" name="dvt" value="<?php echo $bill_detail->unit_full_name; ?>" class="li-raw-3-input" /></li>
                        <li class="li-raw-4"><input type="text" name="sl" value="<?php echo $bill_detail->quantity; ?>" class="li-raw-4-input" /></li>
                        <li class="li-raw-5"><input type="text" name="dongia" value="<?php echo $bill_detail->price; ?>" class="li-raw-5-input" /></li>
                        <li class="li-raw-6"><input type="text" name="number" value="<?php echo $bill_detail->sum; ?>" class="li-raw-6-input" /></li>
                        <li class="li-raw-7"><input type="text" name="dongia" value="<?php echo ($bill_detail->tax=="0")?"/":$bill_detail->tax; ?>" class="li-raw-7-input" style="width: 50%;margin-right: 0;" /><?php if($bill_detail->tax!="0"){?><label style="float: right;padding: 0;margin: 0;">%</label><?php }?></li>
                        <li class="li-raw-8"><input type="text" name="number" value="<?php echo $bill_detail->sum_tax; ?>" class="li-raw-8-input" /></li>
                    </ul>
                    <input type="hidden" name="price_has_tax[]" value="<?php echo $bill_detail->price_has_tax; ?>"/>
                </div>
                <div class="clearfix"></div>
            <?php
            }
            
            ?>
            
            
        </div>
            
    </div>
    <div class="clearfix"></div>
    
    <!-- main bottom -->
	<div class="mainbottom-wrapbottom<?php echo trim($lien);?>">
	    
        <!-- text payment -->
        <div class="div-sign">&nbsp;</div>
        
        <!-- number payment -->
        <div class="div-payment-tien">
            <ul>
                <li class="li-tienhang"><input type="text" name="tienhang" value="<?php echo $invoicefull_model->sum; ?>" class="li-tienhang-input" /></li>
                <li class="li-tienvat"><input type="text" name="tienvat" value="<?php echo $invoicefull_model->tax_sum; ?>" class="li-tienvat-input" /></li>
                <li class="clearfix"></li>
                
                <li class="li-tongtien"><input type="text" name="tongtien" value="<?php echo $invoicefull_model->sum_all; ?>" class="li-tongtien-input" /></li>
                <li class="clearfix"></li>
                
                <li class="li-payment-text"><textarea name="tienchu" rows="2" class="li-payment-text-input"><?php $NumberToWords=Yii::app()->NumberToWords;echo ucfirst($NumberToWords->convert_number_to_words(str_replace(".", "", $invoicefull_model->sum_all),  NumberToWords::VIETNAM_LANGUAGE))." đồng"; ?></textarea></li>
            </ul>
        </div>
        <div class="clearfix"></div>
        
    </div>
    <div class="clearfix"></div>
        
</div>
<!-- end main -->

</center>
</body>

