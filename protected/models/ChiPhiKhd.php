<?php

/**
 * 
 */
class ChiPhiKhd extends CActiveRecord {

    const TIEN_MAT = '1';
    const CHUYEN_KHOAN_ACB = '2';
    const CHUYEN_KHOAN_VIETCOMBANK = '3';
    const OTHER = '4';
    public $thu_chi=0;
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
        return 'chi_phi_khd';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return array(
            
        );
    }

    /**
     * 
     */
    public function beforeSave() {
        if($this->getIsNewRecord()){
            $stt=Yii::app()->db->createCommand("select max(stt) as max from chi_phi_khd")->queryScalar();
            if($stt==FALSE||$stt==""){
                $stt=0;
            }
            $stt++;
            $this->stt=$stt;
        }
        
        $this->created_at = FunctionCommon::convertDateForDB($this->created_at) . date(" H:i:s");
        if ($this->getIsNewRecord()) {


            $this->user_id = Yii::app()->session['user_id'];
            $this->last_updated_at = $this->created_at;
        } else {
            $this->last_updated_at = date("Y-m-d H:i:s");
        }


        return parent::beforeSave();
    }
    
    
    
    /**
     * tạo một record bên sổ cái sau khi một record ở đây sinh ra
     */
    protected function create_socai(){
        $count=Yii::app()->db->createCommand("insert into socai ("
                                                        ."thu,"
                                                        ."chi,"
                                                        ."created_at,"
                                                        ."chi_phi_khd_id,"
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
                                                ."'Chi phí dịch vụ không hóa đơn',"
                                                .PaymentMethod::CHUA_THANH_TOAN.","
                                                ."'".$this->stt."',"
                                                ."'".str_replace("'", "\'", $this->description)."',"
                                                .$this->sum_and_sumtax.","
                                                ."'".'<img style="width: 39px;height: 39px;" src="'.Yii::app()->theme->baseUrl.'/images/icon/socai/chua_hoan_thanh.png"/>'."'"
                                    . ")")
                ->execute();
        
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
        }
        else{
            Yii::app()->db->createCommand("update socai set content='".str_replace("'", "\'", $this->description)."' where chi_phi_khd_id=".$this->id)->execute();
        }
        
    }

}
