<?php

/**
 * 
 */
class Role extends CActiveRecord {

    const QUAN_LY_KHO_HANG='1';
    const QUAN_LY_BAN_HANG='2';
    const GIAM_SAT_HE_THONG='3';
    const ADMIN='4';
    const NHAN_VIEN='5';

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
        return 'role';
    }

    

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'role' => 'Chức năng',
            
        );
    }

    

}
