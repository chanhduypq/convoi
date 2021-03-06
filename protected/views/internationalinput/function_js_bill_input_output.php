<script type="text/javascript">    
    function set_gia_tri_thanh_toan_qua_ngan_hang(){
        sum_tax=$("#sum_sum_tax label").html();
        if(sum_tax==""){
            sum_tax="0";
        }
        if (sum_tax.indexOf(".") != -1) {
            sum_tax = sum_tax.split(".").join("");
        } 
        sum_tax = parseInt(sum_tax);

        gia_tri_hang_hoa_vnd=$("#gia_tri_hang_hoa_vnd").val();
        chi_phi_ngan_hang_vnd=$("#chi_phi_ngan_hang_vnd").val(); 
        if(gia_tri_hang_hoa_vnd==""){
            gia_tri_hang_hoa_vnd="0";
        }
        if(chi_phi_ngan_hang_vnd==""){
            chi_phi_ngan_hang_vnd="0";
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
    }
    /**
     * hủy một hàng đã chọn tại page create/update hóa đơn
     * @param {element} node
     * @returns {void}
     */
    function cancelGoods(node) {
        $(node).remove();
        showGoodsOrderLabel($(".div-margin li.prostt input.cus-auto18-input"));
        setSumForHiddenInputs($(".div-pro1"),$('input[name="sum"]'),$('input[name="tax_sum"]'));
        setTong($(".div-pro1"),$("#sum_sum label"),$("#sum_sum_tax label"),$("#sum_sum_and_tax"));
        set_gia_tri_thanh_toan_qua_ngan_hang();
    }    
    /**
     * giảm tiền thuế hoặc tăng tiền hàng lên sao cho tổng tiền thanh toán không còn bị lẻ vài đồng
     * @param {element} quantity_node
     * @param {element} price_has_tax_node
     * @param {element} sum_node
     * @param {element} tax_ttdb_sum_node
     * @param {element} tax_nk_sum_node
     * @param {element} tax_sum_node
     * @param {int} tax
     * @returns {void}
     */
    function editAutoTienHangAndTax(quantity_node, price_has_tax_node, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node, tax) {        
        quantity = $(quantity_node).val() + "";
        price_has_tax = $(price_has_tax_node).val() + "";
        sum = $(sum_node).val() + "";
        if(tax_ttdb_sum_node!=null){
            tax_ttdb_sum = $(tax_ttdb_sum_node).html() + "";
            if(tax_ttdb_sum==''){
                tax_ttdb_sum='0';
            }
        }
        else{
            tax_ttdb_sum = "0";
        }
        if(tax_nk_sum_node!=null){
            tax_nk_sum = $(tax_nk_sum_node).html() + "";
            if(tax_nk_sum==''){
                tax_nk_sum='0';
            }
        }
        else{
            tax_nk_sum = "0";
        }
        
        
        tax_sum = $(tax_sum_node).html() + "";
        /**
         * 
         */
        if (quantity.indexOf(".") != -1) {
            quantity = quantity.split(".").join("");
        }
        if (price_has_tax.indexOf(".") != -1) {
            price_has_tax = price_has_tax.split(".").join("");
        }
        if (sum.indexOf(".") != -1) {
            sum = sum.split(".").join("");
        }
        if (tax_ttdb_sum.indexOf(".") != -1) {
            tax_ttdb_sum = tax_ttdb_sum.split(".").join("");
        }
        if (tax_nk_sum.indexOf(".") != -1) {
            tax_nk_sum = tax_nk_sum.split(".").join("");
        }
        if (tax_sum.indexOf(".") != -1) {
            tax_sum = tax_sum.split(".").join("");
        }
        /**
         * 
         */
        quantity = parseInt(quantity);
        price_has_tax = parseInt(price_has_tax);

        price_not_tax = price_has_tax / ((tax + 100) / 100);
        price_not_tax = price_not_tax.toFixed(2);

        sum = parseInt(sum);
        tax_ttdb_sum = parseInt(tax_ttdb_sum);
        tax_nk_sum = parseInt(tax_nk_sum);
        tax_sum = parseInt(tax_sum);
        tax = parseInt(tax);
        hieu = Math.abs(quantity * price_has_tax - (sum + tax_ttdb_sum+tax_nk_sum+tax_sum));

        if (hieu == 0) {
            return;
        }

        if (quantity * price_has_tax > sum + tax_ttdb_sum+tax_nk_sum+tax_sum) {//tăng thuế lên
            tax_sum += hieu;
            tax_sum = numberWithCommas(tax_sum);
            $(tax_sum_node).html(tax_sum);
        }
        else {//giảm tiền hàng xuống
            sum -= hieu;
            $(sum_node).val(sum);
        }
    }
    /**
     * hiển thị thành tiền và thuế sau khi user nhập số lượng và giá bán     
     * @param {string} quantity
     * @param {float} price
     * @param {int} tax_ttdb
     * @param {int} tax_nk
     * @param {int} tax
     * @param {element} sum_node
     * @param {element} tax_ttdb_sum_node
     * @param {element} tax_nk_sum_node
     * @param {element} tax_sum_node
     * @returns {void}
     */
    function setTienHangAndTax(quantity, price_not_tax, tax_ttdb,tax_nk,tax, sum_node, tax_ttdb_sum_node,tax_nk_sum_node,tax_sum_node) {
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
        temp = sum * tax_ttdb / 100;
        temp_toFixed=temp.toFixed(0);
        if(tax_ttdb_sum_node!=null){
            $(tax_ttdb_sum_node).html(numberWithCommas(temp_toFixed));
        }        
        temp1 = (sum + temp)* tax_nk / 100;
        temp1_toFixed=temp1.toFixed(0);
        if(tax_nk_sum_node!=null){
            $(tax_nk_sum_node).html(numberWithCommas(temp1_toFixed));
        }        
        temp2= (sum + temp+temp1)* tax / 100;
        temp2_toFixed=temp2.toFixed(0);
        $(tax_sum_node).html(numberWithCommas(temp2_toFixed));

        
        sum = sum.toFixed(0);        
        $(sum_node).val(sum);
        


    }    
    /**
     * hiển thị thứ tự tiếp theo khi thêm/xóa một hàng hóa
     * tên hàng 01, 02, 03,....     
     * @param {array} node_array
     * @returns {void}
     */
    function showGoodsOrderLabel(node_array) {    
        for (i = 0; i < node_array.length; i++) {
            $(node_array[i]).val(i+1);
        }
    }
    /**
     * set value cho tổng tiền, tổng tiền thuế cho các input hidden để submit và lưu vào database
     * @param {array} div_node_array
     * @param {element} sum_node
     * @param {element} tax_sum_node
     * @returns {void}
     */
    function setSumForHiddenInputs(div_node_array,sum_node,tax_sum_node){
        sum = 0;
        sum_tax = 0; 
        //
        for (i = 0; i < div_node_array.length - 1; i++) {
            temp1 = $(div_node_array[i]).find(".pro-qtemoney-th").eq(0).find("input").eq(0).val();
            if($(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_ttdb').eq(0).length>0){
                temp2 = $(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_ttdb').eq(0).html();
                if(temp2==''){
                    temp2='0';
                }
            }
            else{
                temp2='0';
            }
            if($(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_nk').eq(0).length>0){
                temp3 = $(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_nk').eq(0).html();
                if(temp3==''){
                    temp3='0';
                }
            }
            else{
                temp3='0';
            }            
            temp4 = $(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_vat').eq(0).html();
            /**
             * bỏ dấu phẩy và dấu chấm trong chuỗi số để cộng trừ nhân chia với số
             */
            if (temp1.indexOf(".") != -1) {
                temp1 = temp1.split(".").join("");
            }
            if (temp2.indexOf(".") != -1) {
                temp2 = temp2.split(".").join("");
            }
            if (temp3.indexOf(".") != -1) {
                temp3 = temp3.split(".").join("");
            }
            if (temp4.indexOf(".") != -1) {
                temp4 = temp4.split(".").join("");
            }
            //
            sum += parseInt(temp1);
            sum_tax += parseInt(temp2);
            sum_tax += parseInt(temp3);
            sum_tax += parseInt(temp4);
        }
        sum = parseInt(sum);
        sum_tax = parseInt(sum_tax);
        $(sum_node).val(sum);
        $(tax_sum_node).val(sum_tax);        
    }
    /**
     * hiển thị tổng thành tiền và thuế phía dưới  
     * @param {array} div_node_array
     * @param {element} sum_node
     * @param {element} tax_sum_node
     * @param {element} sum_and_tax_sum_node
     * @returns {void}
     */
    function setTong(div_node_array,sum_node,tax_sum_node,sum_and_tax_sum_node) {
        sum = 0;
        sum_tax = 0; 
        //
        for (i = 0; i < div_node_array.length - 1; i++) {
            temp1 = $(div_node_array[i]).find(".pro-qtemoney-th").eq(0).find("input").eq(0).val();
            if(temp1==''){
                temp1='0';
            }
            if($(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_ttdb').eq(0).length>0&&$(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_ttdb').eq(0).is(":visible")){
                temp2 = $(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_ttdb').eq(0).html();
                if(temp2==''){
                    temp2='0';
                }
            }
            else{
                temp2='0';
            }
            if($(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_nk').eq(0).length>0&&$(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_nk').eq(0).is(":visible")){
                temp3 = $(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_nk').eq(0).html();
                if(temp3==''){
                    temp3='0';
                }
            }
            else{
                temp3='0';
            }            
            temp4 = $(div_node_array[i]).find(".pro-qtemoney-tt").eq(0).find('li.tax_vat').eq(0).html();
            /**
             * bỏ dấu phẩy và dấu chấm trong chuỗi số để cộng trừ nhân chia với số
             */
            if (temp1.indexOf(".") != -1) {
                temp1 = temp1.split(".").join("");
            }
            if (temp2.indexOf(".") != -1) {
                temp2 = temp2.split(".").join("");
            }
            if (temp3.indexOf(".") != -1) {
                temp3 = temp3.split(".").join("");
            }
            if (temp4.indexOf(".") != -1) {
                temp4 = temp4.split(".").join("");
            }
            //
            sum += parseInt(temp1);
            sum_tax += parseInt(temp2);
            sum_tax += parseInt(temp3);
            sum_tax += parseInt(temp4);
        }
        sum = parseInt(sum);
        sum_tax = parseInt(sum_tax);
        sum_sum_and_tax = sum + sum_tax;
        sum_sum_and_tax = parseInt(sum_sum_and_tax);
        //
        sum = numberWithCommas(sum);
        $(sum_node).html(sum);
        //
        $("#tien_thue_vnd").val(sum_tax);
        sum_tax = numberWithCommas(sum_tax);
        $(tax_sum_node).html(sum_tax);
        //
        sum_sum_and_tax = numberWithCommas(sum_sum_and_tax);
        $(sum_and_tax_sum_node).html(sum_sum_and_tax);
    }
    /**
     * user chọn một hàng hóa khác từ combobox hàng hóa, reset value đã input trước đó
     * giá, số lượng,...trở về số 0 để user input lại     
     * @param {element} quantity_node
     * @param {element} price_has_tax_node
     * @param {element} price_not_tax_node
     * @param {element} sum_node
     * @param {element} sum_tax_node
     * @param {Boolean} disable_input
     * @returns {void}
     */
    function resetInput(quantity_node, price_has_tax_node,price_not_tax_node, sum_node, sum_tax_ttdb_node,sum_tax_nk_node,sum_tax_node, disable_input) {
        /**
         *          
         */
        $(quantity_node).val('0');
        $(price_has_tax_node).val('0');
        $(price_not_tax_node).val('0');
        $(sum_node).html('0');
        $(sum_tax_ttdb_node).html('0');
        $(sum_tax_nk_node).html('0');
        $(sum_tax_node).html('0');
        /**
         *          
         */
        if (disable_input == true) {
            $(quantity_node).removeAttr("disabled");
            $(price_has_tax_node).removeAttr("disabled");
            $(price_not_tax_node).removeAttr("disabled");
        }
        else {
            $(quantity_node).attr("disabled", "disabled");
            $(price_has_tax_node).attr("disabled", "disabled");
            $(price_not_tax_node).attr("disabled", "disabled");
        }

    }
    /**
     * reset combobox đơn vị khi user chọn một hàng hóa khác tại combobox hàng hóa
     * @param {int} goods_group_id
     * @param {element} unit_node
     * @param {element} tax_ttdb_node
     * @param {element} tax_nk_node
     * @param {element} tax_node
     * @returns {void}
     */
    function setGoodCombobox(goods_group_id, unit_node, tax_ttdb_node,tax_nk_node,tax_node,s) {//ok
        $(unit_node).html('');
        $(tax_ttdb_node).show();
        $(tax_nk_node).show();
        $(tax_ttdb_node).parent().prev().show();
        $(tax_nk_node).parent().prev().show();
        
        $(tax_ttdb_node).val('0');
        $(tax_nk_node).val('0');
        $(tax_ttdb_node).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li").show();
        
        //
        $.ajax({ 
            async: false,
            cache: false,       
            url: '<?php echo $this->createUrl("/ajax/getunits"); ?>/goods_group_id/' + goods_group_id,
            success: function(data, textStatus, jqXHR) {
                if($.trim(data)!=''){
                    data = $.parseJSON(data);
                    for (i = 0; i < data.length; i++) {
                        if (i == 0) {
                            flag=false;
                            
                            if(data[i].thue_tieu_thu_dac_biet=='0'){
                                flag=true;
                                
                                $(tax_ttdb_node).hide();
                                $(tax_ttdb_node).parent().prev().hide();
                                
                                $(tax_ttdb_node).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li.li-left").eq(0).hide();
                                $(tax_ttdb_node).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li.li-right").eq(0).hide();
                            }
                            else{
                                $(tax_ttdb_node).val(data[i].thue_tieu_thu_dac_biet);
                            }                            
                            if(data[i].thue_nhap_khau=='0'){
                                $(tax_nk_node).hide();
                                $(tax_nk_node).parent().prev().hide();
                                if(flag==false){
                                    index=1;
                                }
                                else{
                                    index=0;
                                }
                                
                                $(tax_nk_node).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li.li-left").eq(1).hide();
                                $(tax_nk_node).parent().parent().parent().parent().find("td.pro-qtemoney-tt").eq(0).find("li.li-right").eq(1).hide();
                            }
                            else{
                                $(tax_nk_node).val(data[i].thue_nhap_khau);
                            }                           
                            
                            $(tax_node).val(data[i].tax);
                        }
                        option = "<option value='" + data[i].id + "'>" + data[i].unit_full_name + "</option>";

                        $(option).appendTo($(unit_node));

                    }
                }
            }

        });

    }

    /**
     * sau khi user input mã số thuế, các thông tin khác về khách hàng se hiển thị đúng theo mã số thuế đó
     * @param {String} tax_code
     * @param {element} branch_full_name_node
     * @param {element} branch_address_node
     * @param {element} branch_id_node
     * @returns {void}     
     */
    function setFullName(tax_code,branch_full_name_node,branch_address_node,branch_id_node) {
        $.ajax({ 
            async: false,
            cache: false,
            url: '<?php echo $this->createUrl("/ajax/getbranchinfo")."/tax_code/";?>' + tax_code,
            success: function(data, textStatus, jqXHR) {
                if($.trim(data)!=''){
                    data = $.parseJSON(data);
                    $(branch_full_name_node).val(data.full_name);
                    $(branch_address_node).val(data.address);
                    $(branch_id_node).val(data.id);
                    global_tax_code=tax_code;
                }
            }

        });
    }
    /**
     * sau khi user input tên công ty, các thông tin khác về khách hàng se hiển thị đúng theo tên công ty đó
     * @param {String} branch_full_name
     * @param {element} tax_code_node
     * @param {element} branch_address_node
     * @param {element} branch_id_node
     * @returns {void}     
     */
    function setTaxCode(branch_full_name,tax_code_node,branch_address_node,branch_id_node) {
        $.ajax({ 
            async: false,
            cache: false,
            type: "POST",
            data: {full_name: branch_full_name},
            url: '<?php echo $this->createUrl("/ajax/getbranchinfo")."/tax_code/";?>',
            success: function(data, textStatus, jqXHR) {
                if($.trim(data)!=''){
                    data = $.parseJSON(data);
                    $(tax_code_node).val(data.tax_code);
                    $(branch_address_node).val(data.address);
                    $(branch_id_node).val(data.id);
                    global_tax_code=data.tax_code;
                }
            }

        });
    }
    
    function exist_bill_input_number(bill_number,bill_id){
        $("#div_loading_validate").show();  
        result_bool=true;
        $.ajax({ 
            async: false,
            cache: false,
            type: "POST",
             
            data: {bill_number: bill_number,id:bill_id,is_international:1},
            url: '<?php echo $this->createUrl("/ajax/checkbillinputnumberexist"); ?>',
            success: function(data, textStatus, jqXHR) {                  
                $("#div_loading_validate").hide();                   
                if($.trim(data)==""||$.trim(data)=="[]"){                    
                    result_bool= false;
                }                
            }

        });
        return result_bool;
    }
</script>