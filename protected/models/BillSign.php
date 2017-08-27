<?php

/**
 * 
 */
class BillSign extends CActiveRecord {
     

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
        return 'bill_sign';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {       
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(            
            array('sign,init_bill_number,start_date,mau_so', 'required', 'message' => 'Vui lòng nhập {attribute}.'),           
            array('bill_type_id', 'required', 'message' => 'Vui lòng chọn Mẫu hóa đơn.'),           
            array(
                'init_bill_number',
                'match', 'not' => true, 'pattern' => '/[^0-9]/',
                'message' => '{attribute} chỉ được nhập bằng chữ số.',
            ),
            array('sign,mau_so', 'unique', 'on' => 'insert,update', 'message' => 'Đã tồn tại {attribute} này rồi. Vui lòng nhập lại.'),
            array('start_date', 'type', 'type' => 'date', 'message' => '{attribute}: không đúng định dạng ngày tháng!', 'dateFormat' => FunctionCommon::convertDateForValidation()),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'sign' => 'Ký hiệu',
            'init_bill_number' => 'Số bắt đầu',
            'start_date' => 'Ngày bắt đầu',
            'mau_so' => 'Mẫu số',            
            
        );
    }
    /**
     * 
     */
    public function beforeSave() {
        $this->current_bill_number=  $this->init_bill_number=(int)$this->init_bill_number;
        $this->start_date=  FunctionCommon::convertDateForDB($this->start_date);
        return parent::beforeSave();
    }
    

   

    

}
