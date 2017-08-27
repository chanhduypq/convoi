<script type="text/javascript">
jQuery(function($) {
    $('.numeric').number(true);
    $("body").delegate(".numeric", "keyup", function() {
        if($(this).attr('id')=="sum1"){
            sum1=$(this).val();
            if (sum1.indexOf(".") != -1) {
                sum1 = sum1.split(".").join("");
            }
            sum2=$("#sum2").val();
            if (sum2.indexOf(".") != -1) {
                sum2 = sum2.split(".").join("");
            }
            
        }
        else if($(this).attr('id')=="sum2"){
            sum2=$(this).val();
            if (sum2.indexOf(".") != -1) {
                sum2 = sum2.split(".").join("");
            }
            sum1=$("#sum1").val();
            if (sum1.indexOf(".") != -1) {
                sum1 = sum1.split(".").join("");
            }
        }
        if($.trim(sum1)==''){
            sum1='0';
        }
        if($.trim(sum2)==''){
            sum2='0';
        }
        $('input[name="sum"]').val(sum1);
        $('input[name="tax_sum"]').val(sum2);
        sum1=parseInt(sum1);
        sum2=parseInt(sum2);
        temp=sum1+sum2;
        temp=numberWithCommas(temp);
        
        $("#sum_sum_and_tax").html(temp);
        
    });
});
</script>