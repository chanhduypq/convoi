<div style="display: none;" id="dialog-modal-delete">
</div>
<script type="text/javascript">
    jQuery(function($) {
        function delete_goods(id){
            jQuery("#dialog-modal-delete").dialog({  
                title: 'Bạn có muốn xóa không?',
                create: function(event, ui) {
                  $("body").css({ overflow: 'hidden' })
                 },
                 beforeClose: function(event, ui) {
                  $("body").css({ overflow: 'inherit' })
                 },
                position: ['top', 110],                
                height: 100,
                width: 400,
                show: {effect: "slide", duration: 500},
                hide: {effect: "slide", duration: 500},
                modal: true,
                open: function(event, ui) {                                                          
                    $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
                    $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
                },
                buttons: {
                    "Có": function() {
                      jQuery("#dialog-modal-delete").dialog('close');
                      $.ajax({ 
                          async: false,
                          cache: false,
                            url: '<?php echo $this->createUrl('/goodsfull/delete'); ?>/id/'+id+'/from_page/<?php echo $from_page;?>',                
                            success: function(data, textStatus, jqXHR) {
                                submit_form_common('<?php echo $this->createUrl('/'.Yii::app()->controller->id.'/index');?>','<?php echo $this->createUrl("/ajax/search"); ?>');                    
                            }
                        });
                    },
                    "Không": function() {
                      jQuery("#dialog-modal-delete").dialog('close');
                      $(".ui-dialog-buttonset").html('');
                    }
                }        
            });
            $(".ui-dialog-buttonset").find("button").eq(0).addClass("save");
            $(".ui-dialog-buttonset").find("button").eq(1).addClass("close");
            
        }
        
        $("body").delegate("td.cancel_goods", "click", function() {
            id=$(this).parents("tr").eq(0).find("td").eq(0).attr("id");
            delete_goods(id);            
        });
        
    });
</script>