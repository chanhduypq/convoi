<?php 
$this->renderPartial('//layouts/top/function_php');
$DATE_FORMAT = Yii::app()->session['date_format'];
if ($DATE_FORMAT == 'Y.m.d') {
    $start_date = date("Y.m.01");
    $end_date = date("Y.m.d");
    $DATE_FORMAT = "yy.mm.dd";
} elseif ($DATE_FORMAT == 'Y-m-d') {
    $start_date = date("Y-m-01");
    $end_date = date("Y-m-d");
    $DATE_FORMAT = "yy-mm-dd";
} elseif ($DATE_FORMAT == 'Y/m/d') {
    $start_date = date("Y/m/01");
    $end_date = date("Y/m/d");
    $DATE_FORMAT = "yy/mm/dd";
} elseif ($DATE_FORMAT == 'Ymd') {
    $start_date = date("Ym01");
    $end_date = date("Ymd");
    $DATE_FORMAT = "yymmdd";
}
$class_attr_for_addnew_div_element=get_class_attr_for_addnew_div_element($this,Yii::app()->controller->id);
$title_attr_for_create_icon=get_title_attr_for_create_icon(Yii::app()->controller->id);
$label_for_branch_combobox=  get_label_for_branch_combobox(Yii::app()->controller->id);
?>
<div id="header-wrapper">

    <div id="logo">
        <a href>
            <img style="cursor: pointer;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.png" width="70%" height="auto">
        </a>
    </div>
<?php
if (Yii::app()->controller->action->id == "index") {   
    echo_js_css_for_datetimepicker_multiselect(Yii::app()->theme->baseUrl);
    if (in_array(Yii::app()->controller->id, Yii::app()->params['controller_list_for_search'])){
        if(Yii::app()->controller->id=='goodsleftfull'){
            echo_search_not_date(Yii::app()->session['goodsleft_equal_0'],Yii::app()->controller->id,$label_for_branch_combobox);
        }
        else{
            echo_search($start_date,$end_date,Yii::app()->session['all_time_common'],Yii::app()->controller->id,$label_for_branch_combobox);
        }
        
    }
    else if(Yii::app()->controller->id=='thongke'){?>
        <div id="search">
            <ul class="statistic-top-menu">
                <li>doanh thu<span><a href="#"><i class="fa fa-chevron-up"></i></a>15%</span></li>
                <li>chi phí<span class="statistic-decrease"><a href="#"><i class="fa fa-chevron-down"></i></a>8%</span></li>
                <li>lợi nhuận<span><a href="#"><i class="fa fa-chevron-up"></i></a>6%</span></li>
            </ul>
        </div>
    <?php    
    }
    ?>
    <div id="add-new" class="<?php echo $class_attr_for_addnew_div_element;?>">
        <?php 
        echo_icon_user(Yii::app()->theme->baseUrl);
        ?>
        <li class="but-add"<?php if (Yii::app()->controller->id == "goodsinputfull" || Yii::app()->controller->id == "goodsfull" || Yii::app()->controller->id == "goodsleftfull") echo ' id="add_goods"';?>>
        <?php
        echo echo_icon_create(Yii::app()->theme->baseUrl, Yii::app()->controller->id, $this,$title_attr_for_create_icon);            
        ?>
        </li>
    </div>
<?php
}
else { 
    if (Yii::app()->controller->id == "invoicefull") {?>
        <div id="mau-HDtop">
            <?php
            echo_icon_user(Yii::app()->theme->baseUrl);
            ?>
        </div>
    <?php    
    }
    else{
    ?>
    <div id="add-new" class="<?php echo $class_attr_for_addnew_div_element;?>">
        <?php 
        echo_icon_user(Yii::app()->theme->baseUrl);
        ?>
        <li class="but-add">&nbsp;        
        </li>
    </div>

<?php
    }
}
?>
</div>
<div class="clearfix"></div>


