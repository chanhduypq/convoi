<input type="hidden" name="reason"/>
    <input type="hidden" id="edit_lien1_lien2"/>
    <input type="hidden" name="print" id="print"/>
<div style="display: none;" id="dialog-modal-reason">
    <div class="error">
        Vui lòng nhập lý do phía dưới
    </div>
    <textarea rows="15" cols="40" placeholder="Nhập lý do tại đây"></textarea>     
</div>
<script type="text/javascript">

var bill_number='';

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



    
    function submit(type,bill_number) {
        if (type == '2') { 
            $("head").append("<link href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>");
            $("head").append('<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font_roboto.css" />');
            $("#print_loading").css({top:'50%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'}).show();
            <?php Yii::app()->session['url']=Yii::app()->request->url;?>
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/sxdvfull/updateandprint1/'); ?>',
                data: $("form#form_common").serialize(),
                success: function(data, textStatus, jqXHR) {
//                    var find = 'fi';
//                    var re = new RegExp(find, 'g');
//                    data = data.replace(re, '<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>');
//                    
//                    find = 'clear<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>';
//                    re = new RegExp(find, 'g');
//                    data = data.replace(re, 'clearfi');
                    
                    var iframe=document.createElement('iframe');
                    iframe.setAttribute("id","a");
                    document.body.appendChild(iframe);
                    var iframedoc=iframe.contentDocument||iframe.contentWindow.document;
                    iframedoc.body.innerHTML=data;
                    editMoney();
                    html2canvas(iframedoc.body, {            
                        onrendered: function(canvas) {
                            var imgString = canvas.toDataURL("image/png");

                            $.ajax({ 
                                async: false,
                                cache: false,
                                url: '<?php echo $this->createUrl('/ajax/print');?>',
                                type: "POST",
                                data: {content: imgString, bill_number: bill_number, lien: '1'},
                                success: function(data, textStatus, jqXHR) {  
                                    if($("#print").val()=='1'){
                                        window.location='<?php echo $this->createUrl('/sxdv/print');?>';
                                    }
                                    else{
                                        window.location='<?php echo $this->createUrl('/sxdv/preview');?>';
                                    }
                                    
                                }
                            });

                        }
                    });                    
                }
            });
        }
        else if (type == '3') {
            $("head").append("<link id='tuetc' href='http://fonts.googleapis.com/css?family=Droid+Serif|Roboto' rel='stylesheet' type='text/css'>");
            $("head").append('<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font_roboto.css" />');
            $("#print_loading").css({top:'50%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'}).show();
            <?php Yii::app()->session['url']=Yii::app()->request->url;?>
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/sxdvfull/updateandprint2/'); ?>',
                data: $("form#form_common").serialize(),
                success: function(data, textStatus, jqXHR) {
//                    var find = 'fi';
//                    var re = new RegExp(find, 'g');
//                    data = data.replace(re, '<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>');
//                    
//                    find = 'clear<font style="font-family:Arial, Helvetica, sans-serif;">fi</font>';
//                    re = new RegExp(find, 'g');
//                    data = data.replace(re, 'clearfi');
                    
                    var iframe=document.createElement('iframe');
                    iframe.setAttribute("id","a");
                    document.body.appendChild(iframe);
                    var iframedoc=iframe.contentDocument||iframe.contentWindow.document;
                    iframedoc.body.innerHTML=data;
                    editMoney();
                    html2canvas(iframedoc.body, {            
                        onrendered: function(canvas) {
                            var imgString = canvas.toDataURL("image/png");

                            $.ajax({ 
                                async: false,
                                cache: false,
                                url: '<?php echo $this->createUrl('/ajax/print');?>',
                                type: "POST",
                                data: {content: imgString, bill_number: bill_number, lien: '2'},
                                success: function(data, textStatus, jqXHR) {  
                                    if($("#print").val()=='1'){
                                        window.location='<?php echo $this->createUrl('/sxdv/print');?>';
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

                        }
                    });                    
                }
            });
        }
        


        

    }
    jQuery(function($) {
        $("textarea").css("height","50px");
        function print(){
            if ($.trim($("#dialog-modal-reason textarea").val()) == "") {
                $("#dialog-modal-reason div").show();
                $("#dialog-modal-reason textarea").focus();
            }
            else {
                $("#print").val('1');
                jQuery("#dialog-modal-reason").dialog('close');
                submit($("#edit_lien1_lien2").val(),bill_number);
            }
        }
        function preview(){
            if ($.trim($("#dialog-modal-reason textarea").val()) == "") {
                $("#dialog-modal-reason div").show();
                $("#dialog-modal-reason textarea").focus();
            }
            else {
                $("#print").val('0');
                jQuery("#dialog-modal-reason").dialog('close');
                submit($("#edit_lien1_lien2").val(),bill_number);

            }
        }
        function download(){
            if ($.trim($("#dialog-modal-reason textarea").val()) == "") {
                $("#dialog-modal-reason div").show();
                $("#dialog-modal-reason textarea").focus();
            }
            else {
                $("#print").val('-1');
                jQuery("#dialog-modal-reason").dialog('close');
                submit($("#edit_lien1_lien2").val(),bill_number);

            }
        }
        
        $("body").delegate(".print_bill1", "click", function() {           
            current_node = $(this).parent();
            bill_number=$.trim($(current_node).parent().find('td').eq(0).find('a').eq(0).html());
            count = $.trim($(this).parent().find("p").eq(1).html());

            if (count == '0') {
                $("#print").val('1');
                href = $(current_node).parent().find("a").eq(0).attr("href");
                href = href.split("/");
                id = href[href.length - 1];
                $("#bill_id").val(id);

                submit('2',bill_number);

            }
            else {
                $("#save_reason").find("a").eq(0).html("In");
                $("#preview_reason").show();

                jQuery("#dialog-modal-reason").dialog({
                    title:'In hóa đơn',
                    create: function(event, ui) {
                        $("body").css({overflow: 'hidden'});
                        $('.title-HD.sort').css('z-index','1');
                    },
                    beforeClose: function(event, ui) {
                        $("body").css({overflow: 'inherit'});
                        $('form#form_common input[name="reason"]').val($("#dialog-modal-reason textarea").val());

                    },
                    position: ['top', 110],
                    height: 250,
                    width: 500,
                    show: {effect: "slide", duration: 500},
                    hide: {effect: "slide", duration: 500},
                    modal: true,
                    open: function(event, ui) {                    
                        
                        $("#dialog-modal-reason div").hide();
                        $("#dialog-modal-reason textarea").val('');
                        
                        $("#edit_lien1_lien2").val('2');
                        href = $(current_node).parent().find("a").eq(0).attr("href");
                        href = href.split("/");
                        id = href[href.length - 1];
                        $("#bill_id").val(id);
                        $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                        $(".ui-dialog-buttonset").find("button").eq(1).addClass("save");
                        
                        
                    },
                    buttons: {
                        "In": print,
                        "Xem trước": preview
                    } 
                });

            }



        });
        $("body").delegate(".print_bill2", "click", function() {
            current_node = $(this).parent();
            bill_number=$.trim($(current_node).parent().find('td').eq(0).find('a').eq(0).html());
            count = $.trim($(this).parent().find("p").eq(1).html());

            if (count == '0') {
                $("#print").val('1');
                href = $(current_node).parent().find("a").eq(0).attr("href");
                href = href.split("/");
                id = href[href.length - 1];
                $("#bill_id").val(id);

                submit('3',bill_number);


            }
            else {
                $("#save_reason").find("a").eq(0).html("In");
                $("#preview_reason").show();

                jQuery("#dialog-modal-reason").dialog({
                    create: function(event, ui) {
                        $("body").css({overflow: 'hidden'});
                        $('.title-HD.sort').css('z-index','1');
                    },
                    beforeClose: function(event, ui) {
                        $("body").css({overflow: 'inherit'});
                        $('form#form_common input[name="reason"]').val($("#dialog-modal-reason textarea").val());

                    },
                    position: ['top', 110],
                    height: 300,
                    width: 500,
                    show: {effect: "slide", duration: 500},
                    hide: {effect: "slide", duration: 500},
                    modal: true,
                    open: function(event, ui) {
                        $("#dialog-modal-reason div").hide();
                        $("#dialog-modal-reason textarea").val('');
                        
                        $("#edit_lien1_lien2").val('3');
                        href = $(current_node).parent().find("a").eq(0).attr("href");
                        href = href.split("/");
                        id = href[href.length - 1];
                        $("#bill_id").val(id);
                        
                        $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                        $(".ui-dialog-buttonset").find("button").eq(1).addClass("save");
                        $(".ui-dialog-buttonset").find("button").eq(2).addClass("save");
                        
                    },
                    buttons: {
                        "In": print,
                        "Xem trước": preview,
                        "Download":download
                    } 
                });

            }



        });


    });
</script>
<style>
button.ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-icon-only.ui-dialog-titlebar-close{
    display: block !important;
}
</style>