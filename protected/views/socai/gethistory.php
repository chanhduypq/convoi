<table class="title-HD1 sort">
    <tbody>
        <tr class="title-HD">
            <td class="title-HDli title-HDliw20">
                Người thực hiện
            </td>
            <td class="title-HDli title-HDliw10">
                Ngày
            </td>
            <td class="title-HDli title-HDliw40">
                Nội dung
            </td>
            <td class="title-HDli title-HDliw10">
                Thu
            </td>
            <td class="title-HDli title-HDliw10">
                Chi
            </td>
            <td class="title-HDli title-HDliw10">
                Số tiền
            </td>
            
        </tr>
        <?php
        if(count($items)>0){
            foreach ($items as $item) {?>
        <tr class="row-HD">
            <td class="row0-KHli row0-HDliw20">
                <?php echo $item->nguoi_thuc_hien."<br>".$item->log_date;?>
            </td>
            <td class="row0-KHli row0-HDliw10">
                <?php echo $item->created_at;?>
            </td>
            <td class="row0-KHli row0-HDliw40">
                <?php echo nl2br($item->content);?>
            </td>
            <td class="row0-KHli row0-HDliw10">
                &nbsp;<?php echo $item->thu;?>
            </td>
            <td class="row0-KHli row0-HDliw10">
                &nbsp;<?php echo $item->chi;?>
            </td>
            <td class="row0-KHli row0-HDliw10">
                &nbsp;<?php echo $item->tm;?>
            </td>
            
        </tr>
        <?php        
            }
        }
        ?>
    </tbody>
</table>