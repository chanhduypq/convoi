<script type="text/javascript">
    
    jQuery(function($) {
        $("#mst").keyup(function (){
           setFullName($('#mst').val(),$('#branch_full_name'),$('#branch_address'),$('input[name="branch_id"]'));
        });
        $("#branch_full_name").keyup(function (){
           setTaxCode($('#branch_full_name').val(),$('#mst'),$('#branch_address'),$('input[name="branch_id"]'));
        });
        <?php 
        foreach ($array as $value) {
            $selector=$value['selector'];
            $controller_action_name=$value['controller_action_name'];
            $function_on_select=$value['function_on_select'];
            if($selector[0]!="#"){
                $selector="#$selector";                
            }            
            if($controller_action_name[0]!="/"){
                $controller_action_name="/$controller_action_name";                
            }
            $controller_action_name=  rtrim($controller_action_name,"/");
            ?>
            $('<?php echo $selector;?>').autoComplete({
                minChars: 1,
                source: function(term, response) {
                    $.getJSON('<?php echo $this->createUrl($controller_action_name); ?>', {q: term}, function(data) {
                        response(data);
                    });
                }
                <?php 
                if(trim($function_on_select)!=""){ 
                ?> 
                ,
                onSelect: function(e, term, item) {
                    <?php echo $function_on_select;?>
                }
                <?php 
                }
                ?>
            });
        <?php
        }
        ?>        
    });
</script>