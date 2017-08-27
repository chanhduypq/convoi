<?php

/**
 * 
 */
class Sxdv extends CActiveRecord {

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
        return 'sxdv';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
        );
    }

    /**
     * 
     */
    public function beforeSave() {
        $this->sum_and_sumtax = $this->sum + $this->tax_sum;

        if ($this->getIsNewRecord()) {
            $this->created_at.=" " . date("H:i:s");
            $this->last_updated_at = $this->created_at;
            $this->is_printed = 0;
            $bill_number =  Bill::getBillNumberForInsert();
            if ($bill_number == '0') {
                $bill_number = Yii::app()->params['INIT_BILL_NUMBER'];
            }
            $this->bill_number = (int) $bill_number;
            $this->user_id = Yii::app()->session['user_id'];
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
        $bill_number=$this->bill_number;
        $zero_string="";
        $NUMBER_OF_DIGIT_OF_BILLNUMBER=Yii::app()->params['NUMBER_OF_DIGIT_OF_BILLNUMBER'];
        for($i=0;$i<$NUMBER_OF_DIGIT_OF_BILLNUMBER-strlen($bill_number);$i++){
            $zero_string.="0";
        }
        $bill_number=$zero_string.$bill_number;
        $count=Yii::app()->db->createCommand("insert into socai ("
                                                        ."thu,"
                                                        ."chi,"
                                                        ."created_at,"
                                                        ."sxdv_id,"
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
                                                ."'HĐ Sản xuất & dịch vụ',"
                                                .PaymentMethod::CHUA_THANH_TOAN.","
                                                ."'".$bill_number."',"
                                                ."'".Yii::app()->session['danh_xung_full_name'] . " thu tiền hóa đơn " . $bill_number." (".date("Y.m.d").")',"
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
            Branch::update_type_after_create_bill($this->branch_id);
            $model = BillSign::model()->find();
            $model->current_bill_number = $model->current_bill_number + 1;
            Yii::app()->db->createCommand("update bill_sign set current_bill_number=" . $model->current_bill_number . " where id=" . $model->id)->execute();
//            $model->save(FALSE);
        } else {
            if ($this->old_branch_id != $this->branch_id) {
                Branch::update_type_after_update_bill_or_bill_input($this->old_branch_id);
                Branch::update_type_after_update_bill_or_bill_input($this->branch_id);
            }
        }

        
    }

    /**
     * 
     */
    public function getBillNumberForCreate() {
        $bill_number = Yii::app()->db->createCommand()
                ->select("current_bill_number as current_bill_number")
                ->from("bill_sign")
                ->queryScalar();
        if ($bill_number == FALSE || $bill_number == '0') {
            $bill_number = Yii::app()->params['INIT_BILL_NUMBER'];
        } else {
            $bill_number = (string) $bill_number;
        }
        $zero_string = "";
        $NUMBER_OF_DIGIT_OF_BILLNUMBER = Yii::app()->params['NUMBER_OF_DIGIT_OF_BILLNUMBER'];
        for ($i = 0; $i < $NUMBER_OF_DIGIT_OF_BILLNUMBER - strlen($bill_number); $i++) {
            $zero_string.="0";
        }
        $bill_number = $zero_string . $bill_number;
        return $bill_number;
    }

}
