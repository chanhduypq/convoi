<?php

/**
 * 
 */
class BillInput extends CActiveRecord {

    private $old_branch_id;
    public $thu_chi=0;
    public $thu_chi3=0;
    public $thu_chi4=0;
    public $thu_chi5=0;
    public $success=false;

    /**
     * Returns the static model of the specified AR class.
     * @return Products the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'bill_input';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return array(
            array('bill_number', 'unique', 'criteria' => array(
                    'condition' => '`is_international`=:is_international',
                    'params' => array(
                        ':is_international' => $this->is_international
                    )
                ),
                'message' => 'Đã tồn tại Số hóa đơn này rồi. Vui lòng nhập lại.'
            ),
        );
    }

    /**
     * 
     */
    public function beforeSave() {
        $this->sum_and_sumtax = $this->sum + $this->tax_sum;
        $this->created_at = FunctionCommon::convertDateForDB($this->created_at) . date(" H:i:s");
        if ($this->getIsNewRecord()) {
            $this->user_id = Yii::app()->session['user_id'];
            $this->last_updated_at = $this->created_at;
        } else {
            $this->last_updated_at = date("Y-m-d H:i:s");
            $model = $this->findByPk($this->id);
            $this->old_branch_id = $model->branch_id;
        }


        return parent::beforeSave();
    }
    /**
     * ghi log cho viec update
     */
    protected function write_log_for_update($tienmat_or_chuyenkhoan,$model,$row){
        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            $thuchi_history_model = new ThuchiHistory();
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            $thuchi_history_model = new TaiKhoanACBHistory();
        }

        $thuchi_history_model->thuchi_id = $model->id;
        $thuchi_history_model->created_at = $model->created_at;
        $thuchi_history_model->thu = $model->thu;
        $thuchi_history_model->chi = $model->chi;
        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            $tm = Yii::app()->db->createCommand("select tm from thuchi where id=" . $row['id'])->queryScalar();
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            $tm = Yii::app()->db->createCommand("select tm from tai_khoan_acb where id=" . $row['id'])->queryScalar();
        }

        $thuchi_history_model->tm = $tm;
        $thuchi_history_model->type = $tienmat_or_chuyenkhoan;
        $thuchi_history_model->log_date = date("Y-m-d H:i:s");
        $thuchi_history_model->user_id = Yii::app()->session['user_id'];
        $thuchi_history_model->content = $this->description;
        $thuchi_history_model->save(false);
    }
    /**
     * ghi log cho viec tạo mới
     */
    protected function write_log_for_insert($tienmat_or_chuyenkhoan,$model){
        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            $thuchi_history_model = new ThuchiHistory();
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            $thuchi_history_model = new TaiKhoanACBHistory();
        }

        $thuchi_history_model->thuchi_id = $model->id;
        $thuchi_history_model->created_at = $model->created_at;
        $thuchi_history_model->thu = $model->thu;
        $thuchi_history_model->chi = $model->chi;
        $thuchi_history_model->tm = $model->tm;
        $thuchi_history_model->type = $tienmat_or_chuyenkhoan;
        $thuchi_history_model->log_date = date("Y-m-d H:i:s");
        $thuchi_history_model->user_id = Yii::app()->session['user_id'];
        $thuchi_history_model->content = $this->description;
        $thuchi_history_model->save(false);
    }

    /**
     * 
     * insert 1 record bên thuchi hoặc tài khoản ACB
     */
    public function insert_thuchi($tienmat_or_chuyenkhoan, $payment_method_type = NULL) {
        $user = User::model()->findByPk($this->user_id);
        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            $tm = Yii::app()->db->createCommand()->select("tm")->from("thuchi")->order("created_at DESC")->queryScalar();
            $model = new ThuChi();
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            $tm = Yii::app()->db->createCommand()->select("tm")->from("tai_khoan_acb")->order("created_at DESC")->queryScalar();
            $model = new TaiKhoanACB();
        }


        $model->setIsNewRecord(true);
        
        $date = explode(" ", $this->created_at);
        $date = $date[0];
        $date = implode(".", explode("-", $date));
        if ($this->is_international == "" || $this->is_international == "0") {
            $model->content = $user->danh_xung . " " . $user->full_name . " chi tiền mua hàng hóa đơn " . $this->bill_number . " ($date)";
        } else {
            if ($payment_method_type == '5') {
                $model->content = $user->danh_xung . " " . $user->full_name . " đóng thuế tờ khai " . $this->bill_number . " ($date)";
            } else if ($payment_method_type == '3') {
                $model->content = $user->danh_xung . " " . $user->full_name . " đóng tiền giá trị hàng hóa tờ khai " . $this->bill_number . " ($date)";
            } else if ($payment_method_type == '4') {
                $model->content = $user->danh_xung . " " . $user->full_name . " đóng tiền chi phí ngân hàng tờ khai " . $this->bill_number . " ($date)";
            }
        }


        $model->created_at = date("Y-m-d H:i:s");

        if ($this->is_international == "" || $this->is_international == "0") {
            $model->chi = $this->thu_chi;
        } else {



            if ($payment_method_type == '5') {
                if ($this->tien_thue_vnd == null || !is_numeric($this->tien_thue_vnd)) {
                    $temp = 0;
                } else {
                    $temp = $this->tien_thue_vnd;
                }
                $model->payment_method_id5 = $this->payment_method_id5;
            } else if ($payment_method_type == '3') {
                if ($this->gia_tri_hang_hoa_vnd == null || !is_numeric($this->gia_tri_hang_hoa_vnd)) {
                    $temp = 0;
                } else {
                    $temp = $this->gia_tri_hang_hoa_vnd;
                }
                $model->payment_method_id3 = $this->payment_method_id3;
            } else if ($payment_method_type == '4') {
                if ($this->chi_phi_ngan_hang_vnd == null || !is_numeric($this->chi_phi_ngan_hang_vnd)) {
                    $temp = 0;
                } else {
                    $temp = $this->chi_phi_ngan_hang_vnd;
                }
                $model->payment_method_id4 = $this->payment_method_id4;
            }

            $model->chi = $this->thu_chi;
        }

        $model->thu = 0;
        $model->is_init = 0;
        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            $model->type = ThuChi::TIEN_MAT;
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            $model->type = ThuChi::CHUYEN_KHOAN;
        }
        if ($this->is_international == "" || $this->is_international == "0") {
            $model->tm = $tm - $this->sum_and_sumtax;
        } else {
            $model->tm = $tm - $temp;
        }

        $model->bill_input_id = $this->id;
        $model->chuyen_khoan = $model->khac = 0;
        $model->is_lock = 0;
        $model->save(FALSE);

        $created_at = explode(" ", $this->created_at);

        $created_at = $created_at[0];
        $created_at = explode("-", $created_at);
        $year = $created_at[0];
        $month = $created_at[1];
        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            ThuChi::insert_or_update_init_next_month();
