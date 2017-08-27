<?php

class SocaiController extends Controller {

    public function init() {
        parent::init();
    }

//    public function actionGethistory() { 
//        $params = array();
//        $params['items']=  ThuchiHistory::model()->findAll("thuchi_id=".Yii::app()->request->getParam("thuchi_id"));     
//        $this->renderPartial('gethistory', $params);
//    }

    public function actionGethistory() {
        $params = array();
        $params['items'] = SocaiHistory::model()->findAll("socai_id=" . Yii::app()->request->getParam("thuchi_id"));
        $this->renderPartial('gethistory', $params);
    }

    public function actionSave() {
        Yii::app()->db->beginTransaction();
        $success=true;
        
        $thuchi = Yii::app()->request->getParam("thuchi");
        $tien = Yii::app()->request->getParam("tien");
        $content = Yii::app()->request->getParam("content");
        $giao_dich = Yii::app()->request->getParam("giao_dich");
        $thanh_toan = Yii::app()->request->getParam("thanh_toan");

        $tien = str_replace(".", "", $tien);
        $id = Yii::app()->request->getParam("id", "");
        if ($id != "") {
            $is_new_record = FALSE;
            $model = Socai::model()->findByPk($id);
            $model->is_tamung = 0;
        } else {
            $model = new Socai();
            $model->setIsNewRecord(true);
            $model->is_tamung = 1;
            $is_new_record = true;
        }
        if (
                trim($tien) == "" || trim($content) == "" || trim($giao_dich) == "" || trim($thanh_toan) == ""
        ) {
            Yii::app()->end();
        }


        $model->created_at = FunctionCommon::convertDateForDB(Yii::app()->request->getParam("created_at")) . date(" H:i:s");
        if ($thuchi == '1') {
            $model->chi = 0;
            $model->thu = $tien;
        } else {
            $model->chi = $tien;
            $model->thu = 0;
        }

        $model->tm = 0;
        $model->content = $content;
        $model->giao_dich = $giao_dich;
        $model->thanh_toan = $thanh_toan;
        $model->payment_method_to_khai = Yii::app()->request->getParam("to_khai", NULL);

        if($model->save(FALSE)==FALSE){
            $success=FALSE;
        }

        if($id != ""&&($model->success_after_save==FALSE||$model->success_before_save==FALSE)){
            
            $success=FALSE;
        }
        /**
         * ghi log
         */
        if($this->write_log($model)==FALSE){
                            
            $success=FALSE;
        }

        if ($is_new_record == true) {
            /**
             * thêm 1 record qua tiền mặt/tài khoản ACB nếu tạm ứng này dc chọn là phương thức tiền mặt/tài khoản ACB
             */
            if($this->insert($thanh_toan, $model, $thuchi, $tien,$giao_dich)==FALSE){
                $success=FALSE;
            }
        } else {
            if($this->insert_or_update($thanh_toan, $model, $thuchi, $tien,$giao_dich)==FALSE){
                $success=FALSE;
            }
        }
        
        if($success==FALSE){
            Yii::app()->db->getCurrentTransaction()->rollback();
            
            echo 'Đường truyền bị lỗi. Vui lòng làm lại.';
            Yii::app()->end();
        }
        else{
            Yii::app()->db->getCurrentTransaction()->commit();
        }
    }
    /**
     * ghi log cho sổ cái
     */
    protected function write_log($model){
        $thuchi_history_model = new SocaiHistory();
        $thuchi_history_model->socai_id = $model->id;
        $thuchi_history_model->created_at = $model->created_at;
        $thuchi_history_model->thu = $model->thu;
        $thuchi_history_model->chi = $model->chi;
        $thuchi_history_model->tm = $model->tm;
        $thuchi_history_model->type = $model->thanh_toan;
        $thuchi_history_model->content = $model->content;
        $thuchi_history_model->log_date = date("Y-m-d H:i:s");
        $thuchi_history_model->user_id = Yii::app()->session['user_id'];
        return $thuchi_history_model->save(false);
    }