<script type="text/javascript">
    date_format='<?php echo $DATE_FORMAT; ?>';
    jQuery(function($) {
        $("li#edit_profile").click(function() {
            window.location='<?php echo $this->createUrl('/user/editprofile');?>/id/<?php echo Yii::app()->session['user_id'];?>';
        });
        jQuery("li#setting").click(function(){
            jQuery(this).next("div").slideToggle();
        });        
        
        $("#logout").click(function() {
            window.location = "<?php echo $this->createUrl('/index/logout');?>";
        });
        $("#expand_for_user").click(function() {
            if ($("#admin-setting").is(":visible")) {
                $("#setting").next().hide(500);
                $("#admin-setting").hide(500);
                if($("div.back_button").length>0){
                    $("div.back_button").show();
                }
                
            }
            else {                
                $("#admin-setting").show(500);
                $("div.back_button").hide();
            }
        });
        $(document).click(function(event) {
            if ($(event.target).closest('#expand_for_user').get(0) == null&&$(event.target).closest('li#setting').get(0) == null&&$(event.target).closest('li#danh_xung_full_name').get(0) == null) {
                $("#setting").next().hide(500);
                $("#admin-setting").hide(500);
                if($("div.back_button").length>0){
                    $("div.back_button").show();
                }
            }
        });
        /**
         * 
         * ngày bắt đầu và ngày kết thúc để search thi không cho phép user nhập
         * chỉ được chọn bằng datepicker
         * do đó, khi user gõ backspace thi chặn trình duyệt quay về trang trước tại 2 textbox ngày bắt đầu và ngày kết thúc
         */
        $(document).keydown(function(event) {
            if ($(event.target).closest('#start_date').get(0) != null||$(event.target).closest('#end_date').get(0) != null||$(event.target).closest('#birthday').get(0) != null||$(event.target).closest('#created_at').get(0) != null) {
                if(event.keyCode == 8){                    
                    event.preventDefault();
                    return false;
                }
            }
        });   
<?php
if (Yii::app()->controller->action->id == "index") {
?>     
        if($("#end_date_common").length>0){
            max_date_start=$("#end_date_common").val();
        } 
        else{
            max_date_start=$(".date input#end_date").val();
        }
        if($("#start_date_common").length>0){
            min_date_end=$("#start_date_common").val();
        } 
        else{
            min_date_end=$(".date input#start_date").val();
        }
        $('.date input').attr('style', '');
        $(".date input#start_date").datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: max_date_start,
            onClose: function() {
                if ($(this).val() != $("#start_date_common").val()) {
                    search("");
                }
            }
        });
        $(".date input#end_date").datepicker({
            dateFormat: '<?php echo $DATE_FORMAT; ?>',
            maxDate: 0,
            minDate: min_date_end,
            onClose: function() {
                value=$(this).val();
                if ($(this).val() != $("#end_date_common").val()) {
                    search("");
                }
            }
        });

        $.datepicker.setDefaults($.datepicker.regional[ "vi" ]);

        $("select#customer").multiselect({
            show: {effect: "slide", duration: 500},
            hide: {effect: "slide", duration: 500},
            noneSelectedText: "<?php echo $label_for_branch_combobox;?>",
            selectedText: "# <?php echo $label_for_branch_combobox;?> được chọn"
        }).multiselectfilter();

        $("select#goods").multiselect({
            show: {effect: "slide", duration: 500},
            hide: {effect: "slide", duration: 500},
            //                show: ["bounce", 200],
            //                hide: ["explode", 1000],
            noneSelectedText: "Hàng hóa",
            selectedText: "# Hàng hóa được chọn"
        }).multiselectfilter();
        
        $("#pre_month").click(function (){
            if($("#start_date").length==0){
                return;
            }
            date_val=$("#start_date").val();
            if(date_format=="yymmdd"){
                year=date_val.substr(0,4);
                month=date_val.substr(4,2);                
            }
            else{
                year=date_val.substr(0,4);
                month=date_val.substr(5,2);      
            }
            if(month=='01'){
                month='12';
                year--;
            }
            else{
                month--;
                if(month<10){
                    month="0"+month;
                }
            }
            if(month=='01'||month=='03'||month=='05'||month=='07'||month=='08'||month=='10'||month=='12'){
                day='31';
            }
            else if(month=='04'||month=='06'||month=='09'||month=='11'){
                day='30';
            }
            else{
                if(year%4==0){
                    day='29';
                }
                else{
                    day='28';
                }
            }
            if(date_format=="yymmdd"){
                start_date=year+month+"01";
                end_date=year+month+day;
            }
            else if(date_format=="yy.mm.dd"){
                start_date=year+"."+month+".01";
                end_date=year+"."+month+"."+day;
            }
            else if(date_format=="yy-mm-dd"){
                start_date=year+"-"+month+"-01";
                end_date=year+"-"+month+"-"+day;
            }
            else if(date_format=="yy/mm/dd"){
                start_date=year+"/"+month+"/01";
                end_date=year+"/"+month+"/"+day;
            }
            
            $("#start_date_common").val(start_date);
            $("#end_date_common").val(end_date);
            submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');

        });
        $("#next_month").click(function (){
            if($("#end_date").length==0){
                return;
            }
            date_val=$("#end_date").val();
            if(date_format=="yymmdd"){
                year=date_val.substr(0,4);
                month=date_val.substr(4,2);                
            }
            else{
                year=date_val.substr(0,4);
                month=date_val.substr(5,2);      
            }
            if(month=='12'){
                month='01';
                year++;
            }
            else{
                month++;
                if(month<10){
                    month="0"+month;
                }
            }
            if(month=='01'||month=='03'||month=='05'||month=='07'||month=='08'||month=='10'||month=='12'){
                day='31';
            }
            else if(month=='04'||month=='06'||month=='09'||month=='11'){
                day='30';
            }
            else{
                if(year%4==0){
                    day='29';
                }
                else{
                    day='28';
                }
            }
            if(month=='<?php echo date('m');?>'&&year=='<?php echo date('Y');?>'){
                day='<?php echo date('d');?>';
            }
            if(date_format=="yymmdd"){
                start_date=year+month+"01";
                end_date=year+month+day;
            }
            else if(date_format=="yy.mm.dd"){
                start_date=year+"."+month+".01";
                end_date=year+"."+month+"."+day;
            }
            else if(date_format=="yy-mm-dd"){
                start_date=year+"-"+month+"-01";
                end_date=year+"-"+month+"-"+day;
            }
            else if(date_format=="yy/mm/dd"){
                start_date=year+"/"+month+"/01";
                end_date=year+"/"+month+"/"+day;
            }
            
            $("#start_date_common").val(start_date);
            $("#end_date_common").val(end_date);
            submit_form_common('<?php echo $this->createUrl("/".Yii::app()->controller->id."/index"); ?>','<?php echo $this->createUrl("/ajax/search"); ?>');

        });

        $("button[type='button']").width('160');
        $("div.ui-multiselect-menu.ui-widget.ui-widget-content.ui-corner-all").width('400');
<?php
}
?>
    });

</script>





