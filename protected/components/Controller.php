<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to 'column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $pageTitle = array();
    public $start_date_common;
    public $end_date_common;
    public $customer_id_common;
    public $goods_id_common;
    public $all_time_common;
    public $goodsleft_equal_0;
    public $not_buy;

    public function init() {
        return parent::init();
    }

    protected function getParamsForFilter() { 
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') {
            $value = date("Y.m.01");
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            $value = date("Y-m-01");
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $value = date("Y/m/01");
        } elseif ($DATE_FORMAT == 'Ymd') {
            $value = date("Ym01");
        } 
        if(Yii::app()->request->isAjaxRequest&&  is_array($_POST)&&count($_POST)>0&&isset($_POST['all_time_common'])){//nếu user đã submit qua ajax            
            $all_time=Yii::app()->request->getParam("all_time_common", '0');
            $all_goodleft=Yii::app()->request->getParam("goodsleft_equal_0", '0');
            $not_buy=Yii::app()->request->getParam("not_buy", '0');
            if($all_time=='1'){
                Yii::app()->session['start_date_common']=$value;
                Yii::app()->session['end_date_common']=date($DATE_FORMAT);
            }
            else{
                Yii::app()->session['start_date_common']=Yii::app()->request->getParam("start_date_common", $value);
                Yii::app()->session['end_date_common']= Yii::app()->request->getParam("end_date_common", date($DATE_FORMAT));
            }

            Yii::app()->session['customer_id_common']= Yii::app()->request->getParam("customer_id_common", "");
            Yii::app()->session['goods_id_common']=Yii::app()->request->getParam("goods_id_common", "");
            Yii::app()->session['all_time_common']=$all_time;
            Yii::app()->session['goodsleft_equal_0']=$all_goodleft;
            Yii::app()->session['not_buy']=$not_buy;
            if(!is_numeric(Yii::app()->request->getParam("customer_id_common", ""))){
                Yii::app()->session['customer_text_common']='';
            }
            else{
                Yii::app()->session['customer_text_common'] = Yii::app()->db->createCommand()
                    ->select("short_hand_name")
                    ->from("branch")
                    ->where("id=".Yii::app()->request->getParam("customer_id_common"))
                    ->queryScalar();
            }
            if(!is_numeric(Yii::app()->request->getParam("goods_id_common", ""))){
                Yii::app()->session['goods_text_common']='';
            }
            else{
                Yii::app()->session['goods_text_common'] = Yii::app()->db->createCommand()
                    ->select("goods_short_hand_name")
                    ->from("goods")
                    ->where("id=".Yii::app()->request->getParam("goods_id_common"))
                    ->queryScalar();
            }
        }
        else{
            if(!isset(Yii::app()->session['start_date_common'])){
                Yii::app()->session['start_date_common']=$value;
                Yii::app()->session['end_date_common']=date($DATE_FORMAT);
                Yii::app()->session['customer_id_common']= "";
                Yii::app()->session['goods_id_common']="";
                Yii::app()->session['customer_text_common']="";
                Yii::app()->session['goods_text_common']="";
                Yii::app()->session['all_time_common']='0';
                Yii::app()->session['goodsleft_equal_0']='0';
                Yii::app()->session['not_buy']='0';
            }
            
        }
        
        $this->start_date_common = Yii::app()->session['start_date_common'];
        $this->end_date_common = Yii::app()->session['end_date_common'];
        $this->customer_id_common = Yii::app()->session['customer_id_common'];
        $this->goods_id_common = Yii::app()->session['goods_id_common'];
        $this->all_time_common = Yii::app()->session['all_time_common'];
        $this->goodsleft_equal_0 = Yii::app()->session['goodsleft_equal_0'];
        $this->not_buy = Yii::app()->session['not_buy'];
        
        
    }
    /**
     * set session sort
     * kiểm tra user đang click vào title nào trong header của page index hàng hóa/khách hàng/hóa đơn/...
     * kết quả hiển thị se dc sắp xếp theo title đó
     */
    protected function setSessionForSort(){
        $field=Yii::app()->request->getParam("field",NULL);
        $session_key=Yii::app()->request->getParam("session_key",NULL);  
        if($field!=NULL&&$session_key!=NULL){
            $old_session_sort=Yii::app()->session[$session_key];
            FunctionCommon::setSesstionForSort($old_session_sort, $field,$session_key);
        }
    }
    
    public function afterAction($action) {
        parent::afterAction($action);        
        if($action->id!='editprofile'){                
            unset(Yii::app()->session['back_url_from_edit_profile']);
        }
        
    }

    public function beforeAction($action) {           
        if (!(Yii::app()->request->isAjaxRequest)) {
            /**
             * nếu user gõ url có parameter $_GET không đúng thi redirect về trang hóa đơn xuất
             * ví dụ: 
             *     url có parameter $_GET đúng: xxx/invoicefull/update/id/1
             *     url có parameter $_GET 
             *                           không đúng: xxx/invoicefull/update/id1/1
             *                           hoặc dư: xxx/invoicefull/update/id/1/p1/5
             * danh sách các key $_GET được định nghĩa trong Yii::app()->params['key_list_of_get_method'] tại file params.php                       
             * do đó khi một url nào đó trong một file code bất kỳ được sinh ra thi phải được thêm vào array Yii::app()->params['key_list_of_get_method']
             */
            if(count($_GET)>0){
                foreach ($_GET as $key=>$value) {
                    if(!in_array($key, Yii::app()->params['key_list_of_get_method'])){
                        $this->redirect(array("/thongke"));
                    }
                }
                
            }
            /**
             * 
             */
            $id = Yii::app()->request->getParam("id", "");
            if ($id != "" && !is_numeric($id)) {
                $this->redirect(array("/thongke"));
            }
        }        
        //
        if (!($action->controller->id == 'index' && $action->id == 'logout')) {

            if (!isset(Yii::app()->session['username'])) {
                if ($action->controller->id != 'index') {
                    $this->redirect(array("/index/login"));
                }
            } 
            else {               
                $this->initSessionForSort();
                $this->initSessionDateFormat();
                $this->initSessionCalculateWay();
                $this->initSessionPathForSaveBill();
                $this->initSessionMauHD();
                $this->getParamsForFilter();
                $this->setSessionForSort();                
                FunctionCommon::redirect_invoicefull_if_not_allow($this, $action->controller->id, $this->createUrl('thongke/index'),$action->id);                
                if ($action->controller->id == 'index') {//nếu url chỉ là xxx.yyy (có nghĩa là không phải ở dạng xxx.yyy/controller/...)
                    $this->redirect(array("/thongke"));
                }
            }
        }        
        //
        return parent::beforeAction($action);
    }
    /**
     * khỏi tạo session
     * page hóa đơn: khởi tạo sắp xếp theo số hóa đơn
     * page khách hàng: khởi tạo sắp xếp theo tên khách hàng
     * page hàng hóa: khởi tạo sắp xếp theo tên hàng hóa
     */
    protected function initSessionForSort(){
        if (!isset(Yii::app()->session['goods_list_sort'])) {
            Yii::app()->session['goods_list_sort'] = 'goods_short_hand_name ASC,goods_full_name ASC';
        }
        if (!isset(Yii::app()->session['goods_input_list_sort'])) {
            Yii::app()->session['goods_input_list_sort'] = 'goods_short_hand_name ASC,goods_full_name ASC';
        }
        if (!isset(Yii::app()->session['goods_left_list_sort'])) {
            Yii::app()->session['goods_left_list_sort'] = 'goods_short_hand_name ASC,goods_full_name ASC';
        }
        if (!isset(Yii::app()->session['customer_list_sort'])) {
            Yii::app()->session['customer_list_sort'] = 'short_hand_name ASC,full_name ASC';
        }
        if (!isset(Yii::app()->session['customer_sxdv_list_sort'])) {
            Yii::app()->session['customer_sxdv_list_sort'] = 'short_hand_name ASC,full_name ASC';
        }
        if (!isset(Yii::app()->session['customer_kxhd_list_sort'])) {
            Yii::app()->session['customer_kxhd_list_sort'] = 'short_hand_name ASC,full_name ASC';
        }
        if (!isset(Yii::app()->session['supplier_list_sort'])) {
            Yii::app()->session['supplier_list_sort'] = 'short_hand_name ASC,full_name ASC';
        }
        if (!isset(Yii::app()->session['international_input_list_sort'])) {
            Yii::app()->session['international_input_list_sort'] = 'short_hand_name ASC,full_name ASC';
        }
        if (!isset(Yii::app()->session['invoice_list_sort'])) {
            Yii::app()->session['invoice_list_sort'] = 'bill_number DESC';
        }
        if (!isset(Yii::app()->session['sxdv_list_sort'])) {
            Yii::app()->session['sxdv_list_sort'] = 'bill_number DESC';
        }
        if (!isset(Yii::app()->session['invoice_input_list_sort'])) {
            Yii::app()->session['invoice_input_list_sort'] = 'bill_number DESC';
        }
        if (!isset(Yii::app()->session['invoice_chiphi_list_sort'])) {
            Yii::app()->session['invoice_chiphi_list_sort'] = 'bill_number DESC';
        }
        if (!isset(Yii::app()->session['kxhd_list_sort'])) {
            Yii::app()->session['kxhd_list_sort'] = 'stt DESC';
        }
        if (!isset(Yii::app()->session['lai_suat_list_sort'])) {
            Yii::app()->session['lai_suat_list_sort'] = 'stt DESC';
        }
        if (!isset(Yii::app()->session['chi_phi_khd_list_sort'])) {
            Yii::app()->session['chi_phi_khd_list_sort'] = 'stt DESC';
        }
        if (!isset(Yii::app()->session['user_list_sort'])) {
            Yii::app()->session['user_list_sort'] = 'danh_xung ASC,full_name ASC';
        }
        if (!isset(Yii::app()->session['international_list_sort'])) {
            Yii::app()->session['international_list_sort'] = 'short_hand_name ASC,full_name ASC';
        }
        if (!isset(Yii::app()->session['thuchi_list_sort'])) {
            Yii::app()->session['thuchi_list_sort'] = 'created_at ASC';
        }
        if (!isset(Yii::app()->session['tai_khoan_acb_list_sort'])) {
            Yii::app()->session['tai_khoan_acb_list_sort'] = 'created_at ASC';
        }
        if (!isset(Yii::app()->session['socai_list_sort'])) {
            Yii::app()->session['socai_list_sort'] = 'created_at ASC';
        }
        
    }
    /**
     * khỏi tạo session kiểu hiển thị ngày tháng
     * năm/tháng/ngày năm-tháng-ngày nămthángngày năm.tháng.ngày
     * kiểu hiển thị này được set trong table date_format
     */
    protected function initSessionDateFormat(){
        if (!isset(Yii::app()->session['date_format'])) {
            Yii::app()->session['date_format'] = Yii::app()->db->createCommand()
                                                                ->select("date_format")
                                                                ->from("date_format")
                                                                ->where("active=1")
                                                                ->queryScalar();
        }        
    }
    /**
     * khởi tạo session phương thức tính tổng tiền trên hoá đơn     
     */
    protected function initSessionCalculateWay(){
        if (!isset(Yii::app()->session['calculate_way'])) {
            Yii::app()->session['calculate_way'] = Yii::app()->db->createCommand()
                                                                ->select("way")
                                                                ->from("calculate_way")                                                                
                                                                ->queryScalar();
        }        
    }
    /**
     * khởi tạo session đường dẫn thư mục lưu trữ hóa đơn   
     */
    protected function initSessionPathForSaveBill(){
        if (!isset(Yii::app()->session['path_for_save_bill'])) {
            $path_for_save_bill = Yii::app()->db->createCommand()
                                                                ->select("path")
                                                                ->from("save_bill_path")                                                                
                                                                ->queryScalar();
            if($path_for_save_bill[0]!='/'){
                $path_for_save_bill='/'.$path_for_save_bill;
            }
            if($path_for_save_bill[strlen($path_for_save_bill)-1]!='/'){
                $path_for_save_bill.='/';
            }
            Yii::app()->session['path_for_save_bill']=$path_for_save_bill;
        }        
    }
    /**
     * khởi tạo session mẫu hóa đơn, kí hiệu hóa đơn 
     */
    protected function initSessionMauHD(){
        if (!isset(Yii::app()->session['sign'])||!isset(Yii::app()->session['mau_so'])) {
            $row = Yii::app()->db->createCommand()
                ->select("*")
                ->from("bill_sign")                                                                
                ->queryRow();
            Yii::app()->session['sign']=$row['sign'];
            Yii::app()->session['mau_so']=$row['mau_so'];
        }        
    }
//    public function filters() {
//        return array(
//            'accessControl', 
//        );
//    }
//    /**
//     * 
//     */
//    public function accessRules() {
//        if (Yii::app()->controller->id == "user"&&!FunctionCommon::allow_manage_user()) {
//            Yii::app()->session['error_role']='Bạn không có quyền quản lý user.';
//            $this->redirect($this->createUrl('invoicefull/index'));
//        }
//        else if (Yii::app()->controller->id == "system"&&!FunctionCommon::allow_manage_system()) {
//            Yii::app()->session['error_role']='Bạn không có quyền quản lý hệ thống.';
//            $this->redirect($this->createUrl('invoicefull/index'));
//        }
//
//        return array(
//            array('allow',
//                'users' => array('*'),
//            ),
//            array('deny', // deny all users
//                'actions' => array('create', 'index', 'update'),
//                'users' => array('*'),
//            ),
//        );
//    }

}
