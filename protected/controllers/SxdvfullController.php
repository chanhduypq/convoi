<?php

class SxdvfullController extends Controller {

    private $action;

    public function init() {
        
        parent::init();
        /**
         * tại page index se có link đến CHI TIẾT/SỬA hóa đơn
         * nếu user có quyền chỉnh sửa thi se link đến page chỉnh sửa(action update), còn không thi link đến page xem chi tiết (action view)
         * ở đây, $this->action làm nhiệm vụ đưa qua view để mỗi link trong page index se link đến page chỉnh sửa hay page xem chi tiết
         */
        if(FunctionCommon::get_role()==Role::QUAN_LY_BAN_HANG||FunctionCommon::get_role()==Role::ADMIN){
            $this->action='update';
        }
        else{
            $this->action='view';
        }
    }
    /**
     * khi in hoặc xem trước hóa đơn xuất
     * hệ thống se lưu 2 file image trên host
     * 1 tại thư mục được setting, 1 tại thư mục tạm nào đó
     * file tạm này phai được xóa đi khi user xem trước hoặc in xong
     * sau khi xem trước hoặc in, hệ thống se quay về page index/update của hóa đơn xuất. Lúc đó se xóa file này.
     */
    protected function deleteBillFile(){
        if(isset(Yii::app()->session['file_name'])){
            @unlink(Yii::app()->session['file_name']);
        }
    }

    public function actionIndex() {
        $this->deleteBillFile();
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
        $items = SxdvFull::model()->findAll($where);
        $params['items'] = $items;        
        /**
         * lấy tổng tiền, tổng thuế, tổng số hóa đơn
         */
        $row = Yii::app()->db->createCommand()
                ->select("sum(`sum_and_sumtax`) as `sum_and_sumtax`,sum(tax_sum) as tax_sum, count(*) as bill_count")
                ->from("sxdv")
                ->where($where)
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
        $argPage       = (int) Yii::app()->request->getQuery('page', 0);
        $dbCriteria             = new CDbCriteria;
        $dbCriteria->condition=$where;
        $dbCriteria->limit      = Yii::app()->params['number_of_items_per_page'];
        $dbCriteria->offset     = $argPage * $dbCriteria->limit;
        //        
        $items = SxdvFull::model()->findAll($dbCriteria);
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
        /**
         * 
         */
        if (Yii::app()->request->isPostRequest) {
            $this->create();            
            $this->redirect(array("/sxdvfull/index"));
        }
        //
        $model=new Sxdv();
        $params['bill_number'] = $model->getBillNumberForCreate();
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
        $sxdv_donvi = Yii::app()->request->getParam("sxdv_donvi");
        $sxdv_name = Yii::app()->request->getParam("sxdv_name");
        $tax = Yii::app()->request->getParam("tax");
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
        $bill_model = Sxdv::model()->findByPk(Yii::app()->request->getParam("id"));
        if($sum+$tax_sum!=$bill_model->sum+$bill_model->tax_sum){
            $different=true;
        }

        $bill_model->sum = $sum;
        $bill_model->tax_sum = $tax_sum;
        $bill_model->branch_id = $branch_id;  
        $bill_model->save(FALSE);
        /**
         * xóa thông tin chi tiết hóa đơn cũ trước khi lưu thông tin mới         * 
         */
        foreach (SxdvDetail::model()->findAll("bill_id=" . $bill_model->id) as $bill_detail_model) {
            $bill_detail_model->delete();
        }
        /**
         * lưu chi tiết hóa đơn
         */
        for ($i = 0; $i < count($sxdv_name); $i++) {
            $bill_detail_model = new SxdvDetail();
            $bill_detail_model->setIsNewRecord(true);
            $bill_detail_model->sxdv_donvi = $sxdv_donvi[$i];
            $bill_detail_model->sxdv_name = $sxdv_name[$i];
            $bill_detail_model->tax = $tax[$i]==""?0:$tax[$i];
            $bill_detail_model->quantity = $quantity[$i];
            $bill_detail_model->price = $price_not_tax[$i];
            $bill_detail_model->price_has_tax = $price_has_tax[$i];
            $bill_detail_model->bill_id = $bill_model->id;
            $bill_detail_model->save(FALSE);
        }
        /**
         * 
         */
        if($different==true){
            Common::update_complete_and_socai($bill_model, "thu", "sxdv_id", "HĐ Sản xuất & dịch vụ");
        }
        /**
         * 
         */
        $sum=Yii::app()->db->createCommand()
                ->select("sum(thu)")
                ->from("socai")
                ->where("sxdv_id=" . $bill_model->id . " and thu<>0")
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        $so_tien_con_lai=$bill_model->sum_and_sumtax-$sum;
        if($so_tien_con_lai==0){
            Yii::app()->db->createCommand("delete from socai where sxdv_id=" . $bill_model->id." and thu=0")->execute();
        }
        else{
            Yii::app()->db->createCommand("update socai set tm=$so_tien_con_lai where sxdv_id=" . $bill_model->id." and thu=0")->execute();
        }
    }
    
    

