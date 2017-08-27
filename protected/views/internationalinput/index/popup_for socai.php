<div id="dialog-modal-noidia_quocte" style="display: none;">
    <div class="edit-KH" id="khachhang">    
        
        <li class="e_title">&nbsp;</li>
            <li style="float: left;width: 500px;border:1px solid #bbbdbe;height: 33px;padding-top: 5px;padding-left: 0px;">
                
                <label>
                    <input id="thu" checked="checked" type="radio" name="tam_ung" value="1"/>Đã tạm ứng
                </label>
                <label>
                    <input id="chi" type="radio" name="tam_ung" value="0"/>Chưa tạm ứng
                </label>
                
            </li>
        <li class="clearfix"></li>
        <div style="max-height: 500px;overflow-y: auto;height: 500px;" id="tam_ung1">
        <?php
        $rows=  SocaiFull::model()->findAll("giao_dich like '%Tờ khai%' and bill_input_id is null");
        ?>
        
        
                <?php
                foreach ($rows as $row){

                    if(is_numeric($row->payment_method_id3)){
                        $payment_method_type="Giá trị hàng hóa (VND)";
                        $type='3';
                    }
                    else if(is_numeric($row->payment_method_id4)){
                        $payment_method_type="Chi phí ngân hàng (VND)";
                        $type='4';
                    }
                    else if(is_numeric($row->payment_method_id5)){
                        $payment_method_type="Tiền thuế (VND)";
                        $type='5';
                    }
                    else{
                        $payment_method_type='';
                        $type='0';
                    }
                ?>  
                    <li class="e_title">&nbsp;</li>
                    <li class="e_content1" style="width: 500px;text-align: left;padding-top: 5px;padding-bottom: 5px;">
                        <input id="<?php echo $row->id;?>" style="float: left;margin-right: 10px;cursor: pointer;" type="checkbox" value="<?php echo $row->id.'|'.$type;?>" name="tam_ung[]"/>
                        <label title="<?php echo $row->content;?>" style="cursor: pointer;" for="<?php echo $row->id;?>">                                
                                <?php echo $row->created_at.' | '.$row->chi.' | '.$payment_method_type.' | '.  FunctionCommon::crop($row->content, 50, true) ;?>
                        </label>
                    </li>
                    <li class="clearfix"></li>
                <?php
                }
                ?>
<!--                <select id="tam_ung" style="width: 300px;">
                    <option value="">--Chọn tạm ứng--</option>
                    <?php
//                    foreach ($rows as $row){
//                        
//                        if(is_numeric($row->payment_method_id3)){
//                            $payment_method_type="Giá trị hàng hóa (VND)";
//                            $type='3';
//                        }
//                        else if(is_numeric($row->payment_method_id4)){
//                            $payment_method_type="Chi phí ngân hàng (VND)";
//                            $type='4';
//                        }
//                        else if(is_numeric($row->payment_method_id5)){
//                            $payment_method_type="Tiền thuế (VND)";
//                            $type='5';
//                        }
//                        else{
//                            $payment_method_type='';
//                            $type='0';
//                        }
                    ?>                    
                    <option value="<?php // echo $row->id.'|'.$type;?>"><?php // echo $row->created_at.' | '.$row->chi.' | '.$payment_method_type.' | '.  FunctionCommon::crop($row->content, 50, true) ;?></option>
                    <?php
//                    }
                    ?>
                </select>-->
              
        </div>

    </div>
    <div class="error" id="error_select" style="display: none;margin-left: 0px;">Vui lòng chọn ít nhất 1 tạm ứng</div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        var tam_ung='1';
        
        $('input[name="tam_ung"]').change(function() {
            tam_ung=$(this).val();  
            if(tam_ung=='0'){
                $('#tam_ung1').hide();
                $("div#error_select").hide();
            }
            else{
                $('#tam_ung1').show();
            }
        });
        

        
        $("#add-new").find('.but-add').eq(1).click(function() {
            if($('input[name="tam_ung[]"]').length==0){
                window.location = "<?php echo $this->createUrl('/'.Yii::app()->controller->id.'/create');?>";
                return;
            }
            jQuery("#dialog-modal-noidia_quocte").dialog({
                title: '',
                create: function(event, ui) {
                    $("body").css({overflow: 'hidden'});
                    $('.title-HD.sort').css('z-index','1');
                    
                },
                beforeClose: function(event, ui) {
                    $("body").css({overflow: 'inherit'});
                    jQuery("#dialog-modal-noidia_quocte").hide();
                },
                open: function(event, ui) {
                    $("div#error_select").hide();
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                },
                position: ['top', 110],                
                height: 500,
                width: 900,
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                buttons: {
                    "Đồng ý": go_to_page,
                    "<?php echo Yii::app()->params['text_for_button_close'];?>": function() {
                        jQuery("#dialog-modal-noidia_quocte").dialog('close');
                        $(".ui-dialog-buttonset").html('');
                    }
                }  
            });

        });
        function go_to_page(){
            if (tam_ung == '1') {
                tam_ungs=$('input[name="tam_ung[]"]');
                flag=false;
                for(i=0;i<tam_ungs.length;i++){
                    if($(tam_ungs[i]).is(":checked")){
                        flag=true;
                    }
                }
                
                if(flag==false){
                    $("div#error_select").show();
                }
                else{
                    tam_ungs = $('input[name="tam_ung[]"]:checked:enabled').map(function() {
                        return $(this).val();
                    }).get();
                    window.location = "<?php echo $this->createUrl('/'.Yii::app()->controller->id.'/create/');?>/socai_ids/"+tam_ungs;
//                    temp=$("select#tam_ung").val();
//                    temp=temp.split("|");
//                    value=temp[0];
//                    payment_method_type=temp[1];
//                    window.location = "<?php echo $this->createUrl('/'.Yii::app()->controller->id.'/create/');?>/socai_id/"+value+"/payment_method_type/"+payment_method_type;
                }
                
            }
            else if (tam_ung == '0') {
                window.location = "<?php echo $this->createUrl('/'.Yii::app()->controller->id.'/create');?>";
            }

        }
        
    });
</script>