//            ThuChi::update_records($month, $year);
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            TaiKhoanACB::insert_or_update_init_next_month();
//            TaiKhoanACB::update_records($month, $year);
        }

        /**
         * ghi log
         */
        $this->write_log_for_insert($tienmat_or_chuyenkhoan, $model);
        
    }

    /**
     * 
     * update record bên thuchi hoặc tài khoản ACB
     */
    public function update_thuchi($tienmat_or_chuyenkhoan, $payment_method_type = NULL) {
        if ($payment_method_type == '3') {
            $and_where = " AND payment_method_id3=" . $this->payment_method_id3;
        } else if ($payment_method_type == '4') {
            $and_where = " AND payment_method_id4=" . $this->payment_method_id4;
        } else if ($payment_method_type == '5') {
            $and_where = " AND payment_method_id5=" . $this->payment_method_id5;
        } else {
            $and_where = '';
        }

        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            $row = Yii::app()->db->createCommand()->select()->from("thuchi")->where("bill_input_id=" . $this->id . $and_where)->queryRow();
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            $row = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("bill_input_id=" . $this->id . $and_where)->queryRow();
        }

        if (!is_array($row) || count($row) == 0 || $row['is_lock'] == '1') {
            return;
        }
        $user = User::model()->findByPk($this->user_id);

        $created_at = explode(" ", $row['created_at']);
        $created_at = $created_at[0];
        $created_at = explode("-", $created_at);
        $month = $created_at[1];
        $year = $created_at[0];

        $date = explode(" ", $this->created_at);
        $date = $date[0];
        $date = implode(".", explode("-", $date));
        if ($this->is_international == "" || $this->is_international == "0") {
            $content = $user->danh_xung . " " . $user->full_name . " chi tiền mua hàng hóa đơn " . $this->bill_number . " ($date)";
        } else {
            if ($payment_method_type == '5') {
                $content = $user->danh_xung . " " . $user->full_name . " đóng thuế tờ khai " . $this->bill_number . " ($date)";
            } else if ($payment_method_type == '3') {
                $content = $user->danh_xung . " " . $user->full_name . " đóng tiền giá trị hàng hóa tờ khai " . $this->bill_number . " ($date)";
            } else if ($payment_method_type == '4') {
                $content = $user->danh_xung . " " . $user->full_name . " đóng tiền chi phí ngân hàng tờ khai " . $this->bill_number . " ($date)";
            }
        }

        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            $model = ThuChi::model()->findAll('id=' . $row['id'] . $and_where);
            $model = $model[0];
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            $model = TaiKhoanACB::model()->findAll('id=' . $row['id'] . $and_where);
            $model = $model[0];
        }

        if ($this->is_international == "" || $this->is_international == "0") {

            $model->chi=  str_replace(".", "", $model->chi);
        } else {
            if ($payment_method_type == '5') {
                if ($this->tien_thue_vnd == null || !is_numeric($this->tien_thue_vnd)) {
                    $temp = 0;
                } else {
                    $temp = $this->tien_thue_vnd;
                }
            } else if ($payment_method_type == '3') {
                if ($this->gia_tri_hang_hoa_vnd == null || !is_numeric($this->gia_tri_hang_hoa_vnd)) {
                    $temp = 0;
                } else {
                    $temp = $this->gia_tri_hang_hoa_vnd;
                }
            } else if ($payment_method_type == '4') {
                if ($this->chi_phi_ngan_hang_vnd == null || !is_numeric($this->chi_phi_ngan_hang_vnd)) {
                    $temp = 0;
                } else {
                    $temp = $this->chi_phi_ngan_hang_vnd;
                }
            }

            $model->chi=  str_replace(".", "", $model->chi);
        }

        $model->content = $content;