    public function actionUpdateandprint1() {
        if (Yii::app()->request->isPostRequest) {              
            $this->update();            
            $bill_id = Yii::app()->request->getParam("id");    
          
            $this->save_history_for_print($bill_id,  SxdvHistory::PRINT_LIEN1);
         
            $model = Sxdv::model()->findByPk($bill_id);
            $model->is_printed = 1;
            $model->save(FALSE);
            Yii::app()->runController('sxdvfull/lien/id/' . $bill_id.'/print/'.Yii::app()->request->getParam("print")."/lien/1");
            exit;
        }
    }

    public function actionUpdateandprint2() {
        if (Yii::app()->request->isPostRequest) {
             
            $this->update();
            $bill_id = Yii::app()->request->getParam("id");            
            $this->save_history_for_print($bill_id,  SxdvHistory::PRINT_LIEN2);
            

            $model = Sxdv::model()->findByPk($bill_id);
            $model->is_printed = 1;
            $model->save(FALSE);   
            
            Yii::app()->runController('sxdvfull/lien/id/' . $bill_id.'/print/'.Yii::app()->request->getParam("print")."/lien/2");
            exit;
        }
    }

    public function actionUpdate() {  
        $this->deleteBillFile();
        $params = array();
        /**
         * 
         */
        if (Yii::app()->request->isPostRequest) {
            $this->update();
            $this->save_history_for_update();
            $this->redirect(array("/sxdvfull/index"));
        }
        $id=Yii::app()->request->getParam("id",'0');
                
        //
        $params['invoicefull_model'] = SxdvFull::model()->findByAttributes(array('id'=>$id));

        /**
         * 
         */         
        $params['bill_details']=  SxdvDetailFull::model()->findAllByAttributes(array('bill_id'=>Yii::app()->request->getParam("id")));
        $params['count_print_lien1_histoty_date'] = SxdvHistory::getPrintCount($id, SxdvHistory::PRINT_LIEN1);
        $params['count_print_lien2_histoty_date'] = SxdvHistory::getPrintCount($id, SxdvHistory::PRINT_LIEN2);
               

        /**
         * 
         */
        
        $histoty_array = SxdvHistory::getBillHistoty($id);
        $params['histoty_array'] = $histoty_array;        
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $created_user = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,date_format(sxdv.created_at,'$DATE_FORMAT - %H:%i:%s') AS created_at_date")
                ->from("sxdv")
                ->leftJoin("user", "user.id=sxdv.user_id")
                ->where("sxdv.id=$id")
                ->queryRow()
        ;
        $params['created_user'] = $created_user;
        $params['payment_method']=  PaymentMethod::model()->findAll();
        
        $sum_socai=Yii::app()->db->createCommand()
                ->select("sum(thu)")
                ->from("socai")
                ->where("sxdv_id=$id")
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
        $params['invoicefull_model'] = SxdvFull::model()->findByAttributes(array('id'=>$id));
        /**
         * 
         */         
        $params['bill_details']=  SxdvDetailFull::model()->findAllByAttributes(array('bill_id'=>Yii::app()->request->getParam("id")));
        $params['count_print_lien1_histoty_date'] = SxdvHistory::getPrintCount($id, SxdvHistory::PRINT_LIEN1);
        $params['count_print_lien2_histoty_date'] = SxdvHistory::getPrintCount($id, SxdvHistory::PRINT_LIEN2);
        
        
        /**
         * 
         */
        
        $histoty_array = SxdvHistory::getBillHistoty($id);
        $params['histoty_array'] = $histoty_array;        
        $DATE_FORMAT=  FunctionCommon::convertDateForDBSelect();
        $created_user = Yii::app()->db->createCommand()
                ->select("user.danh_xung,user.full_name,date_format(sxdv.created_at,'$DATE_FORMAT - %H:%i:%s') AS created_at_date")
                ->from("sxdv")
                ->leftJoin("user", "user.id=sxdv.user_id")
                ->where("sxdv.id=$id")
                ->queryRow()
        ;
        $params['created_user'] = $created_user;
        $params['payment_method']=  PaymentMethod::model()->findAll();
        /**
         * 
         */
        $this->render('view', $params);
    }
        
