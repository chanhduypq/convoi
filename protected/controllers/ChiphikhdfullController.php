<?php

class ChiphikhdfullController extends Controller {

    private $action;

    public function init() {
        parent::init();
        /**
         * tại page index se có link đến CHI TIẾT/SỬA hóa đơn
         * nếu user có quyền chỉnh sửa thi se link đến page chỉnh sửa(action update), còn không thi link đến page xem chi tiết (action view)
         * ở đây, $this->action làm nhiệm vụ đưa qua view để mỗi link trong page index se link đến page chỉnh sửa hay page xem chi tiết
         */
        if(FunctionCommon::get_role()==Role::QUAN_LY_KHO_HANG||FunctionCommon::get_role()==Role::ADMIN){
            $this->action='update';
        }
        else{
            $this->action='view';
        }
    }
    
    public function actionIndex() {        
        $params = array();
        /**
         * 
         */
        if($this->all_time_common=='1'){
            $where = "1=1";            
        }
        else{
            $where = "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
            $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";
        }

        
        $items = ChiPhiKhdFull::model()->findAll($where);
                
        $params['items'] = $items;        
        /**
         * lấy tổng tiền, tổng thuế, tổng số hóa đơn
         */
        $row = Yii::app()->db->createCommand()
                ->select("sum(`sum_and_sumtax`) as `sum_and_sumtax`, count(*) as bill_count")
                ->from("chi_phi_khd")
                ->where($where)
                ->queryRow();
        $params['sum_and_sumtax'] = number_format($row['sum_and_sumtax'], 0, ",", ".");
        $params['bill_count'] = $row['bill_count'];
        /**
         * 
         */
        $params['start_date_common'] = Yii::app()->session['start_date_common'];
        $params['end_date_common'] = Yii::app()->session['end_date_common'];
        $params['customer_id_common'] = Yii::app()->session['customer_id_common'];
        $params['goods_id_common'] = Yii::app()->session['goods_id_common'];
        $params['all_time_common'] = Yii::app()->session['all_time_common'];
        $params['action']=  $this->action;
        //
        $this->render('index', $params);
    }

    public function actionMore() {        
        $params = array();
        /**
         * 
         */
        if($this->all_time_common=='1'){
            $where = "1=1";            
        }
        else{
            $where = "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
            $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";
        }
            
        $argPage       = (int) Yii::app()->request->getQuery('page', 0);
        $dbCriteria             = new CDbCriteria;
        $dbCriteria->condition=$where;
        $dbCriteria->limit      = Yii::app()->params['number_of_items_per_page'];
        $dbCriteria->offset     = $argPage * $dbCriteria->limit;
        //        
        $items = ChiPhiKhdFull::model()->findAll($dbCriteria);
        if(!is_array($items)||count($items)==0){
            echo '';
            Yii::app()->end();
        }
        $params['items'] = $items;  
        $params['action']=  $this->action;
        $this->renderPartial('//render_partial/common/more', $params);
    }

    public function actionCreate() {
        $params = array();
        //
        if (Yii::app()->request->isPostRequest) {
            $this->create();               
            $this->redirect(array("/chiphikhdfull/index"));
        }
        
        /**
         * 
         */
        $stt=Yii::app()->db->createCommand("select max(stt) as max from chi_phi_khd")->queryScalar();
        if($stt==FALSE||$stt==""){
            $stt=0;
        }
        $stt++;
        $params['stt']=$stt;
        $params['payment_method']=  PaymentMethod::model()->findAll();
        $params['socai_id']=Yii::app()->request->getParam("socai_id","");
        if($params['socai_id']!=""){
            $model_socai=Socai::model()->findByPk($params['socai_id']);
            $params['tien_tam_ung']=$model_socai->thu+$model_socai->chi;
        }
        else{
            $params['tien_tam_ung']='0';
        }
        //
        $this->render('create', $params);
    }