    /**
     * thêm 1 record qua tiền mặt/tài khoản ACB nếu tạm ứng này dc chọn là phương thức tiền mặt/tài khoản ACB
     */
    public function insert($thanh_toan, $model, $thuchi, $tien,$giao_dich) {
        
        if ($thanh_toan == PaymentMethod::TIEN_MAT) {
            $model1 = new ThuChi();
        } else if ($thanh_toan == PaymentMethod::CHUYEN_KHOAN_ACB) {
            $model1 = new TaiKhoanACB();            
        } else {
            return true;
        }
        if (isset($model1)&&$model1 != NULL) {
            $model1->setIsNewRecord(true);
            $model1->created_at = date("Y-m-d H:i:s");
            if ($thuchi == '1') {
                $model1->chi = 0;
                $model1->thu = $tien;
            } else {
                $model1->chi = $tien;
                $model1->thu = 0;
            }
            $model1->tm = 0;
            $model1->content = "Tạm ứng\n$giao_dich";
            if (Yii::app()->request->getParam("to_khai", NULL) != NULL) {
                $model1->content .="\n" . Yii::app()->request->getParam("to_khai", NULL);
            }
            $model1->is_init=$model1->is_lock=$model1->chuyen_khoan = $model1->khac = 0;
            $model1->socai_id = $model->id;
            if ($thanh_toan == PaymentMethod::TIEN_MAT) {
                $model1->type = ThuChi::TIEN_MAT;
            } else {
                $model1->type = TaiKhoanACB::CHUYEN_KHOAN;
            }
            if($model1->save(FALSE)==FALSE){
                return FALSE;
            }
            if ($thanh_toan == PaymentMethod::TIEN_MAT) {
                return Thuchi::insert_or_update_init_next_month();
            } else if ($thanh_toan == PaymentMethod::CHUYEN_KHOAN_ACB) {
                return TaiKhoanACB::insert_or_update_init_next_month();
            }
            
        }
        
        
        return true;
    }

    
    public function insert_or_update($thanh_toan, $model, $thuchi, $tien,$giao_dich) {
        
        if ($thanh_toan == PaymentMethod::TIEN_MAT) {
            $model1 = ThuChi::model()->findByAttributes(array('socai_id' => $model->id));
            $num=TaiKhoanACB::model()->deleteAllByAttributes(array('socai_id' => $model->id));
            if($num!=0){
                if(TaiKhoanACB::insert_or_update_init_next_month()==FALSE){
                    return FALSE;
                }
            }
        } else if ($thanh_toan == PaymentMethod::CHUYEN_KHOAN_ACB) {
            $model1 = TaiKhoanACB::model()->findByAttributes(array('socai_id' => $model->id));
            $num=ThuChi::model()->deleteAllByAttributes(array('socai_id' => $model->id));
            if($num!=0){
                if(ThuChi::insert_or_update_init_next_month()==FALSE){
                    return FALSE;
                }
            }
        } else {
            $num=TaiKhoanACB::model()->deleteAllByAttributes(array('socai_id' => $model->id));
            if($num!=0){
                if(TaiKhoanACB::insert_or_update_init_next_month()==FALSE){
                    return FALSE;
                }
            }
            $num=ThuChi::model()->deleteAllByAttributes(array('socai_id' => $model->id));
            if($num!=0){
                if(ThuChi::insert_or_update_init_next_month()==FALSE){
                    return FALSE;
                }
            }                   
            return true;
        }
        if (isset($model1)&&$model1 != NULL) {//nếu đã tồn tại record bên tiền mặt/tài khoản ACB
            if ($thuchi == '1') {
                $model1->chi = 0;
                $model1->thu = $tien;
            } else {
                $model1->chi = $tien;
                $model1->thu = 0;
            }
            $model1->tm = 0;
            if($model1->payment_method_id3!=null&&$model1->payment_method_id3!=""){
                $model1->content="Giá trị hàng hóa (VND) tờ khai: \n".$model->content;
            }
            else if($model1->payment_method_id4!=null&&$model1->payment_method_id4!=""){
                $model1->content="Chi phí ngân hàng (VND) tờ khai: \n".$model->content;
            }
            else if($model1->payment_method_id5!=null&&$model1->payment_method_id5!=""){
                $model1->content="Tiền thuế (VND) tờ khai: \n".$model->content;
            }
            if($model1->save(FALSE)==false){
                return false;
            }
            
            
        } else {//nếu chưa tồn tại record bên tiền mặt/tài khoản ACB thi insert vào
            if ($thanh_toan == PaymentMethod::TIEN_MAT) {
                $model1 = new ThuChi();
            } else if ($thanh_toan == PaymentMethod::CHUYEN_KHOAN_ACB) {
                $model1 = new TaiKhoanACB();
            } 
            $model1->setIsNewRecord(true);
            $model1->created_at = date("Y-m-d H:i:s");
            if ($thuchi == '1') {
                $model1->chi = 0;
                $model1->thu = $tien;
            } else {
                $model1->chi = $tien;
                $model1->thu = 0;
            }
            $model1->tm = 0;
            if (strpos($model->trang_thai, "tam_ung.png") !== FALSE) {//nếu record này là tạm ứng
                $model1->content = "Tạm ứng\n$giao_dich";
                if (Yii::app()->request->getParam("to_khai", NULL) != NULL) {
                    $model1->content .="\n" . Yii::app()->request->getParam("to_khai", NULL);
                }
            } else {//nếu record này không phải là tạm ứng
                $model1->bill_id = $model->bill_id;
                $model1->bill_input_id = $model->bill_input_id;
                $model1->bill_chi_phi_id = $model->bill_chi_phi_id;
                $model1->sxdv_id = $model->sxdv_id;
                $model1->kxhd_id = $model->kxhd_id;
                $model1->lai_suat_id = $model->lai_suat_id;
                $model1->chi_phi_khd_id = $model->chi_phi_khd_id;
                $model1->payment_method_id3 = $model->payment_method_id3;
                $model1->payment_method_id4 = $model->payment_method_id4;
                $model1->payment_method_id5 = $model->payment_method_id5;
                $model1->content = $model->content;
                if($model1->payment_method_id3!=null&&$model1->payment_method_id3!=""){
                    $model1->content="Giá trị hàng hóa (VND) tờ khai: \n".$model->content;
                }
                else if($model1->payment_method_id4!=null&&$model1->payment_method_id4!=""){
                    $model1->content="Chi phí ngân hàng (VND) tờ khai: \n".$model->content;
                }
                else if($model1->payment_method_id5!=null&&$model1->payment_method_id5!=""){
                    $model1->content="Tiền thuế (VND) tờ khai: \n".$model->content;
                }
            }
            $model1->is_init=$model1->is_lock=$model1->chuyen_khoan = $model1->khac = 0;
            $model1->socai_id = $model->id;
            if ($thanh_toan == PaymentMethod::TIEN_MAT) {
                $model1->type = ThuChi::TIEN_MAT;
            } else {
                $model1->type = TaiKhoanACB::CHUYEN_KHOAN;
            }

            if($model1->save(FALSE)==false){
                return false;
            }
               
        }
        
        if ($thanh_toan == PaymentMethod::TIEN_MAT) {
            return ThuChi::insert_or_update_init_next_month();
        } else if ($thanh_toan == PaymentMethod::CHUYEN_KHOAN_ACB) {
            return TaiKhoanACB::insert_or_update_init_next_month();
        }
        
        
        return true;
    }

