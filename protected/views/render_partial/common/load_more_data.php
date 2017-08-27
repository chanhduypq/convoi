<div id="div_loading" style="display: none;position: absolute;z-index: 99999;">
    <img style="width: 100%;height: auto;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading_4.gif"/>
</div>
<script type="text/javascript">
    var page = 1;
    var page_count=<?php echo $page_count;?>;
    jQuery(function($) {
        h = $(window).height();
        while (h > $("#listing_container").height() + $("tfoot").find('tr').eq(0).height()+$("#header-wrapper").height()) {

            if (page<=page_count) {
                loadnewdata();
                page++;
            }
            else{
                break;
            }

        }
    });

    $(window).scroll(function()
    {
        if ($(window).scrollTop() <= $(document).height() - $(window).height())
        {
            if (page<=page_count) {
                loadnewdata();
                page++;
            }

        }
    });
    
    
    jQuery("document").ready(function($){
        $(window).scroll(function () {
            if ($(this).scrollTop() > 80) {
                $('.title-HD').addClass("fix");
            } else {
                $('.title-HD').removeClass("fix");
            }
            width = $(".row-HD").width();
            $('.title-HD').css('width', width + 'px');
            body_td_node_array=$(".row-HD").eq(0).find('td');
            header_td_node_array=$(".title-HD").eq(0).find('td');
            for(i=0;i<$(body_td_node_array).length;i++){
                width = $(body_td_node_array[i]).width()+1;
                padding=$(body_td_node_array[i]).css('padding-left');
                padding=padding.substr(0,padding.length-2);
                padding=parseInt(padding);
                width+=padding;
                padding=$(body_td_node_array[i]).css('padding-right');
                padding=padding.substr(0,padding.length-2);
                padding=parseInt(padding);
                width+=padding;
                $(header_td_node_array[i]).css('width', width + 'px');
                if($(header_td_node_array[i]).html()!="#"){
                    $(header_td_node_array[i]).css('padding-left', '0px');
                    $(header_td_node_array[i]).css('padding-right', '0px');
                }
                
            }
        });

    });


</script>