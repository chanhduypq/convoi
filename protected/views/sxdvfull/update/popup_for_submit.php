<div style="display: none;" id="dialog-modal-reason">
    <div class="error">
        Vui lòng nhập lý do phía dưới
    </div>
    <textarea rows="15" cols="40" placeholder="Nhập lý do tại đây"></textarea>  
    <li class="clearfix"></li>
</div>

<script type="text/javascript">
    function setTienHangAndTax1(quantity, price_not_tax, tax, sum_node, tax_sum_node) {
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
        sum = quantity * price_not_tax;
        tax_sum = sum * tax / 100;
        sum = sum.toFixed(0);
        tax_sum = tax_sum.toFixed(0);
        sum = numberWithCommas(sum);
        $(sum_node).val(sum);
        tax_sum = numberWithCommas(tax_sum);
        $(tax_sum_node).val(tax_sum);


    }    
    function editMoney(){

        if ($("li.li-address").css('height') == '50px') {
                $("li.li-mst").css("padding-top", "0px");
        }
        quantities = $("#a").contents().find("input[name='sl']");
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
            setTienHangAndTax1($(quantities[i]).val(), price_not_tax_double, tax, sum_node, tax_sum_node);
            <?php
            if(Yii::app()->session['calculate_way']=='1'){
                echo "editAutoTienHangAndTax($(quantities[i]), price_has_tax_node, sum_node, tax_sum_node, tax);";
            }
            ?>


        } 

    }

    function submit(type) {
        if (type == '1') {//user click button lưu
            $("form#update_bill").removeAttr("target");
            $("form#update_bill").attr("action", "<?php echo $this->createUrl("/sxdvfull/update"); ?>");
            $("form#update_bill").submit();
        }
        else if (type == '2') {//user click button hóa đơn liên 1
            $("head").append("<link href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>");
            $("head").append('<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font_roboto.css" />');
            $("#print_loading").css({top: '50%', left: '50%', margin: '-' + ($('#myDiv').height() / 2) + 'px 0 0 -' + ($('#myDiv').width() / 2) + 'px'}).show();
<?php Yii::app()->session['url'] = Yii::app()->request->url; ?>
            $.ajax({
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/sxdvfull/updateandprint1/'); ?>',
                data: $("form#update_bill").serialize(),
                success: function(data, textStatus, jqXHR) {
//                    var find = 'fi';
//                    var re = new RegExp(find, 'g');
//                    data = data.replace(re, '<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>');
//                    
//                    find = 'clear<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>';
//                    re = new RegExp(find, 'g');
//                    data = data.replace(re, 'clearfi');

                    var iframe = document.createElement('iframe');
                    iframe.setAttribute("id","a");
                    document.body.appendChild(iframe);
                    var iframedoc = iframe.contentDocument || iframe.contentWindow.document;
                    iframedoc.body.innerHTML = data;
                    editMoney();
                    html2canvas(iframedoc.body, {
                        onrendered: function(canvas) {
                            var imgString = canvas.toDataURL("image/png");

                            $.ajax({
                                async: false,
                                cache: false,
                                url: '<?php echo $this->createUrl('/ajax/print'); ?>',
                                type: "POST",
                                data: {content: imgString, bill_number: '<?php echo $bill_number; ?>', lien: '1'},
                                success: function(data, textStatus, jqXHR) {
                                    if ($("#print").val() == '1') {
                                        window.location = '<?php echo $this->createUrl('/sxdv/print'); ?>';
                                    }
                                    else {
                                        window.location = '<?php echo $this->createUrl('/sxdv/preview'); ?>';
                                    }

                                }
                            });

                        }
                    });
                }
            });
        }
        else if (type == '3') {//user click button hóa đơn liên 2
        $("head").append("<link id='tuetc' href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>");
        $("head").append('<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font_roboto.css" />');
            $("#print_loading").css({top: '50%', left: '50%', margin: '-' + ($('#myDiv').height() / 2) + 'px 0 0 -' + ($('#myDiv').width() / 2) + 'px'}).show();
<?php Yii::app()->session['url'] = Yii::app()->request->url; ?>
            $.ajax({
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/sxdvfull/updateandprint2/'); ?>',
                data: $("form#update_bill").serialize(),
                success: function(data, textStatus, jqXHR) {
//                    var find = 'fi';
//                    var re = new RegExp(find, 'g');
//                    data = data.replace(re, '<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>');
//                    
//                    find = 'clear<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>';
//                    re = new RegExp(find, 'g');
//                    data = data.replace(re, 'clearfi');
                    
                    var iframe = document.createElement('iframe');
                    iframe.setAttribute("id","a");
                    document.body.appendChild(iframe);
                    var iframedoc = iframe.contentDocument || iframe.contentWindow.document;
                    iframedoc.body.innerHTML = data;
                    editMoney();
                    html2canvas(iframedoc.body, {
                        onrendered: function(canvas) {
                            var imgString = canvas.toDataURL("image/png");

                            $.ajax({
                                async: false,
                                cache: false,
                                url: '<?php echo $this->createUrl('/ajax/print'); ?>',
                                type: "POST",
                                data: {content: imgString, bill_number: '<?php echo $bill_number; ?>', lien: '2'},
                                success: function(data, textStatus, jqXHR) {
                                    if ($("#print").val() == '1') {
                                        window.location = '<?php echo $this->createUrl('/sxdv/print'); ?>';
                                    }
                                    else if($("#print").val()=='0'){
                                        window.location='<?php echo $this->createUrl('/sxdv/preview');?>';
                                    }
                                    else{
                                        window.location='<?php echo $this->createUrl('/sxdv/download');?>';
                                        $("#print_loading").hide();
                                        $(iframe).remove();
                                        $("#tuetc").remove();
                                    }

                                }
                            });

                        },
                        taintTest:false
                    });
                }
            });
        }
        

    }
    
</script>