<?php

/**
 * 
 */
class PaymentMethod extends CActiveRecord {
    const CHUA_THANH_TOAN = '1';
    const KHONG_THANH_TOAN = '2';
    const TIEN_MAT = '3';
    const CHUYEN_KHOAN_ACB='4';
    const THANH_TOAN_KHAC='5';
    const CHUYEN_KHOAN_VIETCOMBANK='6';
    const TAM_UNG='7';

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
        return 'payment_method';
    }
}
