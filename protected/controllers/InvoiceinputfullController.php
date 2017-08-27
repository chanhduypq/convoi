<?php

class InvoiceinputfullController extends Controller {

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
        
        if ($this->customer_id_common != "") {
            $customer_id_common=  $this->customer_id_common;
            $where.=" and branch_id=$customer_id_common";
        }
        if ($this->goods_id_common != "") {  
            $goods_id_common=  $this->goods_id_common;
            $where.=" and id IN (select bill_id from bill_input_detail where goods_id=$goods_id_common)";
        }
        $items = InvoiceInputFull::model()->findAll($where);
        $params['items'] = $items;        
        /**
         * lấy tổng tiền, tổng thuế, tổng số hóa đơn
         */
        $row = Yii::app()->db->createCommand()
                ->select("sum(`sum_and_sumtax`) as `sum_and_sumtax`,sum(tax_sum) as tax_sum, count(*) as bill_count")
                ->from("bill_input")
                ->where($where)
                ->andWhere("is_international=0")
                ->queryRow();
        $params['sum_and_sumtax'] = number_format($row['sum_and_sumtax'], 0, ",", ".");
        $params['tax_sum'] = number_format($row['tax_sum'], 0, ",", ".");
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
        if ($this->customer_id_common != "") {
            $customer_id_common=  $this->customer_id_common;
            $where.=" and branch_id=$customer_id_common";
        }
        if ($this->goods_id_common != "") {  
            $goods_id_common=  $this->goods_id_common;
            $where.=" and id IN (select bill_id from bill_input_detail where goods_id=$goods_id_common)";
        }         
        $argPage       = (int) Yii::app()->request->getQuery('page', 0);
        $dbCriteria             = new CDbCriteria;
        $dbCriteria->condition=$where;
        $dbCriteria->limit      = Yii::app()->params['number_of_items_per_page'];
        $dbCriteria->offset     = $argPage * $dbCriteria->limit;
        //        
        $items = InvoiceInputFull::model()->findAll($dbCriteria);
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
            $this->redirect(array("/invoiceinputfull/index"));
        }
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
        $branch_id = Yii::app()->request->getParam("branch_id");
        $description = Yii::app()->request->getParam("description");
        $bill_number = Yii::app()->request->getParam("bill_number");
        $created_at = Yii::app()->request->getParam("created_at");
        $goods_id = Yii::app()->request->getParam("goods_id");
        $price_not_tax = Yii::app()->request->getParam("price_not_tax");
        $price_has_tax = Yii::app()->request->getParam("price_has_tax");
        $quantity = Yii::app()->request->getParam("quantity");
        $sum = str_replace(".", "", Yii::app()->request->getParam("sum",""));
        $tax_sum = str_replace(".", "", Yii::app()->request->getParam("tax_sum",""));
        if($branch_id==NULL){
            return;
        }
        /**
         * lưu hóa đơn
         */
        $bill_model = BillInput::model()->findByPk(Yii::app()->request->getParam("id"));
        if($sum+$tax_sum!=$bill_model->sum+$bill_model->tax_sum){
            $different=true;
        }

        $bill_model->sum = $sum;
        $bill_model->tax_sum = $tax_sum;
        $bill_model->branch_id = $branch_id;   
        $bill_model->description = $description;
        $bill_model->bill_number = $bill_number;
        $bill_model->last_updated_at=$bill_model->created_at=  FunctionCommon::convertDateForDB($created_at);
        $bill_model->save(FALSE);
        /**
         * xóa thông tin chi tiết hóa đơn cũ trước khi lưu thông tin mới         * 
         */        
        foreach (BillInputDetail::model()->findAll("bill_id=" . $bill_model->id) as $bill_detail_model) {
            $bill_detail_model->delete();
        }
        /**
         * lưu chi tiết hóa đơn
         */
        for ($i = 0; $i < count($goods_id); $i++) {
            $bill_detail_model = new BillInputDetail();
            $bill_detail_model->setIsNewRecord(true);
            $bill_detail_model->goods_id = $goods_id[$i];
            $bill_detail_model->quantity = $quantity[$i];
            $bill_detail_model->price = $price_not_tax[$i];
            $bill_detail_model->price_has_tax = $price_has_tax[$i];
            $bill_detail_model->bill_id = $bill_model->id;
            $bill_detail_model->save(FALSE);
        }
        /**
         * nếu số hóa đơn thay đổi thi số hóa đơn bên sổ cái cũng thay đổi theo
         */
        Yii::app()->db->createCommand("update socai set tham_chieu='$bill_number' where bill_input_id=".Yii::app()->request->getParam("id"))->execute();
        /**
         * 
         */
        if($different==true){
            Common::update_complete_and_socai($bill_model, "chi", "bill_input_id", "Nhập kho kinh doanh");
        }
        /**
         * 
         */
        $sum=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("bill_input_id=" . $bill_model->id . " and chi<>0")
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        $so_tien_con_lai=$bill_model->sum_and_sumtax-$sum;
        if($so_tien_con_lai==0){
            Yii::app()->db->createCommand("delete from socai where bill_input_id=" . $bill_model->id." and chi=0")->execute();
        }
        else{
            Yii::app()->db->createCommand("update socai set tm=$so_tien_con_lai where bill_input_id=" . $bill_model->id." and chi=0")->execute();
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
            $this->redirect(array("/invoiceinputfull/index"));
        }
        $id=Yii::app()->request->getParam("id",'0');
        //
        $params['invoicefull_model'] = InvoiceInputFull::model()->findByAttributes(array('id'=>$id));              
        $params['bill_details']=  BillInputDetailFull::model()->findAllByAttributes(array('bill_id'=>Yii::app()->request->getParam("id")));    
        /**
         * 
         */        
        $update_histoty_array = BillInputHistory::getUpdateHistoty($id);        
        $params['update_histoty_array'] = $update_histoty_array;        
        /**
         * 
         */
        $goods = Yii::app()->db->createCommand()
                ->select("group_id,goods_full_name")
                ->from("goods")
                ->group("group_id")
                ->queryAll();

        $params['goods'] = $goods;
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $created_user = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,date_format(bill_input.created_at,'$DATE_FORMAT - %H:%i:%s') AS created_at_date")
                ->from("bill_input")
                ->leftJoin("user", "user.id=bill_input.user_id")
                ->where("bill_input.id=$id")
                ->queryRow()
        ;
        $params['created_user'] = $created_user;
        $params['payment_method']=  PaymentMethod::model()->findAll();
        
        $sum_socai=Yii::app()->db->createCommand()
                ->select("sum(chi)")
                ->from("socai")
                ->where("bill_input_id=$id")
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
        $params['invoicefull_model'] = InvoiceInputFull::model()->findByAttributes(array('id'=>$id));              
        $params['bill_details']=  BillInputDetailFull::model()->findAllByAttributes(array('bill_id'=>Yii::app()->request->getParam("id")));    
        /**
         * 
         */        
        $update_histoty_array = BillInputHistory::getUpdateHistoty($id);        
        $params['update_histoty_array'] = $update_histoty_array;        
        /**
         * 
         */
        $goods = Yii::app()->db->createCommand()
                ->select("group_id,goods_full_name")
                ->from("goods")
                ->group("group_id")
                ->queryAll();

        $params['goods'] = $goods;
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $created_user = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,date_format(bill_input.created_at,'$DATE_FORMAT - %H:%i:%s') AS created_at_date")
                ->from("bill_input")
                ->leftJoin("user", "user.id=bill_input.user_id")
                ->where("bill_input.id=$id")
                ->queryRow()
        ;
        $params['created_user'] = $created_user;
        $params['payment_method']=  PaymentMethod::model()->findAll("id<>".PaymentMethod::KHONG_THANH_TOAN);
        /**
         * 
         */
        $this->render('view', $params);
    }
        
    

    protected function save_history_for_update() {
        $reason = Yii::app()->request->getParam("reason");
        
        $bill_id = Yii::app()->request->getParam("id");
        
        $bill = Yii::app()->db->createCommand()
                ->select("branch_id,sum,tax_sum,last_updated_at")
                ->from("bill_input")
                ->where("id=$bill_id")
                ->queryRow()
        ;
        $last_updated_at = $bill['last_updated_at'];
        unset($bill['last_updated_at']);
        $bill_detail = Yii::app()->db->createCommand()
                ->select("goods_id,quantity,price,price_has_tax")
                ->from("bill_input_detail")
                ->where("bill_id=$bill_id")
                ->queryAll()
        ;
        $data = array('bill' => $bill, 'bill_detail' => $bill_detail);
        $data = CJSON::encode($data);
        $model = new BillInputHistory();
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
        Yii::app()->db->createCommand("update socai set bill_input_id=$bill_id where id=$socai_id;update bill_input set payment_method_id=$payment_method_id,is_paying=1 where id=$bill_id")->execute();
        $rows=Yii::app()->db->createCommand()
                ->select()
                ->from("socai")
                ->where("bill_input_id=$bill_id")
                ->order("id ASC")
                ->queryAll();
        if(is_array($rows)&&count($rows)==2){
            $sum_thu_chi=$rows[0]['thu']+$rows[1]['thu']+$rows[0]['chi']+$rows[1]['chi'];
            $sum_and_sumtax=Yii::app()->db->createCommand()
                ->select("sum_and_sumtax")
                ->from("bill_input")
                ->where("id=$bill_id")
                ->queryScalar();
            $row_trang_thai2=Yii::app()->db->createCommand("select * from socai where bill_input_id=$bill_id and thu=0 and chi=0")->queryRow();
            if($sum_and_sumtax==$sum_thu_chi){
                Yii::app()->db->createCommand("delete from socai where thu=0 and chi=0 and bill_input_id=$bill_id")->execute();
                $trang_thai='<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/complete.png"/>';
                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai',tham_chieu='$bill_number',content='".str_replace("'", "\'", $description) ."' where bill_input_id=$bill_id")->execute();
                Yii::app()->db->createCommand("update bill_input set is_complete=1 where id=$bill_id")->execute();
            }
            else{
                $trang_thai1='<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>'.
                                    '<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/not_complete.png"/>';
                $trang_thai2='<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">2</div>'.
                                    '<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/not_complete.png"/>';

                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai1',content='".$row_trang_thai2['content']."',tham_chieu='".$row_trang_thai2['tham_chieu']."' where bill_input_id=$bill_id and (thu<>0 or chi<>0)")->execute();
                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai2',tm=".($sum_and_sumtax-($rows[0]['thu']+$rows[0]['chi']))." where bill_input_id=$bill_id and thu=0 and chi=0")->execute();


            }
            Yii::app()->db->createCommand("update thuchi set content='".str_replace("'", "\'", $row_trang_thai2['content']) ."',bill_input_id=$bill_id where socai_id=$socai_id")->execute();
            Yii::app()->db->createCommand("update tai_khoan_acb set content='".str_replace("'", "\'", $row_trang_thai2['content'])."',bill_input_id=$bill_id where socai_id=$socai_id")->execute();

        }
    }

    protected function create() {
        /**
         * get parameter (GET/POST)
         */
        $branch_id = Yii::app()->request->getParam("branch_id");
        $description = Yii::app()->request->getParam("description");
        $bill_number = Yii::app()->request->getParam("bill_number");
        $created_at = Yii::app()->request->getParam("created_at");
        $goods_id = Yii::app()->request->getParam("goods_id");
        $price_not_tax = Yii::app()->request->getParam("price_not_tax");
        $price_has_tax = Yii::app()->request->getParam("price_has_tax");
        $quantity = Yii::app()->request->getParam("quantity");
        $sum = str_replace(".", "", Yii::app()->request->getParam("sum",""));
        $tax_sum = str_replace(".", "", Yii::app()->request->getParam("tax_sum",""));
        
        Yii::app()->db->beginTransaction();
        $success=true;
        /**
         * lưu hóa đơn
         */
        $bill_model = new BillInput();
        $bill_model->setIsNewRecord(true);
        $bill_model->sum = $sum;
        $bill_model->description = $description;
        $bill_model->bill_number = $bill_number;
        $bill_model->created_at=  FunctionCommon::convertDateForDB($created_at);
        $bill_model->tax_sum = $tax_sum;
        $bill_model->branch_id = $branch_id;      
        $bill_model->payment_method_id=Yii::app()->request->getParam("payment_method",PaymentMethod::CHUA_THANH_TOAN);
        if($bill_model->save(FALSE)==FALSE){
            $success=FALSE;
        }
        $bill_id = $bill_model->id;
        if($bill_model->success==FALSE){
            $success=FALSE;
        }
        /**
         * lưu chi tiết hóa đơn
         */
        for ($i = 0; $i < count($goods_id); $i++) {
            $bill_detail_model = new BillInputDetail();
            $bill_detail_model->setIsNewRecord(true);
            $bill_detail_model->goods_id = $goods_id[$i];
            $bill_detail_model->quantity = $quantity[$i];
            $bill_detail_model->price = $price_not_tax[$i];
            $bill_detail_model->price_has_tax = $price_has_tax[$i];
            $bill_detail_model->bill_id = $bill_id;
            if($bill_detail_model->save(FALSE)==FALSE){
                $success=FALSE;
            }
        }
        /**
         * update sổ cái
         */ 
        $socai_id = Yii::app()->request->getParam("socai_id",NULL);        
        if(is_numeric($socai_id)){
            $this->update_socai($socai_id, $bill_id, $bill_number, $description);
        }
        
        if($success==FALSE){
            Yii::app()->db->getCurrentTransaction()->rollback();
            
            Yii::app()->session['error_mysql']='1';
            $this->redirect(array("/invoiceinputfull/create"));      
        }
        else{
            Yii::app()->db->getCurrentTransaction()->commit();
        }
        
    }

}