    public function actionMore() {
        $params = array();
        /**
         * 
         */
        if ($this->all_time_common == '1') {
            $where = "1=1";
        } else {
            $where = "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
            $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";
        }
        $argPage = (int) Yii::app()->request->getQuery('page', 0);
        $dbCriteria = new CDbCriteria;
        $dbCriteria->condition = $where;
        $dbCriteria->limit = Yii::app()->params['number_of_items_per_page'];
        $dbCriteria->offset = $argPage * $dbCriteria->limit;
        //        
        $items = SocaiFull::model()->findAll($dbCriteria);
        if (!is_array($items) || count($items) == 0) {
            echo '';
            Yii::app()->end();
        }
        $params['index'] = $argPage * Yii::app()->params['number_of_items_per_page'];
        $params['items'] = $items;
        $this->renderPartial('//render_partial/common/more', $params);
    }

    public function actionIndex() {
 
        Yii::app()->session['tm']='0';
        /**
         * hiển thị danh sách sổ cái, luôn cho những record chưa thanh toán hoặc chưa hoàn thành nằm dưới cùng
         * do đó trước khi hiển thị danh sách, phai update created_at các record đó thành ngày giờ lớn nhất trong tat cả các record
         */
        Yii::app()->db->createCommand("update socai set created_at='" . date("Y-m-d") . " 23:59:59" . "' where thu=0 and chi=0 and MONTH(created_at)=" . date("m") . " and YEAR(created_at)=" . date("Y"))->execute();
        $params = array();
        /**
         * 
         */
        if ($this->all_time_common == '1') {
            $where = "1=1";
        } else {
            $where = "date(created_at) >= '" . FunctionCommon::convertDateForDB($this->start_date_common) . "'";
            $where.=" and date(created_at) <= '" . FunctionCommon::convertDateForDB($this->end_date_common) . "'";
        }


        $items = SocaiFull::model()->findAll($where);

        $params['items'] = $items;
        $count = Yii::app()->db->createCommand()->select("count(*) as count")->from("socai_full_view")->where($where)->queryScalar();

        $params['count'] = number_format($count, 0, ",", ".");
        $row = Yii::app()->db->createCommand()->select("sum(thu) as sum_thu,sum(chi) as sum_chi")->from("socai")->where($where." and thanh_toan<>". PaymentMethod::KHONG_THANH_TOAN)->queryRow();
        $params['sum_thu'] = $row['sum_thu'];
        $params['sum_chi'] = $row['sum_chi'];
        $params['index'] = 0;
        /**
         * 
         */
        $params['start_date_common'] = Yii::app()->session['start_date_common'];
        $params['end_date_common'] = Yii::app()->session['end_date_common'];
        $params['all_time_common'] = Yii::app()->session['all_time_common'];

        $this->render('index', $params);
    }

}