//        $model->created_at = date("Y-m-d H:i:s");
        $model->is_lock = 0;
        $model->save(FALSE);
        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            ThuChi::insert_or_update_init_next_month();
//            ThuChi::update_records($month, $year);
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            TaiKhoanACB::insert_or_update_init_next_month();
//            TaiKhoanACB::update_records($month, $year);
        }

        /**
         * ghi log
         */
        $this->write_log_for_update($tienmat_or_chuyenkhoan, $model, $row);
        
    }

    

    public function delete_thuchi1($tienmat_or_chuyenkhoan, $payment_method_type) {
        if ($payment_method_type == '3') {
            $and_where = " AND payment_method_id3 is not null";
        } else if ($payment_method_type == '4') {
            $and_where = " AND payment_method_id4 is not null";
        } else if ($payment_method_type == '5') {
            $and_where = " AND payment_method_id5 is not null";
        }

        if (!isset($and_where)) {
            return;
        }
        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            $row = Yii::app()->db->createCommand()->select()->from("thuchi")->where("bill_input_id=" . $this->id . $and_where)->queryRow();
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            $row = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("bill_input_id=" . $this->id . $and_where)->queryRow();
        }

        if (!is_array($row) || count($row) == 0 || $row['is_lock'] == '1') {
            return;
        }
        $created_at = explode(" ", $row['created_at']);
        $created_at = $created_at[0];
        $created_at = explode("-", $created_at);
        $month = $created_at[1];
        $year = $created_at[0];
        if ($tienmat_or_chuyenkhoan == Common::TIEN_MAT) {
            ThuChi::model()->deleteAll("id=" . $row['id'] . $and_where);
            ThuChi::insert_or_update_init_next_month();
//            ThuChi::update_records($month, $year);
        } else if ($tienmat_or_chuyenkhoan == Common::CHUYEN_KHOAN_ACB) {
            TaiKhoanACB::model()->deleteAll("id=" . $row['id'] . $and_where);
            TaiKhoanACB::insert_or_update_init_next_month();
//            TaiKhoanACB::update_records($month, $year);
        }
    }

    

    public function process_dong_thue() {

        $row1 = Yii::app()->db->createCommand()->select()->from("thuchi")->where("bill_input_id=" . $this->id." AND payment_method_id5 is not null")->queryRow();
        $row2 = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("bill_input_id=" . $this->id." AND payment_method_id5 is not null")->queryRow();

        if((is_array($row1)&&count($row1)>0)){
            $created_at= explode(" ", $row1['created_at']);
            $created_at=  explode("-", $created_at[0]);        
            if($created_at[1]!=  date("m")||$created_at[0]!=  date("Y")){
                return;
            }
        }
        if((is_array($row2)&&count($row2)>0)){
            $created_at= explode(" ", $row2['created_at']);
            $created_at=  explode("-", $created_at[0]);        
            if($created_at[1]!=  date("m")||$created_at[0]!=  date("Y")){
                return;
            }
        }
        if ($this->payment_method_id5 == PaymentMethod::TIEN_MAT) {


            if ($this->getIsNewRecord()) {
                $this->insert_thuchi(Common::TIEN_MAT, '5');
            } else {
                $row = Yii::app()->db->createCommand()->select()->from("thuchi")->where("bill_input_id=" . $this->id . " AND payment_method_id5=" . $this->payment_method_id5)->queryRow();
                if (!is_array($row) || count($row) == 0) {
                    $this->insert_thuchi(Common::TIEN_MAT, '5');
                } else {
                    $this->update_thuchi(Common::TIEN_MAT, '5');
                }
                $this->delete_thuchi1(Common::CHUYEN_KHOAN_ACB, '5');
            }
        } else if ($this->payment_method_id5 == PaymentMethod::CHUYEN_KHOAN_ACB) {


            if ($this->getIsNewRecord()) {
                $this->insert_thuchi(Common::CHUYEN_KHOAN_ACB, '5');
            } else {
                $row = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("bill_input_id=" . $this->id . " AND payment_method_id5=" . $this->payment_method_id5)->queryRow();
                if (!is_array($row) || count($row) == 0) {
                    $this->insert_thuchi(Common::CHUYEN_KHOAN_ACB, '5');
                } else {
                    $this->update_thuchi(Common::CHUYEN_KHOAN_ACB, '5');
                }
                $this->delete_thuchi1(Common::TIEN_MAT, '5');
            }
        } else {

            $this->delete_thuchi1(Common::TIEN_MAT, '5');
            $this->delete_thuchi1(Common::CHUYEN_KHOAN_ACB, '5');
        }
    }

    public function process_gia_tri_hang_hoa_vnd() {

        $row1 = Yii::app()->db->createCommand()->select()->from("thuchi")->where("bill_input_id=" . $this->id." AND payment_method_id3 is not null")->queryRow();
        $row2 = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("bill_input_id=" . $this->id." AND payment_method_id3 is not null")->queryRow();

        if((is_array($row1)&&count($row1)>0)){
            $created_at= explode(" ", $row1['created_at']);
            $created_at=  explode("-", $created_at[0]);        
            if($created_at[1]!=  date("m")||$created_at[0]!=  date("Y")){
                return;
            }
        }
        if((is_array($row2)&&count($row2)>0)){
            $created_at= explode(" ", $row2['created_at']);
            $created_at=  explode("-", $created_at[0]);        
            if($created_at[1]!=  date("m")||$created_at[0]!=  date("Y")){
                return;
            }
        }
        if ($this->payment_method_id3 == PaymentMethod::TIEN_MAT) {


            if ($this->getIsNewRecord()) {
                $this->insert_thuchi(Common::TIEN_MAT, '3');
            } else {
                $row = Yii::app()->db->createCommand()->select()->from("thuchi")->where("bill_input_id=" . $this->id . " AND payment_method_id3=" . $this->payment_method_id3)->queryRow();
                if (!is_array($row) || count($row) == 0) {
                    $this->insert_thuchi(Common::TIEN_MAT, '3');
                } else {
                    $this->update_thuchi(Common::TIEN_MAT, '3');
                }
                $this->delete_thuchi1(Common::CHUYEN_KHOAN_ACB, '3');
            }
        } else if ($this->payment_method_id3 == PaymentMethod::CHUYEN_KHOAN_ACB) {


            if ($this->getIsNewRecord()) {
                $this->insert_thuchi(Common::CHUYEN_KHOAN_ACB, '3');
            } else {
                $row = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("bill_input_id=" . $this->id . " AND payment_method_id3=" . $this->payment_method_id3)->queryRow();
                if (!is_array($row) || count($row) == 0) {
                    $this->insert_thuchi(Common::CHUYEN_KHOAN_ACB, '3');
                } else {
                    $this->update_thuchi(Common::CHUYEN_KHOAN_ACB, '3');
                }
                $this->delete_thuchi1(Common::TIEN_MAT, '3');
            }
        } else {
            $this->delete_thuchi1(Common::TIEN_MAT, '3');
            $this->delete_thuchi1(Common::CHUYEN_KHOAN_ACB, '3');
        }
    }

    public function process_chi_phi_ngan_hang() {

        $row1 = Yii::app()->db->createCommand()->select()->from("thuchi")->where("bill_input_id=" . $this->id." AND payment_method_id4 is not null")->queryRow();
        $row2 = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("bill_input_id=" . $this->id." AND payment_method_id4 is not null")->queryRow();

        if((is_array($row1)&&count($row1)>0)){
            $created_at= explode(" ", $row1['created_at']);
            $created_at=  explode("-", $created_at[0]);        
            if($created_at[1]!=  date("m")||$created_at[0]!=  date("Y")){
                return;
            }
        }
        if((is_array($row2)&&count($row2)>0)){
            $created_at= explode(" ", $row2['created_at']);
            $created_at=  explode("-", $created_at[0]);        
            if($created_at[1]!=  date("m")||$created_at[0]!=  date("Y")){
                return;
            }
        }
        if ($this->payment_method_id4 == PaymentMethod::TIEN_MAT) {


            if ($this->getIsNewRecord()) {
                $this->insert_thuchi(Common::TIEN_MAT, '4');
            } else {
                $row = Yii::app()->db->createCommand()->select()->from("thuchi")->where("bill_input_id=" . $this->id . " AND payment_method_id4=" . $this->payment_method_id4)->queryRow();
                if (!is_array($row) || count($row) == 0) {
                    $this->insert_thuchi(Common::TIEN_MAT, '4');
                } else {
                    $this->update_thuchi(Common::TIEN_MAT, '4');
                }
                $this->delete_thuchi1(Common::CHUYEN_KHOAN_ACB, '4');
            }
        } else if ($this->payment_method_id4 == PaymentMethod::CHUYEN_KHOAN_ACB) {


            if ($this->getIsNewRecord()) {
                $this->insert_thuchi(Common::CHUYEN_KHOAN_ACB, '4');
            } else {
                $row = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("bill_input_id=" . $this->id . " AND payment_method_id4=" . $this->payment_method_id4)->queryRow();
                if (!is_array($row) || count($row) == 0) {
                    $this->insert_thuchi(Common::CHUYEN_KHOAN_ACB, '4');
                } else {
                    $this->update_thuchi(Common::CHUYEN_KHOAN_ACB, '4');
                }
                $this->delete_thuchi1(Common::TIEN_MAT, '4');
            }
        } else {
            $this->delete_thuchi1(Common::TIEN_MAT, '4');
            $this->delete_thuchi1(Common::CHUYEN_KHOAN_ACB, '4');
        }
    }

    protected function process_tienmat_taikhoanacb_for_quocte() {
        $this->process_dong_thue();
        $this->process_gia_tri_hang_hoa_vnd();
        $this->process_chi_phi_ngan_hang();
    }

    
    /**
     * tạo một record bên sổ cái sau khi một record ở đây sinh ra
     */
    protected function create_socai(){
        if($this->is_international!='1'){
            $count=Yii::app()->db->createCommand("insert into socai ("
                                                                    ."thu,"
                                                                    ."chi,"
                                                                    ."created_at,"
                                                                    ."bill_input_id,"
                                                                    ."giao_dich,"
                                                                    ."thanh_toan,"
                                                                    ."tham_chieu,"
                                                                    ."content,"
                                                                    ."tm,"
                                                                    ."trang_thai"
                                                                    . ") "
                                                . "values ("
                                                            ."0,"
                                                            ."0,"
                                                            ."'".FunctionCommon::get_last_time_of_current_month()."',"
                                                            .$this->id.","
                                                            ."'Nhập kho kinh doanh',"
                                                            .PaymentMethod::CHUA_THANH_TOAN.","
                                                            ."'".$this->bill_number."',"
                                                            ."'".str_replace("'", "\'", $this->description)."',"
                                                            .$this->sum_and_sumtax.","
                                                            ."'".'<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/chua_hoan_thanh.png"/>'."'"
                                                . ")")
                            ->execute();            
        }
        else{ 
            $count=Yii::app()->db->createCommand("insert into socai ("
                                                                    ."thu,"
                                                                    ."chi,"
                                                                    ."created_at,"
                                                                    ."bill_input_id,"
                                                                    ."giao_dich,"
                                                                    ."thanh_toan,"
                                                                    ."payment_method_id3,"
                                                                    ."payment_method_id4,"
                                                                    ."payment_method_id5,"
                                                                    ."tham_chieu,"
                                                                    ."content,"
                                                                    ."tm,"
                                                                    ."trang_thai"
                                                                    . ") "
                                                . "values ("
                                                            ."0,"
                                                            ."0,"
                                                            ."'".FunctionCommon::get_last_time_of_current_month()."',"
                                                            .$this->id.","
                                                            ."'Tờ khai\nGiá trị hàng hóa (VND)',"
                                                            .PaymentMethod::CHUA_THANH_TOAN.","
                                                            .PaymentMethod::CHUA_THANH_TOAN.","
                                                            ."NULL,"
                                                            ."NULL,"
                                                            ."'".$this->bill_number."',"
                                                            ."'".str_replace("'", "\'", $this->description)."',"
                                                            .$this->gia_tri_hang_hoa_vnd.","
                                                            ."'".'<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/chua_hoan_thanh.png"/>'."'"
                                                . "),"
                                                        ."("
                                                            ."0,"
                                                            ."0,"
                                                            ."'".FunctionCommon::get_last_time_of_current_month()."',"
                                                            .$this->id.","
                                                            ."'Tờ khai\nChi phí ngân hàng (VND)',"
                                                            .PaymentMethod::CHUA_THANH_TOAN.","
                                                            ."NULL,"
                                                            .PaymentMethod::CHUA_THANH_TOAN.","                                                            
                                                            ."NULL,"
                                                            ."'".$this->bill_number."',"
                                                            ."'".str_replace("'", "\'", $this->description)."',"
                                                            .(($this->chi_phi_ngan_hang_vnd==''||$this->chi_phi_ngan_hang_vnd==NULL)?'0':$this->chi_phi_ngan_hang_vnd).","
                                                            ."'".'<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/chua_hoan_thanh.png"/>'."'"
                                                . ")," 
                                                        ."("
                                                            ."0,"
                                                            ."0,"
                                                            ."'".FunctionCommon::get_last_time_of_current_month()."',"
                                                            .$this->id.","
                                                            ."'Tờ khai\nTiền thuế (VND)',"
                                                            .PaymentMethod::CHUA_THANH_TOAN.","                    
                                                            ."NULL,"
                                                            ."NULL,"
                                                            .PaymentMethod::CHUA_THANH_TOAN.","
                                                            ."'".$this->bill_number."',"
                                                            ."'".str_replace("'", "\'", $this->description)."',"
                                                            .(($this->tien_thue_vnd==''||$this->tien_thue_vnd==NULL)?'0':$this->tien_thue_vnd).","
                                                            ."'".'<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/chua_hoan_thanh.png"/>'."'"
                                                . ")" 
                    . "")
                            ->execute(); 
        }
        
        if($count==0){
            return FALSE;
        }
        return true;
        
        
//        Socai::update_records(date('m'), date('Y'));

    }
    

    /**
     * 
     */
    public function afterSave() {
        parent::afterSave();
        if ($this->getIsNewRecord()) {
            if($this->create_socai()==true){
                $this->success=true;
            }
            Branch::update_type_after_create_bill_input($this->branch_id);
        } else {
            Yii::app()->db->createCommand("update socai set content='".str_replace("'", "\'", $this->description)."' where bill_input_id=".$this->id)->execute();
            if ($this->old_branch_id != $this->branch_id) {
                Branch::update_type_after_update_bill_or_bill_input($this->old_branch_id);
                Branch::update_type_after_update_bill_or_bill_input($this->branch_id);
            }
        }

    }

}
