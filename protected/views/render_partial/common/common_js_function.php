<script type="text/javascript">
    var delay = (function(){
      var timer = 0;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();
    function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    function search(name) {
        $("#start_date_common").val($("#start_date").val());
        $("#end_date_common").val($("#end_date").val());
        if (name == 'multiselect_customer') {
            $("#customer_id_common").val($("#customer").val());
        }
        else if (name == 'multiselect_goods') {
            $("#goods_id_common").val($("#goods").val());
        }
        submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');
    }

    
    function setSameWidthForColumn(header_td_node_array,body_td_node_array,header_tr_node,footer_tr_node){
        width = $(header_tr_node).width();
        $(footer_tr_node).css('width', width + 'px');
        for(i=0;i<$(header_td_node_array).length;i++){
            width = $(header_td_node_array[i]).width()+1;
            padding=$(header_td_node_array[i]).css('padding-left');
            padding=padding.substr(0,padding.length-2);
            padding=parseInt(padding);
            width+=padding;
            padding=$(header_td_node_array[i]).css('padding-right');
            padding=padding.substr(0,padding.length-2);
            padding=parseInt(padding);
            width+=padding;
            $(body_td_node_array[i]).css('width', width + 'px');
            $(body_td_node_array[i]).css('padding-left', '0px');
            $(body_td_node_array[i]).css('padding-right', '0px');
        }
    }
    
    
    
    function loadnewdata()
    {
        
        $("div#div_loading").show();

        var url = '<?php echo $this->createUrl("/".Yii::app()->controller->id."/more"); ?>' + '/page/' + page;

        $.ajax({ 
            async: false,
            cache: false,
            url: url,           
            type:'POST',
            data:$("form#form_common").serialize(),
            success: function(data) {                
                $("div#div_loading").hide();
                $('#listing_container').append(data);
//                     $("#listing_container").animate({
//                        scrollTop: $("#listing_container").offset().top-$("#listing_container").height()
//                    });
                setSameWidthForColumn();

            },
            dataType: "html"
        });
        
    }
    function submit_form_common(action,action1){
        $("#div_loading_common").css({top:'50%',left:'50%',margin:'-'+($('#div_loading_common').height() / 2)+'px 0 0 -'+($('#div_loading_common').width() / 2)+'px'}).show();
        $.ajax({ 
            async: false,
            cache: false,
            url: action1,
            type:'POST',
            data:$("form#form_common").serialize(),
            success: function(data) {                
                window.location=action;
            }
        });
        
    }
    function sort(controller_name, field, session_key) {
        $("#field").attr("name", "field");
        $("#field").val(field);
        $("#session_key").attr("name", "session_key");
        $("#session_key").val(session_key);
        submit_form_common("<?php echo Yii::app()->request->baseUrl; ?>/" + controller_name + "/index");
    }
    /**
     * hiển thị một số có phân cách phần ngàn
     * ví dụ: 1000000 -> 1.000.000
     */
    function numberWithCommas(x) {
        var parts = x.toString().split(",");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return parts.join(",");
    }
    /**
     * 
     */
    function unique(array){
        return array.filter(function(el, index, arr) {
            return index === arr.indexOf(el);
        });
    }
</script>