    public function actionLien() {        
        $params = array();        
        $bill_details= SxdvDetailFull::model()->findAllByAttributes(array('bill_id'=>Yii::app()->request->getParam("id")));
//        foreach ($bill_details as &$bill_detail) {           
//            $model=  UnitNameInitPrintBill::model()->findByAttributes(array('sxdv_detail_id'=>$bill_detail->id,'bill_id'=>Yii::app()->request->getParam("id")));
//            if($model!=NULL){
//                $bill_detail->sxdv_donvi=$model->sxdv_donvi;
//                $bill_detail->sxdv_name=$model->sxdv_name;
//            }
//
//        }
        $params['bill_details']= $bill_details;
        $params['bill_id'] = Yii::app()->request->getParam("id");
        /**
         * 
         */        
        $params['invoicefull_model'] = SxdvFull::model()->findByAttributes(array('id'=>Yii::app()->request->getParam("id")));
        $params['print']=Yii::app()->request->getParam("print");       
        $params['lien']=Yii::app()->request->getParam("lien");
        Yii::app()->controller->renderPartial('lien1', $params);
        Yii::app()->end();
    }

    public function actionPrintandcreate1() {
        $bill_id = $this->create();            
        $this->save_history_for_print($bill_id,  SxdvHistory::PRINT_LIEN1);

        $model = Sxdv::model()->findByPk($bill_id);
        $model->is_printed = 1;
        $model->save(FALSE);
        Yii::app()->runController('sxdvfull/lien/id/' . $bill_id.'/print/'.Yii::app()->request->getParam("print")."/lien/1");        
        exit;
    }

