<script type="text/javascript">
    jQuery(function($) {        

        if($("#start_date_common").length>0){
            $("#start_date").val($("#start_date_common").val());
        }
        if($("#end_date_common").length>0){
            $("#end_date").val($("#end_date_common").val());
        }    
        if ($("#customer_id_common").length>0&&$("#customer_id_common").val() != "") {
              $("#customer").next().find("span").eq(1).html('<?php echo Yii::app()->session['customer_text_common'];?>');           
        }
        if ($("#goods_id_common").length>0&&$("#goods_id_common").val() != "") {
            $("#goods").next().find("span").eq(1).html('<?php echo Yii::app()->session['goods_text_common'];?>');
        }
        if($("#end_date").length>0){
            date_val1=$("#end_date").val();
            if(date_format=="yymmdd"){
                year1=date_val1.substr(0,4);
                month1=date_val1.substr(4,2);                
            }
            else{
                year1=date_val1.substr(0,4);
                month1=date_val1.substr(5,2);      
            }
            if(month1=='<?php echo date('m');?>'&&year1=='<?php echo date('Y');?>'){
                $("#next_month").removeClass("cursor").css('color','#aaa').unbind("click");
            }
        }
        
    });
</script>





