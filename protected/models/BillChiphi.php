<?php

/**
 * 
 */
class BillChiphi extends CActiveRecord {
    private $old_branch_id;
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
        return 'bill_chi_phi';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return array(
            array('bill_number', 'unique', 'on' => 'insert,update', 'message' => 'Đã tồn tại Số hóa đơn này rồi. Vui lòng nhập lại.'
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
     * tạo một record bên sổ cái sau khi một record ở đây sinh ra
     */
    protected function create_socai(){
        $count=Yii::app()->db->createCommand("insert into socai ("
                                                        ."thu,"
                                                        ."chi,"
                                                        ."created_at,"
                                                        ."bill_chi_phi_id,"
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
                                                ."'Chi phí dịch vụ có hóa đơn',"
                                                .PaymentMethod::CHUA_THANH_TOAN.","
                                                ."'".$this->bill_number."',"
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
        if($this->sum==0){
            $this->success=true;
            return;
        }
        if ($this->getIsNewRecord()) {
            if($this->create_socai()==true){
                $this->success=true;
            }
            Branch::update_type_after_create_bill_input($this->branch_id);
        } else {
            Yii::app()->db->createCommand("update socai set content='".str_replace("'", "\'", $this->description)."' where bill_chi_phi_id=".$this->id)->execute();
            if ($this->old_branch_id != $this->branch_id) {
                Branch::update_type_after_update_bill_or_bill_input($this->old_branch_id);
                Branch::update_type_after_update_bill_or_bill_input($this->branch_id);
            }
        }
        
    }

}