    public function actionPrintandcreate2() {
        $bill_id = $this->create();     
        $this->save_history_for_print($bill_id,  SxdvHistory::PRINT_LIEN2);

        $model = Sxdv::model()->findByPk($bill_id);
        $model->is_printed = 1;
        $model->save(FALSE);
        Yii::app()->runController('sxdvfull/lien/id/' . $bill_id.'/print/'.Yii::app()->request->getParam("print")."/lien/2");
        exit;
    }
    

    
    protected function save_history_for_print($bill_id,$lien) {
        $reason = Yii::app()->request->getParam("reason","");
        if(trim($reason)==""){//nếu đây là in lần đầu
            $reason="Xuất hóa đơn lần đầu";
        }
        $bill = Yii::app()->db->createCommand()
                ->select("branch_id,sum,tax_sum,last_updated_at")
                ->from("sxdv")
                ->where("id=$bill_id")
                ->queryRow()
        ;
        $last_updated_at = $bill['last_updated_at'];
        unset($bill['last_updated_at']);
        $bill_detail = Yii::app()->db->createCommand()
                ->select("id,quantity,price,price_has_tax,sxdv_donvi,sxdv_name,tax")
                ->from("sxdv_detail")
                ->where("bill_id=$bill_id")
                ->queryAll()
        ;
//        for($i=0;$i<count($bill_detail);$i++){           
//            $model=new UnitNameInitPrintBill();
//            $model->setIsNewRecord(true);
//            $model->sxdv_name=$bill_detail[$i]['sxdv_name'];
//            $model->sxdv_donvi=$bill_detail[$i]['sxdv_donvi'];
//            $model->sxdv_detail_id=$bill_detail[$i]['id'];
//
//            $model->bill_id=$bill_id;
//            if($model->findByAttributes(array('sxdv_detail_id'=>$bill_detail[$i]['id'],'bill_id'=>$bill_id))==NULL){
//                $model->save(FALSE);
//            }
//
//        }
        $data = array('bill' => $bill, 'bill_detail' => $bill_detail);
        $data = CJSON::encode($data);
        $model = new SxdvHistory();
        $model->bill_id = $bill_id;
        $model->printed_at = $last_updated_at;
        $model->data = $data;
        $model->updated_at = NULL;
        $model->print_type = $lien;
        $model->reason=(trim($reason)=="")?NULL:$reason;
        $model->is_preview=(Yii::app()->request->getParam("print")=='0')?1:0;
        $model->setIsNewRecord(true);
        $model->save(FALSE);
    }



