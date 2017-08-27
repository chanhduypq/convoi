<?php

class TaikhoanacbController extends Controller {

    public function init() {
        parent::init();
    }
    public function actionKetso() { 
        TaiKhoanACB::ket_so();
        Yii::app()->end();
    }
    public function actionGethistory() {
        $params = array();
        $params['items']=  TaiKhoanACBHistory::model()->findAll("thuchi_id=".Yii::app()->request->getParam("thuchi_id"));     
        $this->renderPartial('gethistory', $params);
    }
    public function actionCreategoods() { 
        $params = array();
        if (Yii::app()->request->isPostRequest) {
            $this->creategoods();            
            $this->redirect(array("/taikhoanacb/index"));
        }  
        /**
         * 
         */
        $min_date=Yii::app()->db->createCommand("select max(created_at) as max from tai_khoan_acb where is_init=1 AND MONTH(created_at)=".date("m")." AND YEAR(created_at)=".date("Y"))->queryScalar();
        $min_date=  explode(" ", $min_date);
        $min_date=$min_date[0];
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') { 
            $min_date = implode(".", explode("-", $min_date));
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            $min_date = implode("-", explode("-", $min_date));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $min_date = implode("/", explode("-", $min_date));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $min_date = implode("", explode("-", $min_date));
        }
        $params['min_date']=$min_date;
        $this->render('creategoods', $params);
    }
    protected function creategoods() {
        /**
         * get parameter (GET/POST)
         */
        $goods_id = Yii::app()->request->getParam("goods_id");
        $price_not_tax = Yii::app()->request->getParam("price_not_tax");
        $price_has_tax = Yii::app()->request->getParam("price_has_tax");
        $created_at =  FunctionCommon::convertDateForDB(Yii::app()->request->getParam("created_at")).date(" H:i:s");
        $quantity = Yii::app()->request->getParam("quantity");
        $sum = str_replace(".", "", Yii::app()->request->getParam("sum",""));
        $tax_sum = str_replace(".", "", Yii::app()->request->getParam("tax_sum",""));
        /**
         * lưu thu/chi
         */
        $tien=$sum+$tax_sum;
        $model=new TaiKhoanACB();
        $model->setIsNewRecord(true);
        $model->content=Yii::app()->request->getParam("content");

        $model->created_at =$created_at;
        $model->chi = 0;
        $temp = $tien;
        $temp1 = intval($temp / 1000);
        if ($temp % 1000 >= 500) {
            $temp1++;
        }
        $model->thu = $temp1;
        
        $model->type = Yii::app()->request->getParam("type");
        if($model->type==TaiKhoanACB::CHUYEN_KHOAN){
            $model->chuyen_khoan=1;
            $model->khac=0;
        }
        else if($model->type==TaiKhoanACB::OTHER){
            $model->chuyen_khoan=0;
            $model->khac=1;
        }
        else{
            $model->chuyen_khoan=0;
            $model->khac=0;
        }
        $model->is_init = 0;
        $model->tm = 0;
        $model->bill_chi_phi_id = NULL;
        $model->bill_id = NULL;
        $model->bill_input_id = NULL;
        $model->is_lock=0;
        $model->save(FALSE);
        
        /**
         * update tat ca các record tai_khoan_acb trong tháng hiện tại
         */
        $created_at=  explode(" ", $model->created_at);
        $created_at=$created_at[0];
        $created_at=  explode("-", $created_at);
        $year=$created_at[0];
        $month=$created_at[1];
        TaiKhoanACB::insert_or_update_init_next_month();
//        TaiKhoanACB::update_records($month, $year);
        
        /**
         * lưu chi tiết hóa đơn
         */
        for ($i = 0; $i < count($goods_id); $i++) {
            $bill_detail_model = new BillDetail();
            $bill_detail_model->setIsNewRecord(true);
            $bill_detail_model->goods_id = $goods_id[$i];
            $bill_detail_model->quantity = $quantity[$i];
            $bill_detail_model->price = $price_not_tax[$i];
            $bill_detail_model->price_has_tax = $price_has_tax[$i];
            $bill_detail_model->bill_id = 0;
            $bill_detail_model->thuchi_id=$model->id;
            $bill_detail_model->save(FALSE);      
            
        } 
        
        
        $kho_hang = '';
        $rows = Yii::app()->db->createCommand()->select("goods.goods_short_hand_name,bill_detail.quantity")->from("bill_detail")->join("goods", "goods.id=bill_detail.goods_id")->where("thuchi_id=" . $model->id)->queryAll();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $value) {
                $kho_hang.=". " . $value['goods_short_hand_name'] . " (" . $value['quantity'] . ")\n";
            }
        }
        
        $model=  TaiKhoanACB::model()->findByPk($model->id);
        $model->kho_hang=$kho_hang;
        $model->thu=  str_replace(".", "", $model->thu);
        $model->tm=  str_replace(".", "", $model->tm);
        $model->save(FALSE);
        /**
         * ghi log
         */
        $thuchi_history_model=new TaiKhoanACBHistory();
        $thuchi_history_model->thuchi_id=$model->id;
        $thuchi_history_model->created_at=$model->created_at;
        $thuchi_history_model->thu=$model->thu;
        $thuchi_history_model->chi=0;
        $thuchi_history_model->tm=$model->tm;
        $thuchi_history_model->type=$model->type;
        $thuchi_history_model->content=$model->content;
        $thuchi_history_model->log_date=date("Y-m-d H:i:s");
        $thuchi_history_model->user_id=Yii::app()->session['user_id'];
        $thuchi_history_model->kho_hang=$model->kho_hang;
        $thuchi_history_model->save(false);
    }
    public function actionUpdategoods() {  
        
        $params = array();
        /**
         * 
         */
        if (Yii::app()->request->isPostRequest) {
            $this->updategoods();
            $this->redirect(array("/taikhoanacb/index"));
        }
        $id=Yii::app()->request->getParam("id",'0');
        //
        $params['invoicefull_model'] = TaiKhoanACB::model()->findByAttributes(array('id'=>$id));
        /**
         * 
         */         
        $params['bill_details']= $bill_details= BillDetailFull::model()->findAllByAttributes(array('thuchi_id'=>Yii::app()->request->getParam("id")));
        $sum=$tax_sum=0;
        foreach ($bill_details as $bill_detail) {
            $sum+=str_replace(".", "", $bill_detail->sum);
            $tax_sum+=str_replace(".", "",$bill_detail->sum_tax);
        }
        $sum_all=$sum+$tax_sum;
        $sum=  number_format($sum, 0, ",", ".");
        $tax_sum=  number_format($tax_sum, 0, ",", ".");
        $sum_all=  number_format($sum_all, 0, ",", ".");
        $params['sum']=$sum; 
        $params['tax_sum']=$tax_sum; 
        $params['sum_all']=$sum_all; 
        /**
         * 
         */        
        $goods = Goods::getAllGoods();        
        $params['goods'] = $goods;
        /**
         * 
         */
        $min_date=Yii::app()->db->createCommand("select max(created_at) as max from tai_khoan_acb where is_init=1 AND MONTH(created_at)=".date("m")." AND YEAR(created_at)=".date("Y"))->queryScalar();
        $min_date=  explode(" ", $min_date);
        $min_date=$min_date[0];
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') { 
            $min_date = implode(".", explode("-", $min_date));
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            $min_date = implode("-", explode("-", $min_date));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $min_date = implode("/", explode("-", $min_date));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $min_date = implode("", explode("-", $min_date));
        }
        $params['min_date']=$min_date;
        $this->render('updategoods', $params);
    }
    protected function updategoods() {
        /**
         * get parameter (GET/POST)
         */
        $goods_id = Yii::app()->request->getParam("goods_id");
        $price_not_tax = Yii::app()->request->getParam("price_not_tax");
        $price_has_tax = Yii::app()->request->getParam("price_has_tax");
        $quantity = Yii::app()->request->getParam("quantity");
        $sum = str_replace(".", "", Yii::app()->request->getParam("sum",""));
        $tax_sum = str_replace(".", "", Yii::app()->request->getParam("tax_sum",""));
        $created_at =  FunctionCommon::convertDateForDB(Yii::app()->request->getParam("created_at")).date(" H:i:s");
        
        /**
         * lưu hóa đơn
         */
        $tien=$sum+$tax_sum;
        
        $model=TaiKhoanACB::model()->findByPk(Yii::app()->request->getParam("id"));
        $model->content=Yii::app()->request->getParam("content");

        $model->created_at =$created_at;
        $model->chi = 0;
        $temp = $tien;
        $temp1 = intval($temp / 1000);
        if ($temp % 1000 >= 500) {
            $temp1++;
        }

        $model->thu = $temp1;
        
        $model->type = Yii::app()->request->getParam("type");
        if($model->type==TaiKhoanACB::CHUYEN_KHOAN){
            $model->chuyen_khoan=1;
            $model->khac=0;
        }
        else if($model->type==TaiKhoanACB::OTHER){
            $model->chuyen_khoan=0;
            $model->khac=1;
        }
        else{
            $model->chuyen_khoan=0;
            $model->khac=0;
        }
        $model->is_init = 0;
        $model->tm = 0;
        $model->bill_chi_phi_id = NULL;
        $model->bill_id = NULL;
        $model->bill_input_id = NULL;
        $model->is_lock=0;
        $model->save(FALSE);
        /**
         * update tat ca các record tai_khoan_acb trong tháng hiện tại
         */
        $created_at=  explode(" ", $model->created_at);
        $created_at=$created_at[0];
        $created_at=  explode("-", $created_at);
        $year=$created_at[0];
        $month=$created_at[1];
        TaiKhoanACB::insert_or_update_init_next_month();
//        TaiKhoanACB::update_records($month, $year);
        /**
         * xóa thông tin chi tiết hóa đơn cũ trước khi lưu thông tin mới         * 
         */
        foreach (BillDetail::model()->findAll("thuchi_id=" . $model->id) as $bill_detail_model) {
            $bill_detail_model->delete();
        }
        /**
         * lưu chi tiết hóa đơn
         */
        for ($i = 0; $i < count($goods_id); $i++) {
            $bill_detail_model = new BillDetail();
            $bill_detail_model->setIsNewRecord(true);
            $bill_detail_model->goods_id = $goods_id[$i];
            $bill_detail_model->quantity = $quantity[$i];
            $bill_detail_model->price = $price_not_tax[$i];
            $bill_detail_model->price_has_tax = $price_has_tax[$i];
            $bill_detail_model->bill_id = 0;
            $bill_detail_model->thuchi_id=$model->id;
            $bill_detail_model->save(FALSE);
        }
        
        $kho_hang = '';
        $rows = Yii::app()->db->createCommand()->select("goods.goods_short_hand_name,bill_detail.quantity")->from("bill_detail")->join("goods", "goods.id=bill_detail.goods_id")->where("thuchi_id=" . $model->id)->queryAll();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $value) {
                $kho_hang.=". " . $value['goods_short_hand_name'] . " (" . $value['quantity'] . ")\n";
            }
        }
        $model=  TaiKhoanACB::model()->findByPk($model->id);
        $model->kho_hang=$kho_hang;
        $model->thu=  str_replace(".", "", $model->thu);
        $model->tm=  str_replace(".", "", $model->tm);
        $model->save(FALSE);
        /**
         * ghi log
         */
        $thuchi_history_model=new TaiKhoanACBHistory();
        $thuchi_history_model->thuchi_id=$model->id;
        $thuchi_history_model->created_at=$model->created_at;
        $thuchi_history_model->thu=$model->thu;
        $thuchi_history_model->chi=0;
        $thuchi_history_model->tm=$model->tm;
        $thuchi_history_model->type=$model->type;
        $thuchi_history_model->content=$model->content;
        $thuchi_history_model->log_date=date("Y-m-d H:i:s");
        $thuchi_history_model->user_id=Yii::app()->session['user_id'];
        $thuchi_history_model->kho_hang=$model->kho_hang;
        $thuchi_history_model->save(false);
    }
    public function actionSave() { 
        $thuchi=Yii::app()->request->getParam("thuchi");
        $tien=Yii::app()->request->getParam("tien");
        $id=Yii::app()->request->getParam("id","");
        if($id!=""){
            $model=  TaiKhoanACB::model()->findByPk($id);
        }
        else{
            $model=new TaiKhoanACB();        
            $model->setIsNewRecord(true);
        }
        
        $model->content=Yii::app()->request->getParam("content");

        $model->created_at =FunctionCommon::convertDateForDB(Yii::app()->request->getParam("created_at")).date(" H:i:s");
        if($thuchi=='1'){
            $model->chi = 0;
            $model->thu = $tien;
        }
        else{
            $model->chi = $tien;
            $model->thu = 0;
        }
        
        $model->type = Yii::app()->request->getParam("type");
        $model->is_init = 0;
        $model->tm = 0;
        $model->bill_chi_phi_id = NULL;
        $model->bill_id = NULL;
        $model->bill_input_id = NULL;
        if($model->type==TaiKhoanACB::CHUYEN_KHOAN){
            $model->chuyen_khoan=1;
            $model->khac=0;
        }
        else if($model->type==TaiKhoanACB::OTHER){
            $model->chuyen_khoan=0;
            $model->khac=1;
        }
        else{
            $model->chuyen_khoan=0;
            $model->khac=0;
        }
        $model->is_lock=0;
        $model->save(FALSE);
        
        /**
         * update tat ca các record thuchi trong tháng hiện tại
         */
        $created_at=  explode(" ", $model->created_at);
        $created_at=$created_at[0];
        $created_at=  explode("-", $created_at);
        $year=$created_at[0];
        $month=$created_at[1];
        TaiKhoanACB::insert_or_update_init_next_month();
//        TaiKhoanACB::update_records($month, $year);
        /**
         * ghi log
         */
        $thuchi_history_model=new TaiKhoanACBHistory();
        $thuchi_history_model->thuchi_id=$model->id;
        $thuchi_history_model->created_at=$model->created_at;
        $thuchi_history_model->thu=$model->thu;
        $thuchi_history_model->chi=$model->chi;
        $thuchi_history_model->tm=$model->tm;
        $thuchi_history_model->type=$model->type;
        $thuchi_history_model->content=$model->content;
        $thuchi_history_model->log_date=date("Y-m-d H:i:s");
        $thuchi_history_model->user_id=Yii::app()->session['user_id'];
        $thuchi_history_model->save(false);
        
        
    }
    
    public function actionEditinit() { 
        $tienmat=Yii::app()->request->getParam("tienmat");
        $id=Yii::app()->request->getParam("id","");
        $model=  TaiKhoanACB::model()->findByPk($id);  
        $model->tm =  str_replace(".", "", $tienmat);
        $model->save(FALSE);


        /**
         * update tat ca các record tai_khoan_acb trong tháng hiện tại
         */
        $model->created_at=Yii::app()->db->createCommand()->select("created_at")->from("tai_khoan_acb")->where("id=$id")->queryScalar();
        $created_at=  explode(" ", $model->created_at);
        $created_at=$created_at[0];
        $created_at=  explode("-", $created_at);
        $year=$created_at[0];
        $month=$created_at[1];
        
        TaiKhoanACB::insert_or_update_init_next_month();
        
//        TaiKhoanACB::update_records($month, $year);
        /**
         * ghi log
         */
        $thuchi_history_model=new TaiKhoanACBHistory();
        $thuchi_history_model->thuchi_id=$model->id;
        $thuchi_history_model->created_at=$model->created_at;
        $thuchi_history_model->thu=0;
        $thuchi_history_model->chi=0;
        $thuchi_history_model->tm=$model->tm;
        $thuchi_history_model->type=$model->type;
        $thuchi_history_model->content=$model->content;
        $thuchi_history_model->log_date=date("Y-m-d H:i:s");
        $thuchi_history_model->user_id=Yii::app()->session['user_id'];
        $thuchi_history_model->save(false);
        
        Yii::app()->end();
        
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
        $where.=" and (is_init is null or is_init <> 1)";
        $argPage       = (int) Yii::app()->request->getQuery('page', 0);
        $dbCriteria             = new CDbCriteria;
        $dbCriteria->condition=$where;
        $dbCriteria->limit      = Yii::app()->params['number_of_items_per_page'];
        $dbCriteria->offset     = $argPage * $dbCriteria->limit;
        //        
        $items = TaiKhoanACB::model()->findAll($dbCriteria);
        if(!is_array($items)||count($items)==0){
            echo '';
            Yii::app()->end();
        }
        $params['index']=$argPage*Yii::app()->params['number_of_items_per_page'];
        $params['items'] = $items;  
        $this->renderPartial('//render_partial/common/more', $params);
    }
    
    public function actionIndex() { 
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') {
            $temp= explode(".", $this->start_date_common);
            $month=$temp[1];
            $year=$temp[0];
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            $temp= explode("-", $this->start_date_common);
            $month=$temp[1];
            $year=$temp[0];
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $temp= explode("/", $this->start_date_common);
            $month=$temp[1];
            $year=$temp[0];
        } elseif ($DATE_FORMAT == 'Ymd') {
            $year=substr($this->start_date_common, 0,4);
            $month=substr($this->start_date_common, 4,2);
        } 
        
        Yii::app()->session['tm']= Yii::app()->db->createCommand()->select("tm")->from("tai_khoan_acb")->where("MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_init=1")->order("created_at DESC")->queryScalar();
        $params = array();
        /**
         * 
         */
        if($this->all_time_common=='1'){
            $where = "id not in (select max(id) from tai_khoan_acb where is_init=1)";              
        }
        else{
            $where = "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
            $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";
        }
        
        $where.=" and (is_init is null or is_init <> 1)";
        
        
        $items = TaiKhoanACB::model()->findAll($where);
        $params['items'] = $items;
        if($where == "id not in (select max(id) from tai_khoan_acb where is_init=1)"){
            $count=Yii::app()->db->createCommand()->select("count(*) as count")->from("tai_khoan_acb")->queryScalar();
        }
        else{
            $count=Yii::app()->db->createCommand()->select("count(*) as count")->from("tai_khoan_acb")->where($where)->queryScalar();
        }

        $params['count'] = number_format($count, 0, ",", ".");
        $row=Yii::app()->db->createCommand()->select("sum(thu) as sum_thu,sum(chi) as sum_chi")->from("tai_khoan_acb")->where($where)->queryRow();
        $params['sum_thu']=$row['sum_thu'];
        $params['sum_chi']=$row['sum_chi'];
        $params['index']=0;
        /**
         * 
         */
        $params['start_date_common'] = Yii::app()->session['start_date_common'];
        $params['end_date_common'] = Yii::app()->session['end_date_common'];
        $params['all_time_common'] = Yii::app()->session['all_time_common'];  
        /**
         * 
         */
        $min_date=Yii::app()->db->createCommand("select max(created_at) as max from tai_khoan_acb where is_init=1 AND MONTH(created_at)=".date("m")." AND YEAR(created_at)=".date("Y"))->queryScalar();
        $min_date=  explode(" ", $min_date);
        $min_date=$min_date[0];
        $DATE_FORMAT = Yii::app()->session['date_format'];
        if ($DATE_FORMAT == 'Y.m.d') { 
            $min_date = implode(".", explode("-", $min_date));
        } elseif ($DATE_FORMAT == 'Y-m-d') {
            $min_date = implode("-", explode("-", $min_date));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $min_date = implode("/", explode("-", $min_date));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $min_date = implode("", explode("-", $min_date));
        }
        $params['min_date']=$min_date;
        $this->render('index', $params);
    }

    

}