    protected function update() {
        $different=FALSE;
        /**
         * get parameter (GET/POST)
         */
        $description = Yii::app()->request->getParam("description");
        $created_at = Yii::app()->request->getParam("created_at");        
        $sum_and_sumtax = str_replace(".", "", Yii::app()->request->getParam("sum_and_sumtax",""));
        if($sum_and_sumtax==''){
            $sum_and_sumtax=0;
        }
        /**
         * lưu hóa đơn
         */
        $bill_model = ChiPhiKhd::model()->findByPk(Yii::app()->request->getParam("id"));
        if($sum_and_sumtax!=$bill_model->sum_and_sumtax){
            $different=true;
        }

        $bill_model->sum_and_sumtax = $sum_and_sumtax;  
        $bill_model->description = $description;
        $bill_model->last_updated_at= $bill_model->created_at= FunctionCommon::convertDateForDB($created_at);
        $bill_model->save(FALSE);
        /**
         * 
         */
        if($different==true){
            Common::update_complete_and_socai($bill_model, "chi", "chi_phi_khd_id", "Chi phí dịch vụ không hóa đơn");
        }        
        /**
         * 
         */
        $sum=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("chi_phi_khd_id=" . $bill_model->id . " and chi<>0")
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        $so_tien_con_lai=$bill_model->sum_and_sumtax-$sum;        
        if($so_tien_con_lai==0){
            Yii::app()->db->createCommand("delete from socai where chi_phi_khd_id=" . $bill_model->id." and chi=0")->execute();
        }
        else{
            Yii::app()->db->createCommand("update socai set tm=$so_tien_con_lai where chi_phi_khd_id=" . $bill_model->id." and chi=0")->execute();
        }
    }
   

    

    

    public function actionUpdate() {        
        $params = array();
        /**
         * 
         */
        if (Yii::app()->request->isPostRequest) {
            $this->update();
            $this->save_history_for_update();
            $this->redirect(array("/chiphikhdfull/index"));
        }
        $id=Yii::app()->request->getParam("id",'0');
        //
        $params['invoicefull_model'] = ChiPhiKhdFull::model()->findByAttributes(array('id'=>$id));        
        /**
         * 
         */        
        $update_histoty_array = ChiPhiKhdHistory::getUpdateHistoty($id);        
        $params['update_histoty_array'] = $update_histoty_array;        
        /**
         * 
         */
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $created_user = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,date_format(chi_phi_khd.created_at,'$DATE_FORMAT - %H:%i:%s') AS created_at_date")
                ->from("chi_phi_khd")
                ->leftJoin("user", "user.id=chi_phi_khd.user_id")
                ->where("chi_phi_khd.id=$id")
                ->queryRow()
        ;
        $params['created_user'] = $created_user;
        $params['payment_method']=  PaymentMethod::model()->findAll();
        
        $sum_socai=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("chi_phi_khd_id=$id")
                ->queryScalar();
        if($sum_socai==FALSE||$sum_socai==""){
            $sum_socai=0;
        }
        $params["sum_socai"]=$sum_socai;
        /**
         * 
         */
        $this->render('update', $params);
    }
    public function actionView() {        
        $params = array();       
        $id=Yii::app()->request->getParam("id",'0');
        //
        $params['invoicefull_model'] = ChiPhiKhdFull::model()->findByAttributes(array('id'=>$id));              
        /**
         * 
         */        
        $update_histoty_array = ChiPhiKhdHistory::getUpdateHistoty($id);        
        $params['update_histoty_array'] = $update_histoty_array;        
        /**
         * 
         */
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $created_user = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,date_format(chi_phi_khd.created_at,'$DATE_FORMAT - %H:%i:%s') AS created_at_date")
                ->from("chi_phi_khd")
                ->leftJoin("user", "user.id=chi_phi_khd.user_id")
                ->where("chi_phi_khd.id=$id")
                ->queryRow()
        ;
        $params['created_user'] = $created_user;
        $params['payment_method']=  PaymentMethod::model()->findAll();
        $this->render('view', $params);
    }
        
    

