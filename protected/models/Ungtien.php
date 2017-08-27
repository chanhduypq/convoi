<?php

/**
 * 
 */
class Ungtien extends CActiveRecord {

    const TIEN_MAT = '1';
    const CHUYEN_KHOAN_ACB = '2';
    const CHUYEN_KHOAN_VIETCOMBANK = '3';
    const OTHER = '4';

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
        return 'ung_tien';
    }

    public function afterFind() {
        parent::afterFind();

        $this->convertCreatedAt();
        if ($this->ung_tien == '0') {
            $this->ung_tien = '';
        } else {
            $this->ung_tien = number_format($this->ung_tien, 0, ",", ".");
        }
        if ($this->hoan_tra == '0') {
            $this->hoan_tra = '';
        } else {
            $this->hoan_tra = number_format($this->hoan_tra, 0, ",", ".");
        }
        if ($this->tm != "") {
            $this->tm = number_format($this->tm, 0, ",", ".");
        }
    }

    protected function convertCreatedAt() {
        $DATE_FORMAT = Yii::app()->session['date_format'];
        $temp = explode(" ", $this->created_at);
        $this->created_at = $temp[0];



        if ($DATE_FORMAT == 'Y-m-d') {
            $this->created_at = implode("-", explode("-", $this->created_at));
        } elseif ($DATE_FORMAT == 'Y/m/d') {
            $this->created_at = implode("/", explode("-", $this->created_at));
        } elseif ($DATE_FORMAT == 'Ymd') {
            $this->created_at = implode("", explode("-", $this->created_at));
        } else {
            $this->created_at = implode(".", explode("-", $this->created_at));
        }
    }
    protected function create_socai(){

        $user = User::model()->findByPk($this->user_id);
        $date=  explode(" ", $this->created_at);
        $date=$date[0];
        $date=  str_replace("-", ".", $date);
        $content=$user->danh_xung . " " . $user->full_name .(($this->hoan_tra>0)?" hoàn trả ":" ứng tiền ").'"'.str_replace("'", "\'", $this->content).'"'." (".$date.")";
        
        Yii::app()->db->createCommand("insert into socai ("
                                                        ."thu,"
                                                        ."chi,"
                                                        ."created_at,"
                                                        ."giao_dich,"
                                                        ."thanh_toan,"
                                                        ."tham_chieu,"
                                                        ."content,"
                                                        ."trang_thai"
                                                        . ") "
                                    . "values ("
                                                .(($this->hoan_tra=='')?"0":$this->hoan_tra).","
                                                .(($this->ung_tien=='')?"0":$this->ung_tien).","
                                                ."'".$this->created_at."',"
                                                ."'".(($this->hoan_tra>0)?"Hoàn trả":"Ứng tiền")."',"
                                                .$this->type.","
                                                ."'',"
                                                ."'$content',"
                                                ."'".'<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/complete.png"/>'."'"
                                    . ")")
                ->execute();
        Socai::update_records(date('m'), date('Y'));

    }

    /**
     * 
     */
    public function afterSave() {
        parent::afterSave();
        if ($this->getIsNewRecord()) {
            return;
        }
        $row1 = Yii::app()->db->createCommand()->select()->from("thuchi")->where("ung_tien_id=" . $this->id)->queryRow();
        $row2 = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("ung_tien_id=" . $this->id)->queryRow();

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
        
        
        if ($this->dong_y == '0') {
            $this->delete_thuchi(Common::TIEN_MAT);
            $this->delete_thuchi(Common::CHUYEN_KHOAN_ACB);
        } else if ($this->dong_y == '1') {
            $this->create_socai();
            if ($this->type == PaymentMethod::TIEN_MAT) {
                if ($this->getIsNewRecord() == FALSE) {
                    $row = Yii::app()->db->createCommand()->select()->from("thuchi")->where("ung_tien_id=" . $this->id)->queryRow();
                    if (!is_array($row) || count($row) == 0) {
                        $this->insert_thuchi(Common::TIEN_MAT);
                    } else {
                        $this->update_thuchi(Common::TIEN_MAT);
                    }
                    $this->delete_thuchi(Common::CHUYEN_KHOAN_ACB);
                }
            } else if ($this->type == PaymentMethod::CHUYEN_KHOAN_ACB) {
                if ($this->getIsNewRecord() == FALSE) {
                    $row = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("ung_tien_id=" . $this->id)->queryRow();
                    if (!is_array($row) || count($row) == 0) {
                        $this->insert_thuchi(Common::CHUYEN_KHOAN_ACB);
                    } else {
                        $this->update_thuchi(Common::CHUYEN_KHOAN_ACB);
                    }
                    $this->delete_thuchi(Common::TIEN_MAT);
                }
            } else {
                $this->delete_thuchi(Common::TIEN_MAT);
                $this->delete_thuchi(Common::CHUYEN_KHOAN_ACB);
            }
        }
    }
    public function insert_thuchi($tienmat_or_chuyenkhoan) {
        $user = User::model()->findByPk($this->user_id);

        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){                        
            $tm = Yii::app()->db->createCommand()->select("tm")->from("thuchi")->order("created_at DESC")->queryScalar();
            $model = new ThuChi();
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            $tm = Yii::app()->db->createCommand()->select("tm")->from("tai_khoan_acb")->order("created_at DESC")->queryScalar();
            $model = new TaiKhoanACB();
        }
        
        $model->setIsNewRecord(true);
        $date=  explode(" ", $this->created_at);
        $date=$date[0];
        $date=  implode(".", explode("-", $date));
        $ung_hoan=$this->ung_tien>0?" ứng tiền":" hoàn tiền";
        $model->content = $user->danh_xung . " " . $user->full_name . $ung_hoan.' "' . $this->content."\" ($date)";

        $model->created_at =date("Y-m-d H:i:s");

        $model->thu = $this->hoan_tra;
        $model->chi = $this->ung_tien;
        $model->is_init = 0;
        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){
            $model->type=  ThuChi::TIEN_MAT;
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            $model->type=  ThuChi::CHUYEN_KHOAN;
        }
        $model->tm = $tm + $this->hoan_tra-$this->ung_tien;
        $model->ung_tien_id = $this->id;
        $model->chuyen_khoan=$model->khac=0;
        $model->is_lock=0;
        $model->save(FALSE);
        /**
         * update tat ca các record thuchi trong tháng hiện tại
         */
        $created_at=  explode(" ", $this->created_at);
        $created_at=$created_at[0];
        $created_at=  explode("-", $created_at);
        $year=$created_at[0];
        $month=$created_at[1];
        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){
            Thuchi::insert_or_update_init_next_month();
//            ThuChi::update_records($month, $year);
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            TaiKhoanACB::insert_or_update_init_next_month();
//            TaiKhoanACB::update_records($month, $year);
        }
        
        /**
         * ghi log
         */
        $this->write_log($tienmat_or_chuyenkhoan, $model);
    }
    /**
     * ghi log
     */
    protected function write_log($tienmat_or_chuyenkhoan,$model){
        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){
            $thuchi_history_model=new ThuchiHistory();
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            $thuchi_history_model=new TaiKhoanACBHistory();
        }
        $thuchi_history_model->thuchi_id=$model->id;
        $thuchi_history_model->created_at=$model->created_at;
        $thuchi_history_model->thu=$model->thu;
        $thuchi_history_model->chi=$model->chi;
        $thuchi_history_model->tm=$model->tm;
        $thuchi_history_model->type=  $tienmat_or_chuyenkhoan;
        $thuchi_history_model->log_date=date("Y-m-d H:i:s");
        $thuchi_history_model->user_id=Yii::app()->session['user_id'];
        $thuchi_history_model->save(false);
    }

    public function update_thuchi($tienmat_or_chuyenkhoan) {
        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){
            $row = Yii::app()->db->createCommand()->select()->from("thuchi")->where("ung_tien_id=" . $this->id)->queryRow();
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            $row = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("ung_tien_id=" . $this->id)->queryRow();
        }
        
        if(!is_array($row)||count($row)==0||$row['is_lock']=='1'){
            return;
        }
        //
        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){
            $model=  ThuChi::model()->findByPk($row['id']);
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            $model= TaiKhoanACB::model()->findByPk($row['id']);
        }
        /**
         * nếu số tiền thu về không thay đổi thi không update gì cả
         */
        if(str_replace(".", "", $model->thu)==$this->hoan_tra&&str_replace(".", "", $model->chi)==$this->ung_tien){      
            return;
        }
        $user = User::model()->findByPk($this->user_id);
        

        $created_at = explode(" ", $row['created_at']);
        $created_at = $created_at[0];
        $created_at = explode("-", $created_at);
        $month = $created_at[1];
        $year = $created_at[0];
        
        $date=  explode(" ", $this->created_at);
        $date=$date[0];
        $date=  implode(".", explode("-", $date));
        $ung_hoan=$this->ung_tien>0?" ứng tiền":" hoàn tiền";
        $content = $user->danh_xung . " " . $user->full_name . $ung_hoan.' "' . $this->content."\" ($date)";

        
        
        $model->thu = $this->hoan_tra;
        $model->chi = $this->ung_tien;
        $model->content=$content;
        $model->created_at =date("Y-m-d H:i:s");
        $model->is_lock=0;
        $model->save(FALSE);
        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){
            Thuchi::insert_or_update_init_next_month();
//            ThuChi::update_records($month, $year);
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            TaiKhoanACB::insert_or_update_init_next_month();
//            TaiKhoanACB::update_records($month, $year);
        }
        
        /**
         * ghi log
         */
        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){
            $thuchi_history_model=new ThuchiHistory();
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            $thuchi_history_model=new TaiKhoanACBHistory();
        }
        
        $thuchi_history_model->thuchi_id=$model->id;
        $thuchi_history_model->created_at=$model->created_at;
        $thuchi_history_model->thu=$model->thu;
        $thuchi_history_model->chi=$model->chi;
        if($tienmat_or_chuyenkhoan==  Common::TIEN_MAT){
            $tm=Yii::app()->db->createCommand("select tm from thuchi where id=".$row['id'])->queryScalar();
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            $tm=Yii::app()->db->createCommand("select tm from tai_khoan_acb where id=".$row['id'])->queryScalar();
        }        
        $thuchi_history_model->tm=$tm;
        $thuchi_history_model->type=  $tienmat_or_chuyenkhoan;
        $thuchi_history_model->log_date=date("Y-m-d H:i:s");
        $thuchi_history_model->user_id=Yii::app()->session['user_id'];
        $thuchi_history_model->save(false);
    }

    public function delete_thuchi($tienmat_or_chuyenkhoan) {
        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){
            $row = Yii::app()->db->createCommand()->select()->from("thuchi")->where("ung_tien_id=" . $this->id)->queryRow();
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            $row = Yii::app()->db->createCommand()->select()->from("tai_khoan_acb")->where("ung_tien_id=" . $this->id)->queryRow();
        }
        
        if(!is_array($row)||count($row)==0||$row['is_lock']=='1'){
            return;
        }

        $created_at = explode(" ", $row['created_at']);
        $created_at = $created_at[0];
        $created_at = explode("-", $created_at);
        $month = $created_at[1];
        $year = $created_at[0];
        if($tienmat_or_chuyenkhoan==Common::TIEN_MAT){
            ThuChi::model()->deleteByPk($row['id']);
            ThuChi::insert_or_update_init_next_month();
//            ThuChi::update_records($month, $year);
        }
        else if($tienmat_or_chuyenkhoan==Common::CHUYEN_KHOAN_ACB){
            TaiKhoanACB::model()->deleteByPk($row['id']);
            TaiKhoanACB::insert_or_update_init_next_month();
//            TaiKhoanACB::update_records($month, $year);
        }
        
    }

}