    protected function save_history_for_update() {
        $reason = Yii::app()->request->getParam("reason");
        
        $bill_id = Yii::app()->request->getParam("id");
        
        $bill = Yii::app()->db->createCommand()
                ->select("branch_id,sum,tax_sum,last_updated_at")
                ->from("sxdv")
                ->where("id=$bill_id")
                ->queryRow()
        ;
        $last_updated_at = $bill['last_updated_at'];
        unset($bill['last_updated_at']);
        $bill_detail = Yii::app()->db->createCommand()
                ->select("sxdv_donvi,sxdv_name,tax,quantity,price,price_has_tax")
                ->from("sxdv_detail")
                ->where("bill_id=$bill_id")
                ->queryAll()
        ;
        $data = array('bill' => $bill, 'bill_detail' => $bill_detail);
        $data = CJSON::encode($data);
        $model = new SxdvHistory();
        $model->bill_id = $bill_id;
        $model->printed_at = NULL;
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
    protected function update_socai($socai_id,$bill_id,$bill_number){
        $payment_method_id=Yii::app()->db->createCommand()
                    ->select("thanh_toan")
                    ->from("socai")
                    ->where("id=$socai_id")
                    ->queryScalar();
        Yii::app()->db->createCommand("update socai set sxdv_id=$bill_id where id=$socai_id;update sxdv set payment_method_id=$payment_method_id,is_paying=1 where id=$bill_id")->execute();
        $rows=Yii::app()->db->createCommand()
                ->select()
                ->from("socai")
                ->where("sxdv_id=$bill_id")
                ->order("id ASC")
                ->queryAll();
        if(is_array($rows)&&count($rows)==2){
            $sum_thu_chi=$rows[0]['thu']+$rows[1]['thu']+$rows[0]['chi']+$rows[1]['chi'];
            $sum_and_sumtax=Yii::app()->db->createCommand()
                ->select("sum_and_sumtax")
                ->from("sxdv")
                ->where("id=$bill_id")
                ->queryScalar();
            $row_trang_thai2=Yii::app()->db->createCommand("select * from socai where sxdv_id=$bill_id and thu=0 and chi=0")->queryRow();
            if($sum_and_sumtax==$sum_thu_chi){
                Yii::app()->db->createCommand("delete from socai where thu=0 and chi=0 and sxdv_id=$bill_id")->execute();
                $trang_thai='<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/complete.png"/>';
                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai',tham_chieu='$bill_number',content='".$row_trang_thai2['content']."' where sxdv_id=$bill_id")->execute();
                Yii::app()->db->createCommand("update sxdv set is_complete=1 where id=$bill_id")->execute();
            }
            else{
                $trang_thai1='<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>'.
                                    '<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/not_complete.png"/>';
                $trang_thai2='<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">2</div>'.
                                    '<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/not_complete.png"/>';

                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai1',content='".$row_trang_thai2['content']."',tham_chieu='".$row_trang_thai2['tham_chieu']."' where sxdv_id=$bill_id and (thu<>0 or chi<>0)")->execute();
                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai2',tm=".($sum_and_sumtax-($rows[0]['thu']+$rows[0]['chi']))." where sxdv_id=$bill_id and thu=0 and chi=0")->execute();


            }
            Yii::app()->db->createCommand("update thuchi set content='".str_replace("'", "\'", $row_trang_thai2['content']) ."',sxdv_id=$bill_id where socai_id=$socai_id")->execute();
            Yii::app()->db->createCommand("update tai_khoan_acb set content='".str_replace("'", "\'", $row_trang_thai2['content'])."',sxdv_id=$bill_id where socai_id=$socai_id")->execute();
        }
    }

    protected function create() {
        /**
         * get parameter (GET/POST)
         */
        $branch_id = Yii::app()->request->getParam("branch_id");
        $sxdv_donvi = Yii::app()->request->getParam("sxdv_donvi");
        $sxdv_name = Yii::app()->request->getParam("sxdv_name");
        $tax = Yii::app()->request->getParam("tax");
        $price_not_tax = Yii::app()->request->getParam("price_not_tax");
        $price_has_tax = Yii::app()->request->getParam("price_has_tax");
        $created_at = Yii::app()->request->getParam("created_at");
        $quantity = Yii::app()->request->getParam("quantity");
        $sum = str_replace(".", "", Yii::app()->request->getParam("sum",""));
        $tax_sum = str_replace(".", "", Yii::app()->request->getParam("tax_sum",""));
        
        Yii::app()->db->beginTransaction();
        $success=true;
        /**
         * lưu hóa đơn
         */
        $bill_model = new Sxdv();
        $bill_model->setIsNewRecord(true);
        $bill_model->sum = $sum;
        $bill_model->tax_sum = $tax_sum;
        $bill_model->branch_id = $branch_id;
        $bill_model->created_at=  FunctionCommon::convertDateForDB($created_at);
        $bill_model->is_printed = 0; 
        $bill_model->payment_method_id=Yii::app()->request->getParam("payment_method",PaymentMethod::CHUA_THANH_TOAN);
        $bill_number=$bill_model->getBillNumberForCreate();
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
        for ($i = 0; $i < count($sxdv_name); $i++) {
            $bill_detail_model = new SxdvDetail();
            $bill_detail_model->setIsNewRecord(true);
            $bill_detail_model->sxdv_donvi = $sxdv_donvi[$i];
            $bill_detail_model->sxdv_name = $sxdv_name[$i];
            $bill_detail_model->tax = $tax[$i]==""?0:$tax[$i];
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
            $this->update_socai($socai_id, $bill_id, $bill_number);
        }
        
        if($success==FALSE){
            Yii::app()->db->getCurrentTransaction()->rollback();
            
            Yii::app()->session['error_mysql']='1';
            $this->redirect(array("/sxdvfull/create"));      
        }
        else{
            Yii::app()->db->getCurrentTransaction()->commit();
        }
        
        return $bill_id;
    }

}
