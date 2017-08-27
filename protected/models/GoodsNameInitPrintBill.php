<?php

/**
 * 
 */
class GoodsNameInitPrintBill extends CActiveRecord {
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
        return 'goods_name_init_print_bill';
    }
}
