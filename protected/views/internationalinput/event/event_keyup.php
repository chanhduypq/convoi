<script type="text/javascript">
jQuery(function($) {
    $("body").delegate(".numeric", "keyup", function() {
        if($(this).attr("id")=='gia_tri_hang_hoa_vnd'||$(this).attr("id")=='chi_phi_ngan_hang_vnd'){
            sum_tax=$("#sum_sum_tax label").html();
            if(sum_tax==""){
                sum_tax="0";
            }
            if (sum_tax.indexOf(".") != -1) {
                sum_tax = sum_tax.split(".").join("");
            } 
            sum_tax = parseInt(sum_tax);
            
            if($(this).attr("id")=='gia_tri_hang_hoa_vnd'){
                gia_tri_hang_hoa_vnd=$(this).val();
                chi_phi_ngan_hang_vnd=$("#chi_phi_ngan_hang_vnd").val();                
            }
            else{
                chi_phi_ngan_hang_vnd=$(this).val();
                gia_tri_hang_hoa_vnd=$("#gia_tri_hang_hoa_vnd").val();
            }
            if (gia_tri_hang_hoa_vnd.indexOf(".") != -1) {
                gia_tri_hang_hoa_vnd = gia_tri_hang_hoa_vnd.split(".").join("");
            }  
            if (chi_phi_ngan_hang_vnd.indexOf(".") != -1) {
                chi_phi_ngan_hang_vnd = chi_phi_ngan_hang_vnd.split(".").join("");
            } 
            if(gia_tri_hang_hoa_vnd==''){
                gia_tri_hang_hoa_vnd='0';
            }
            if(chi_phi_ngan_hang_vnd==''){
                chi_phi_ngan_hang_vnd='0';
            }
            gia_tri_hang_hoa_vnd = parseInt(gia_tri_hang_hoa_vnd);
            chi_phi_ngan_hang_vnd = parseInt(chi_phi_ngan_hang_vnd);
            sum=gia_tri_hang_hoa_vnd+chi_phi_ngan_hang_vnd+sum_tax;
            $("#gia_tri_thanh_toan_qua_ngan_hang").html(numberWithCommas(sum));
            return;
        }
        
        if($(this).parent().parent().find(".tax_ttdb").eq(0).length>0){
            tax_ttdb = $(this).parent().parent().find(".tax_ttdb").eq(0).val();
            if(tax_ttdb==''){
                tax_ttdb='0';
            }
        }
        else{
            tax_ttdb = '0';
            
        }
        
        tax_ttdb = parseInt(tax_ttdb);
        if($(this).parent().parent().find(".tax_nk").eq(0).length>0){
            tax_nk = $(this).parent().parent().find(".tax_nk").eq(0).val();
            if(tax_nk==''){
                tax_nk='0';
            }
        }
        else{
            tax_nk = '0';
            
        }
        
        
        tax_nk = parseInt(tax_nk);
        
        tax = $(this).parent().parent().find(".tax_vat").eq(0).val();
        tax = parseInt(tax);
        if ($(this).hasClass("price_has_tax")) {
            price = $(this).val();
            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }            
            temp = price / ((100 + tax) / 100);
            temp = temp / ((100 + tax_nk) / 100);
            temp = temp / ((100 + tax_ttdb) / 100);
            price_not_tax_double = temp.toFixed(2);
            $(this).parent().next().find("input").eq(0).val(temp);

            price = $(this).parent().next().find("input").eq(0).val();
            quantity = $(this).parent().parent().find("input[name='quantity[]']").eq(0).val();
        }
        else if ($(this).hasClass("price_not_tax")) {
            price = $(this).val();

            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            price_not_tax_double = price;
            price = price * ((100 + tax_ttdb) / 100);
            price = price * ((100 + tax_nk) / 100);
            price = price * ((100 + tax) / 100);



            $(this).parent().prev().find("input").eq(0).val(price);

            price = $(this).val();
            quantity = $(this).parent().parent().find("input[name='quantity[]']").eq(0).val();
        }
        else if ($(this).attr("name") == "quantity[]") {
            price = $(this).parent().parent().find(".price_has_tax").eq(0).val();
            if (price.indexOf(".") != -1) {
                price = price.split(".").join("");
            }
            temp = price / ((100 + tax) / 100);
            temp = temp / ((100 + tax_nk) / 100);
            temp = temp / ((100 + tax_ttdb) / 100);
            price_not_tax_double = temp.toFixed(2);
            quantity = $(this).val();
        }
        
        sum_node = $(this).parent().parent().find(".pro-qtemoney-th").eq(0).find("input").eq(0);
        

        if($(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_ttdb").eq(0).length>0){
            tax_ttdb_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_ttdb").eq(0);            
        }
        else{
            tax_ttdb_sum_node=null;
        }
        if($(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_nk").eq(0).length>0){
            tax_nk_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_nk").eq(0);            
        }
        else{
            tax_nk_sum_node=null;
        }
                  
        tax_sum_node = $(this).parent().parent().find(".pro-qtemoney-tt").eq(0).find("li.tax_vat").eq(0); 
        setTienHangAndTax(quantity, price_not_tax_double, tax_ttdb,tax_nk,tax, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node);

        if ($(this).hasClass("price_has_tax")) {
            price_has_tax_node = $(this);
            quantity_node = $(this).parent().prev().find("input").eq(0);
        }
        else if ($(this).hasClass("price_not_tax")) {
            price_has_tax_node = $(this).parent().prev().find("input").eq(0);
            quantity_node = $(this).parent().prev().prev().find("input").eq(0);
        }
        else if ($(this).attr("name") == "quantity[]") {
            price_has_tax_node = $(this).parent().next().find("input").eq(0);
            quantity_node = $(this);
        }

        <?php
        if(Yii::app()->session['calculate_way']=='1'){
            echo "editAutoTienHangAndTax(quantity_node, price_has_tax_node, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node, tax);";
        }
        ?>          
        setSumForHiddenInputs($(".div-pro1"),$('input[name="sum"]'),$('input[name="tax_sum"]'));
        setTong($(".div-pro1"),$("#sum_sum label"),$("#sum_sum_tax label"),$("#sum_sum_and_tax"));
        set_gia_tri_thanh_toan_qua_ngan_hang();
        init_sum=$('input[name="sum"]').val();
        init_tax_sum=$('input[name="tax_sum"]').val();



    });
    
});
</script>