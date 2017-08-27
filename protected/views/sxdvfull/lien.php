<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MNS VAT</title>
<meta http-equiv="X-UA-Compatible" content="IE = edge, chrome = 1">
<meta name="description" content="MNS VAT">
<meta name="viewport" content="width = device-width, initial-scale = 1">

<link href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/hoadoncss.css">
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
            tax = $(quantities[i]).parent().next().find("input").eq(0).val();
            if(tax=='/'){
                tax=0;
            }
            tax = parseInt(tax);
            sum_node = $(quantities[i]).parent().next().next().next().find("input").eq(0);
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

<!-- main -->
<div id="main-wrap">
	<!-- main top -->
	<div class="maintop-wraptop<?php echo trim($lien);?>">
            
        <div class="div-invoicenumber1">
            <ul>
                <li class="li-1">&nbsp;</li>
                <li class="li-sohoadon"><input name="" class="input-shd" value="<?php echo Yii::app()->session['mau_so'];?>" type="text" /></li>
                <li class="li-2">&nbsp;</li>
                <li class="li-date"><input name="" class="input-shd" value="<?php echo Yii::app()->session['sign'];?>" type="text" /></li>
            </ul>
        </div>
	    
        <!-- Invoice number -->
        <div class="div-invoicenumber">
            <ul>
                <li class="li-1">&nbsp;</li>
                <li class="li-sohoadon"><input name="" class="input-shd" value="<?php echo $invoicefull_model['bill_number']; ?>" type="text" /></li>
                <li class="li-2">&nbsp;</li>
                <li class="li-date"><input name="" class="input-shd" value="<?php echo date("d.m.Y");// date(Yii::app()->session['date_format']);?>" type="text" /></li>
            </ul>
        </div>
        <div class="clearfix"></div>
        
        <!-- Customer -->
        <div class="div-customer">
        	<ul>
                <li class="li-c1">&nbsp;</li>
                <li class="li-customer"><input type="text" name="customer" value="<?php echo $invoicefull_model['full_name']; ?>" class="input-customer" /></li>
                <li class="clearfix"></li>                
                <li class="li-c2">&nbsp;</li>
                <li class="li-address"><?php echo $invoicefull_model['address']; ?></li>
                <li class="clearfix"></li>
                <li class="li-c3">&nbsp;</li>
                <li class="li-mst"><?php echo implode(' ',str_split($invoicefull_model['tax_code'])) ;if(trim($invoicefull_model['tax_code_chinhanh'])!='') echo ' - &nbsp;'. implode(' ',str_split($invoicefull_model['tax_code_chinhanh'])) ;?></li>                
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
                        <li class="li-raw-5"><input type="text" name="dongia" value="<?php echo ($bill_detail->tax=="0")?"/":$bill_detail->tax; ?>" class="li-raw-5-input" /></li>
                        <li class="li-raw-6"><input type="text" name="number" value="<?php echo $bill_detail->price; ?>" class="li-raw-6-input" /></li>
                        <li class="li-raw-7"><input type="text" name="dongia" value="<?php echo $bill_detail->sum; ?>" class="li-raw-6-input" /></li>
                        <li class="li-raw-8"><input type="text" name="number" value="<?php echo $bill_detail->sum_tax; ?>" class="li-raw-6-input" /></li>
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
        <div class="div-payment-text"><textarea name="tienchu" rows="2" class="div-payment-text-input"><?php $NumberToWords=Yii::app()->NumberToWords;echo ucfirst($NumberToWords->convert_number_to_words(str_replace(".", "", $invoicefull_model->sum_all),  NumberToWords::VIETNAM_LANGUAGE))." đồng"; ?></textarea></div>
        
        <!-- number payment -->        
        <div class="div-payment-tien">
            <ul>
                <li class="li-tienhang"><input type="text" name="tienhang" value="<?php echo $invoicefull_model->sum; ?>" class="li-tienhang-input" /></li>
                <li class="li-tienvat"><input type="text" name="tienvat" value="<?php echo $invoicefull_model->tax_sum; ?>" class="li-tienvat-input" /></li>
                <li class="clearfix"></li>
                <li class="li-tongtien"><input type="text" name="tongtien" value="<?php echo $invoicefull_model->sum_all; ?>" class="li-tongtien-input" /></li>
            </ul>
        	
        </div>
        <div class="clearfix"></div>
        
    </div>
    <div class="clearfix"></div>
        
</div>
<!-- end main -->



</body>
</html>
