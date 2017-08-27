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
        
        <?php
        $rows=  SocaiFull::model()->findAll("giao_dich='Chi phí dịch vụ có hóa đơn' and bill_chi_phi_id is null");
        ?>
        
        <li class="e_title">&nbsp;</li>
            <li class="e_content1" style="width: 750px;">
                <div id="tam_ung" style="max-height: 500px;overflow-y: auto;height: auto;">
                    <table>
                        <tbody>
                            <?php
                            foreach ($rows as $row){
                            ?>   
                            <tr>
                                <td style="width: 5%;">
                                    <input style="float: left;" type="radio" name="tam_ung_radio" id="<?php echo $row->id;?>" value="<?php echo $row->id;?>"/>
                                </td>
                                <td style="width: 15%;">
                                    <?php echo $row->created_at;?>
                                </td>
                                <td style="width: 15%;">
                                    <?php echo $row->chi;?>
                                </td>
                                <td style="width: 65%;">
                                    <?php echo FunctionCommon::crop($row->content, 50, true);?>
                                </td>
                            </tr>


                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </li>  
        <li class="clearfix"></li>

    </div>
    <div class="error" id="error_select" style="display: none;margin-left: 0px;">Vui lòng chọn tạm ứng</div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        var tam_ung='1';
        
        $('input[name="tam_ung"]').change(function() {
            tam_ung=$(this).val();  
            if(tam_ung=='0'){
                $('#tam_ung').parent().hide();
                $("div#error_select").hide();
            }
            else{
                $('#tam_ung').parent().show();
            }
        });
        

        
        $("#add-new").find('.but-add').eq(1).click(function() {
            if($("#tam_ung").find("tr").length==0){
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
//                        jQuery("#dialog-modal-noidia_quocte").hide();
                    }
                }  
            });

        });
        function go_to_page(){
            if (tam_ung == '1') {
                if($("input[name='tam_ung_radio']:checked").length==0){
                    $("div#error_select").show();
                }
                else{                    
                    window.location = "<?php echo $this->createUrl('/'.Yii::app()->controller->id.'/create/');?>/socai_id/"+$("input[name='tam_ung_radio']:checked").val();
                }
                
            }
            else if (tam_ung == '0') {
                window.location = "<?php echo $this->createUrl('/'.Yii::app()->controller->id.'/create');?>";
            }

        }
        
    });
</script>