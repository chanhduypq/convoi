<script type="text/javascript">
    jQuery(function($) {        
        $("#submit").click(function() {            
            if(validate()==true){
                $("form#create_bill").removeAttr("target");
                $("form#create_bill").attr("action", "<?php echo $this->createUrl("/internationalinput/create"); ?>");
                $("form#create_bill").submit();
            }
        });
    });
</script>