    protected function save_history_for_update() {
        $reason = Yii::app()->request->getParam("reason");
        
        $bill_id = Yii::app()->request->getParam("id");
        
        $bill = Yii::app()->db->createCommand()
                ->select("sum_and_sumtax,last_updated_at")
                ->from("chi_phi_khd")
                ->where("id=$bill_id")
                ->queryRow()
        ;
        $last_updated_at = $bill['last_updated_at'];
        unset($bill['last_updated_at']);
        $data = array('bill' => $bill);
        $data = CJSON::encode($data);
        $model = new ChiPhiKhdHistory();
                
        $model->bill_id = $bill_id;        
        $model->data = $data;
        $model->updated_at = $last_updated_at;
        $model->reason=$reason;
        $model->setIsNewRecord(true);
        $model->save(FALSE);
    }
    /**
     * nếu create có chọn tạm ứng thi update sổ cái, 
     *                                update chính nó(update complete=1) nếu số tiền bằng số tiền tạm ứng
     */
    protected function update_socai($socai_id,$bill_id,$bill_number,$description){
        $payment_method_id=Yii::app()->db->createCommand()
                    ->select("thanh_toan")
                    ->from("socai")
                    ->where("id=$socai_id")
                    ->queryScalar();
        Yii::app()->db->createCommand("update socai set chi_phi_khd_id=$bill_id where id=$socai_id;update chi_phi_khd set payment_method_id=$payment_method_id,is_paying=1 where id=$bill_id")->execute();
        $rows=Yii::app()->db->createCommand()
                ->select()
                ->from("socai")
                ->where("chi_phi_khd_id=$bill_id")
                ->order("id ASC")
                ->queryAll();
        if(is_array($rows)&&count($rows)==2){
            $sum_thu_chi=$rows[0]['thu']+$rows[1]['thu']+$rows[0]['chi']+$rows[1]['chi'];
            $sum_and_sumtax=Yii::app()->db->createCommand()
                ->select("sum_and_sumtax")
                ->from("chi_phi_khd")
                ->where("id=$bill_id")
                ->queryScalar();
            $row_trang_thai2=Yii::app()->db->createCommand("select * from socai where chi_phi_khd_id=$bill_id and thu=0 and chi=0")->queryRow();
            if($sum_and_sumtax==$sum_thu_chi){
                Yii::app()->db->createCommand("delete from socai where thu=0 and chi=0 and chi_phi_khd_id=$bill_id")->execute();
                $trang_thai='<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/complete.png"/>';
                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai',tham_chieu='$bill_number',content='".str_replace("'", "\'", $description) ."' where chi_phi_khd_id=$bill_id")->execute();
                Yii::app()->db->createCommand("update chi_phi_khd set is_complete=1 where id=$bill_id")->execute();
            }
            else{
                $trang_thai1='<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>'.
                                    '<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/not_complete.png"/>';
                $trang_thai2='<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">2</div>'.
                                    '<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/not_complete.png"/>';

                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai1',content='".$row_trang_thai2['content']."',tham_chieu='".$row_trang_thai2['tham_chieu']."' where chi_phi_khd_id=$bill_id and (thu<>0 or chi<>0)")->execute();
                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai2',tm=".($sum_and_sumtax-($rows[0]['thu']+$rows[0]['chi']))." where chi_phi_khd_id=$bill_id and thu=0 and chi=0")->execute();


            }
            Yii::app()->db->createCommand("update thuchi set content='".str_replace("'", "\'", $row_trang_thai2['content']) ."',chi_phi_khd_id=$bill_id where socai_id=$socai_id")->execute();
            Yii::app()->db->createCommand("update tai_khoan_acb set content='".str_replace("'", "\'", $row_trang_thai2['content'])."',chi_phi_khd_id=$bill_id where socai_id=$socai_id")->execute();
        }
    }

    protected function create() {
        /**
         * get parameter (GET/POST)
         */
        $description = Yii::app()->request->getParam("description");
        $created_at = Yii::app()->request->getParam("created_at");
        $sum_and_sumtax = str_replace(".", "", Yii::app()->request->getParam("sum_and_sumtax",""));
        if($sum_and_sumtax==''){
            $sum_and_sumtax=0;
        }
        
        Yii::app()->db->beginTransaction();
        $success=true;
        /**
         * lưu hóa đơn
         */
        $bill_model = new ChiPhiKhd();
        $bill_model->setIsNewRecord(true);
        $bill_model->sum_and_sumtax = $sum_and_sumtax;
        $bill_model->description = $description;
        $bill_model->created_at=  FunctionCommon::convertDateForDB($created_at); 
        $bill_model->payment_method_id=Yii::app()->request->getParam("payment_method",PaymentMethod::CHUA_THANH_TOAN);
        if($bill_model->save(FALSE)==FALSE){
            $success=FALSE;
        }
        $bill_number=$bill_model->stt;
        $bill_id=$bill_model->id;
        if($bill_model->success==FALSE){
            $success=FALSE;
        }
        /**
         * update sổ cái
         */ 
        $socai_id = Yii::app()->request->getParam("socai_id",NULL);        
        if(is_numeric($socai_id)){
            $this->update_socai($socai_id, $bill_id,$bill_number,$description);
        }
        
        if($success==FALSE){
            Yii::app()->db->getCurrentTransaction()->rollback();
            
            Yii::app()->session['error_mysql']='1';
            $this->redirect(array("/chiphikhdfull/create"));      
        }
        else{
            Yii::app()->db->getCurrentTransaction()->commit();
        }
        
    }

}
