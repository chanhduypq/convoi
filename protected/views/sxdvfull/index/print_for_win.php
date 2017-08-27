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



    
    function submit(type,bill_number) {
        if (type == '2') {   
            $("#print_loading").css({top:'50%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'}).show();
            <?php Yii::app()->session['url']=Yii::app()->request->url;?>
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/sxdvfull/updateandprint1/'); ?>',
                data: $("form#form_common").serialize(),
                success: function(data, textStatus, jqXHR) {
                    pr=$("#print").val();
                    $("head").html('');
                    $("body").html(data);
                    html2canvas($("body"), {           
                        onrendered: function(canvas) {
                            var imgString = canvas.toDataURL("image/png");

                            $.ajax({ 
                                async: false,
                                cache: false,
                                url: '<?php echo $this->createUrl('/ajax/print');?>',
                                type: "POST",
                                data: {content: imgString, bill_number: bill_number, lien: '1'},
                                success: function(data, textStatus, jqXHR) {  
                                    if(pr=='1'){
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
            $("#print_loading").css({top:'50%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'}).show();
            <?php Yii::app()->session['url']=Yii::app()->request->url;?>
            $.ajax({ 
                async: false,
                cache: false,
                type: "POST",
                url: '<?php echo $this->createUrl('/sxdvfull/updateandprint2/'); ?>',
                data: $("form#form_common").serialize(),
                success: function(data, textStatus, jqXHR) {
                    pr=$("#print").val();
                    $("head").html('');
                    $("body").html(data);
                    html2canvas($("body"), {            
                        onrendered: function(canvas) {
                            var imgString = canvas.toDataURL("image/png");

                            $.ajax({ 
                                async: false,
                                cache: false,
                                url: '<?php echo $this->createUrl('/ajax/print');?>',
                                type: "POST",
                                data: {content: imgString, bill_number: bill_number, lien: '2'},
                                success: function(data, textStatus, jqXHR) {  
                                    if(pr=='1'){
                                        window.location='<?php echo $this->createUrl('/sxdv/print');?>';
                                    }
                                    else if(pr=='0'){
                                        window.location='<?php echo $this->createUrl('/sxdv/preview');?>';
                                    }
                                    else{
                                        window.location='<?php echo $this->createUrl('/sxdv/download');?>';
                                        $("#print_loading").hide();
                                        $(iframe).remove();
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
                        $("body").css({overflow: 'hidden'})
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
                        $("body").css({overflow: 'hidden'})
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