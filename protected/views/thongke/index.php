<?php
FunctionCommon::echo_role_error();
?>
<form id="form_thongke" method="POST" action="<?php echo $this->createUrl('/thongke/index'); ?>">
<div class="statistic-select-month-year">
    <p>chọn thống kê theo tháng/ năm:</p>
    <select name="month" class="statistic-select">
        <?php
//        if($year<  date("Y")){
//            $month_to=12;
//        }
//        else{
//            $month_to=  date("m");
//        }
        $month_to=12;
        for($i=1;$i<=$month_to;$i++){
            if($i==$month){
                $selected=" selected='selected'";
            }
            else{
                $selected='';
            }
            echo "<option$selected value='$i'>$i</option>";
        }
        ?>
    </select>
    <select name="year" class="statistic-select">
        <?php        
        for($i=$from_year;$i<=date("Y");$i++){
            if($i==$year){
                $selected=" selected='selected'";
            }
            else{
                $selected='';
            }
            echo "<option$selected value='$i'>$i</option>";
        }
        ?>
    </select>
    
</div> <!-- lựa chọn ngày tháng năm -->
<div class="statistic-detail">
    <div class="statistic-total-sales">        
        <p><a style="color: #c0c3c5;" href="<?php echo $this->createUrl('invoicefull/index');?>">doanh thu kế toán</a></p>
        <p><?php echo number_format($doanh_thu,0,",",".");?></p>
        <img style="margin-top: 40px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/sale.png" width="60">
    </div>
    <div class="statistic-data1">
        <div class="statistic-content-data1">
            <div class="statistic-box">
                <p><a style="color: #c0c3c5;" href="<?php echo $this->createUrl('invoiceinputfull/index');?>">nhập kho</a></p>
                <p><?php echo number_format($nhap_kho,0,",",".");?></p>
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/nhapkho.png">
            </div>
            <div class="statistic-box">
                <p><a style="color: #c0c3c5;" href="<?php echo $this->createUrl('invoicechiphifull/index');?>">chi phí</a></p>
                <p><?php echo number_format($chiphi,0,",",".");?></p>
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/chiphi.png">
            </div>
            <div class="statistic-box">
                <p><a style="color: #c0c3c5;" href="<?php echo $this->createUrl('internationalinput/index');?>">tờ khai</a></p>
                <p><?php echo number_format($tokhai,0,",",".");?></p>
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/tokhai.png">
            </div>
        </div>
        <p class="statistic-total-data1"><?php echo number_format($nhap_kho_chiphi_tokhai,0,",",".");?></p>
    </div>
</div> <!-- nhập kho, chi phí, kê khai-->

<div class="statistic-data2">
    <p>số liệu kế toán gtgt</p>
    <ul class="statistic-tax">
        <li>
            <span class="tax1">Thuế GTGT nhập kho:</span>
            <p><span class="tax11"><?php echo number_format($thue_nhap_kho,0,",",".");?></span><span class="tax12"></span></p>
        </li>
        <li>
            <span class="tax2">Thuế GTGT xuất kho:</span>
            <p><span class="tax11"><?php echo number_format($thue_xuat_kho,0,",",".");?></span><span class="tax21"></span><span class="tax22"></span></p>
        </li>
        <li>
            <span class="tax3">Thuế phải đóng:</span>
            <p><span class="tax11"><?php echo number_format($thue_phai_dong,0,",",".");?></span><span class="tax31"></span><span class="tax32"></span></p>
        </li>
    </ul>
</div> <!-- số liệu kế toán giá trị gia tăng-->

<div class="statistic-data3">
    <p>thống kê mns</p>
    <div class="box-statistic-data3">
        <div class="content-box-statistic-data3">
            <p><a style="color: #c0c3c5;" href="<?php echo $this->createUrl('thuchi/index');?>">tài khoản tiền mặt</a></p>
            <p><?php echo number_format($tai_khoan_tien_mat,0,",",".");?></p>
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-tienmat.png" class="img-responsive">
        </div>
        <div class="content-box-statistic-data3">
            <p>doanh thu mns</p>
            <p><?php echo number_format($doanh_thu_ban_hang,0,",",".");?></p>
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-banhang.png" class="img-responsive">
        </div>
        <div class="content-box-statistic-data3">
            <p>lợi nhuận</p>
            <p><?php echo number_format($loi_nhuan,0,",",".");?></p>
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-loinhuan.png" class="img-responsive">
        </div>
    </div>
</div> <!-- thống kê mns-->

<div class="statistic-chart">
    <div class="select-chart-year">
        <p>Chọn thống kê biểu đồ theo năm:</p>
        <select name="only_year" class="statistic-select2">
            <?php        
            for($i=$from_year;$i<=date("Y");$i++){
                if($i==$only_year){
                    $selected=" selected='selected'";
                }
                else{
                    $selected='';
                }
                echo "<option$selected value='$i'>$i</option>";
            }
            ?>
        </select>
    </div>
    <div class="box-statistic-chart" id='content-chart'>
        <canvas id='canvas'></canvas>
    </div>
    <div class="note-chart">
        <p><span></span>Doanh thu</p>
        <p><span></span>Chi phí</p>
    </div>
</div> <!-- biểu đồ-->
</form>
<script type="text/javascript">
    jQuery(function ($){
       $("select").change(function (){
          $("#form_thongke").submit(); 
       }); 
    });
    var canvas = document.getElementById("canvas");
    var parent = document.getElementById("content-chart");
    canvas.width = parent.offsetWidth - 40;
    canvas.height = parent.offsetHeight - 40;

    var data1 = {
        labels: [<?php for($i=0;$i<count($doanh_thus);$i++) if($i<count($doanh_thus)-1) echo '"T'.($i+1).'",'; else echo '"T'.($i+1).'"';?>],
        datasets: [{
            fillColor: "rgba(0,172,172,0.2)",
            strokeColor: "rgba(0,172,172,1)",
            pointColor : "rgba(0,172,172,1)",
            pointStrokeColor : "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(0,172,172,1)",
            data: [<?php for($i=0;$i<count($doanh_thus);$i++) if($i<count($doanh_thus)-1) echo $doanh_thus[$i].','; else echo $doanh_thus[$i];?>]
        },
            {
                fillColor: "rgba(239,172,69,0.2)",
                strokeColor: "rgba(239,172,69,1)",
                pointColor : "rgba(239,172,69,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(239,172,69,1)",
                data : [<?php for($i=0;$i<count($chiphis);$i++) if($i<count($chiphis)-1) echo $chiphis[$i].','; else echo $chiphis[$i];?>]
            }]
    }

    var options1 = {
        scaleFontColor: "#000000",
        scaleLineColor: "#dcdcdc",
        scaleGridLineColor: "#dcdcdc",
        bezierCurve: false,
        scaleOverride: true,
        scaleSteps: 7,
        scaleStepWidth: <?php echo $scale_step_width;?>,
        scaleStartValue: 0,
        scaleLabel : "<%= numberWithCommas1(value)+' kk' %>",
        tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value %>",
        multiTooltipTemplate: "<%= numberWithCommas(value) %>"
    }

    new Chart(canvas.getContext("2d")).Line(data1, options1);
    function numberWithCommas1(value){
        value=value/1000000;
        value=numberWithCommas(value);
        return value;
    }
</script>