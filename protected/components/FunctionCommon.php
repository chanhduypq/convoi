<?php

class FunctionCommon{
    /**
     * in hoa chuỗi, cắt những khoảng trắng bị dư, mã hóa chuỗi thành utf8
     * ví dụ: " tôi tên   là  nguyễn vĂn  Hùng"->"T%C3%B4I+T%C3%AAN+L%C3%A0+NGUY%E1%BB%85N+V%C4%83N+H%C3%B9NG"
     * @param string $string
     * @return string
     */
    public static function getStringForValidate($string) {
        $string = trim($string);        
        $words = preg_split('/\s+/', $string);
        $string = implode(" ", $words);
        $string = strtoupper($string);
        return urlencode($string);
    }
    /**
     * 
     * @param string $old_session_sort
     * @param string $field
     * @param string $session_key
     * @return void
     */
    public static function setSesstionForSort($old_session_sort, $field, $session_key) { 
        /**
         * kiểm tra value của $field bao gồm 1 field hay 2 field trong database
         */
        if (strpos($field, "__") === FALSE) {//1 field
            /**
             * kiểm tra trạng thái sắp xếp kế trước là chính nó hay là field khác
             * ví dụ: mới sắp xếp theo ngày tháng, rồi user tiếp tục muốn sắp xếp theo ngày tháng hay theo tên
             *        nếu là ngày tháng thi chuyển trạng thái tăng dần sang giảm dần hoặc ngược lại
             *        nếu không thi sắp xếp theo tên và mặc định là giảm dần trước
             */
            if (strpos($old_session_sort, $field) === FALSE) {//chuyển qua sắp xếp theo field khác
                Yii::app()->session[$session_key] = $field . ' DESC';
            } 
            else {//cũng sắp xếp theo field đó nhưng đổi trạng thái tăng dần hoặc giảm dần
                if (strpos($old_session_sort, "ASC") === FALSE) {
                    Yii::app()->session[$session_key] = $field . ' ASC';
                } else {
                    Yii::app()->session[$session_key] = $field . ' DESC';
                }
            }
        } 
        else {//2 field
            $temp = explode("__", $field);
            /**
             * kiểm tra trạng thái sắp xếp kế trước là chính nó hay là field khác
             * ví dụ: mới sắp xếp theo ngày tháng, rồi user tiếp tục muốn sắp xếp theo ngày tháng hay theo tên
             *        nếu là ngày tháng thi chuyển trạng thái tăng dần sang giảm dần hoặc ngược lại
             *        nếu không thi sắp xếp theo tên và mặc định là giảm dần trước
             * ở đây logic giống như trên nhưng chỉ khác là chỉ cần dùng $temp[0] để kiểm tra thay vì phía trên là dùng $field
             * vi ở đây là trường hợp sắp xếp theo 2 field
             */
            if (strpos($old_session_sort, $temp[0]) === FALSE) {//chuyển qua sắp xếp theo field khác
                $new_session_sort = $temp[0] . ' DESC';                                
                for ($i = 1; $i < count($temp); $i++) {
                    $new_session_sort.= "," . $temp[$i] . ' DESC';                    
                }
                Yii::app()->session[$session_key] = $new_session_sort;
            } 
            else {//cũng sắp xếp theo field đó nhưng đổi trạng thái tăng dần hoặc giảm dần
                if (strpos($old_session_sort, "ASC") === FALSE) {
                    Yii::app()->session[$session_key] = str_replace("DESC", "ASC", $old_session_sort);
                } 
                else {
                    Yii::app()->session[$session_key] = str_replace("ASC", "DESC", $old_session_sort);
                }
            }
        }
    }
    /**
     * get date với format trong database để đưa vào các câu query
     * 
     * @param string $date
     * @return string
     */
    public static function convertDateForDB($date) {
        if($date==NULL||!is_string($date)||trim($date)==''){
            return '';
        }
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') {
            return str_replace(".", "-", $date);
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            return $date;
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            return str_replace("/", "-", $date);
        } elseif ($DATE_FORMAT == 'Ymd') {
            return substr($date, 0,4)."-".substr($date, 4,2)."-".substr($date, 6,2);            
        } 
    }
    /**
     * get date format để đưa vào các câu query kiểu như thế này
     * ....date_format(bill_history.updated_at,'$DATE_FORMAT - %H:%i:%s') AS updated_at_date....
     * @return string
     */
    public static function convertDateForDBSelect() {
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') {
            $DATE_FORMAT = "%Y.%m.%d";
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            $DATE_FORMAT = "%Y-%m-%d";
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $DATE_FORMAT = "%Y/%m/%d";
        } elseif ($DATE_FORMAT == 'Ymd') {
            $DATE_FORMAT = "%Y%m%d";
        } 
        return $DATE_FORMAT;
    }
    /**
     * get date format để đưa vào rules của CActiveRecord
     * kiểu như thế này:
     *         array('start_date', 'type', 'type' => 'date', 'message' => '{attribute}: không đúng định dạng ngày tháng!', 'dateFormat' => 'yyyy/MM/dd'),
     * @return string
     */
    public static function convertDateForValidation() {
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') {
            $DATE_FORMAT = "yyyy.MM.dd";
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            $DATE_FORMAT = "yyyy-MM-dd";
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $DATE_FORMAT = "yyyy/MM/dd";
        } elseif ($DATE_FORMAT == 'Ymd') {
            $DATE_FORMAT = "yyyyMMdd";
        } 
        return $DATE_FORMAT;
    }
    public static function get_last_time_of_current_month(){
        $max_date=date("Y-m-d");
        return $max_date." 23:59:59";
    }
    /**
     * get ngày cuối cùng của tháng hiện tại
     * @return string
     */
    public static function get_last_date_of_current_month(){
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') { 
            if(date("m")=='01'||date("m")=='03'||date("m")=='05'||date("m")=='07'||date("m")=='08'||date("m")=='10'||date("m")=='12'){
                $max_date=date("Y.m.31");
            }
            else if(date("m")=='04'||date("m")=='06'||date("m")=='09'||date("m")=='11'){
                $max_date=date("Y.m.30");
            }
            else {
                if(date("Y")%4==0){
                    $max_date=date("Y.m.29");
                }
                else{
                    $max_date=date("Y.m.28");
                }
            }
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            if(date("m")=='01'||date("m")=='03'||date("m")=='05'||date("m")=='07'||date("m")=='08'||date("m")=='10'||date("m")=='12'){
                $max_date=date("Y-m-31");
            }
            else if(date("m")=='04'||date("m")=='06'||date("m")=='09'||date("m")=='11'){
                $max_date=date("Y-m-30");
            }
            else {
                if(date("Y")%4==0){
                    $max_date=date("Y-m-29");
                }
                else{
                    $max_date=date("Y-m-28");
                }
            }
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            if(date("m")=='01'||date("m")=='03'||date("m")=='05'||date("m")=='07'||date("m")=='08'||date("m")=='10'||date("m")=='12'){
                $max_date=date("Y/m/31");
            }
            else if(date("m")=='04'||date("m")=='06'||date("m")=='09'||date("m")=='11'){
                $max_date=date("Y/m/30");
            }
            else {
                if(date("Y")%4==0){
                    $max_date=date("Y/m/29");
                }
                else{
                    $max_date=date("Y/m/28");
                }
            }
        } elseif ($DATE_FORMAT == 'Ymd') {
            if(date("m")=='01'||date("m")=='03'||date("m")=='05'||date("m")=='07'||date("m")=='08'||date("m")=='10'||date("m")=='12'){
                $max_date=date("Ym31");
            }
            else if(date("m")=='04'||date("m")=='06'||date("m")=='09'||date("m")=='11'){
                $max_date=date("Ym30");
            }
            else {
                if(date("Y")%4==0){
                    $max_date=date("Ym29");
                }
                else{
                    $max_date=date("Ym28");
                }
            }
        }
        return $max_date;
    }

    /**
     * cắt chuỗi nếu chuỗi dài hơn một độ dài cho phép
     * @param string $text 
     * @param integer $len
     * @return string
     * @author Trần Công Tuệ     
     */
    public static function crop($text, $len, $after) {
        if ($text == NULL) {
            return "";
        }
        if (!is_string($text)) {
            return "";
        }
        if (trim($text) == "") {
            return "";
        }
        //
        if ($len > strlen(utf8_decode($text))) {
            $string = $text;
        } else {
            if ($after == true) {
                $string_cop = mb_substr($text, 0, $len, 'UTF-8');
                if(strlen(utf8_decode($text))==strlen(utf8_decode($string_cop))){
                    $string = $string_cop ;
                }
                else{
                    $string = $string_cop . "...";
                }
                
            } else {                
                $string_cop = mb_substr($text, (-1) * ($len), $len, 'UTF-8');
                if(strlen(utf8_decode($text))==strlen(utf8_decode($string_cop))){
                    $string = $string_cop ;
                }
                else{
                    $string = "..." . $string_cop;
                }
                
            }
        }
        return $string;
    }
    /**
     * kiểm tra user có quyền QUẢN TRỊ USER không
     * @return bool
     */
    public static function allow_manage_user() {
        return Role::ADMIN==self::get_role();
    }
    /**
     * kiểm tra user có quyền QUẢN TRỊ HỆ THỐNG không
     * @return bool
     */
    public static function allow_manage_system() {
        return Role::ADMIN==self::get_role();
    }    
    /**
     * nếu user không có quyền access page nào đó thi redirect về page default
     * @param Controller $controller
     * @param string $controller_name
     * @param string $default_url
     * @return void
     */
    public static function redirect_invoicefull_if_not_allow($controller,$controller_name,$default_url,$action_name=null) {
        if ($controller_name == "user"&&!FunctionCommon::allow_manage_user()&&$action_name!='editprofile') {
            Yii::app()->session['error_role']='Bạn không có quyền quản lý user.';
            $controller->redirect($default_url);
        }
        else if ($controller_name == "system"&&!FunctionCommon::allow_manage_system()) {
            Yii::app()->session['error_role']='Bạn không có quyền quản lý hệ thống.';
            $controller->redirect($default_url);
        }
        else if($controller_name=='invoiceinputfull'){
            if($action_name=='create'||$action_name=='update'){
                if(self::get_role()!=Role::QUAN_LY_KHO_HANG&&self::get_role()!=Role::ADMIN){
                    Yii::app()->session['error_role']='Bạn không có quyền '.(($action_name=='create')?'tạo mới':'chỉnh sửa').' [nhập kho và chi phí]--->[nhập kho kinh doanh]';
                    $controller->redirect($default_url);
                }
            }
        }
        else if($controller_name=='internationalinput'){
            if($action_name=='create'||$action_name=='update'){
                if(self::get_role()!=Role::QUAN_LY_KHO_HANG&&self::get_role()!=Role::ADMIN){
                    Yii::app()->session['error_role']='Bạn không có quyền '.(($action_name=='create')?'tạo mới':'chỉnh sửa').' [nhập kho và chi phí]--->[tờ khai]';
                    $controller->redirect($default_url);
                }
            }
        }
        else if($controller_name=='invoicechiphifull'){
            if($action_name=='create'||$action_name=='update'){
                if(self::get_role()!=Role::QUAN_LY_KHO_HANG&&self::get_role()!=Role::ADMIN){
                    Yii::app()->session['error_role']='Bạn không có quyền '.(($action_name=='create')?'tạo mới':'chỉnh sửa').' [nhập kho và chi phí]--->[chi phí dịch vụ có HĐ]';
                    $controller->redirect($default_url);
                }
            }
        }
        else if($controller_name=='chiphikhdfull'){
            if($action_name=='create'||$action_name=='update'){
                if(self::get_role()!=Role::QUAN_LY_KHO_HANG&&self::get_role()!=Role::ADMIN){
                    Yii::app()->session['error_role']='Bạn không có quyền '.(($action_name=='create')?'tạo mới':'chỉnh sửa').' [nhập kho và chi phí]--->[chi phí dịch vụ không HĐ]';
                    $controller->redirect($default_url);
                }
            }
        }
        else if($controller_name=='invoicefull'){
            if($action_name=='create'||$action_name=='update'){
                if(self::get_role()!=Role::QUAN_LY_BAN_HANG&&self::get_role()!=Role::ADMIN){
                    Yii::app()->session['error_role']='Bạn không có quyền '.(($action_name=='create')?'tạo mới':'chỉnh sửa').' [hóa đơn bán hàng]--->[thương mại]';
                    $controller->redirect($default_url);
                }
            }
        }
        else if($controller_name=='sxdvfull'){
            if($action_name=='create'||$action_name=='update'){
                if(self::get_role()!=Role::QUAN_LY_BAN_HANG&&self::get_role()!=Role::ADMIN){
                    Yii::app()->session['error_role']='Bạn không có quyền '.(($action_name=='create')?'tạo mới':'chỉnh sửa').' [hóa đơn bán hàng]--->[Sản xuất & Dịch vụ]';
                    $controller->redirect($default_url);
                }
            }
        }
        else if($controller_name=='kxhdfull'){
            if($action_name=='create'||$action_name=='update'){
                if(self::get_role()!=Role::QUAN_LY_BAN_HANG&&self::get_role()!=Role::ADMIN){
                    Yii::app()->session['error_role']='Bạn không có quyền '.(($action_name=='create')?'tạo mới':'chỉnh sửa').' [hóa đơn bán hàng]--->[không xuất HĐ]';
                    $controller->redirect($default_url);
                }
            }
        }
        else if($controller_name=='laisuatfull'){
            if($action_name=='create'||$action_name=='update'){
                if(self::get_role()!=Role::QUAN_LY_BAN_HANG&&self::get_role()!=Role::ADMIN){
                    Yii::app()->session['error_role']='Bạn không có quyền '.(($action_name=='create')?'tạo mới':'chỉnh sửa').' [hóa đơn bán hàng]--->[lãi suất]';
                    $controller->redirect($default_url);
                }
            }
        }
    }
    /**
     * get quyền của user
     * @return string
     */
    public static function get_role() {
        return Yii::app()->session['role'];            
    }
    /**
     * hiển thị lỗi quyền
     * ví dụ:
     *       user không có quyền tạo mới hóa đơn xuất 
     *       khi user nhập url xxx/invoice/create
     *       hệ thống sẽ quay về page mặc định của hệ thống và báo lỗi "bạn không có quyền tạo mới hóa đơn xuất"
     * @return html 
     */
    public static function echo_role_error(){
        if(isset(Yii::app()->session['error_role'])){
            ?>
        <h1 style="color: red;text-align: center;">
            <?php 
            echo Yii::app()->session['error_role'];
            unset(Yii::app()->session['error_role']); 
            ?>
        </h1>
        <?php
        }        
    }

}