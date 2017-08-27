<?php

/**
 * 
 */
class SxdvDetail extends CActiveRecord {
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
        return 'sxdv_detail';
    }   
    /**
     * 
     */
    public function beforeSave() {
        /**
         * bỏ dấu phẩy và dấu chấm trong chuỗi số để đưa vào db với type là int
         */
        $this->quantity=  str_replace(".", "", $this->quantity);
        $this->quantity=  str_replace(",", "", $this->quantity);    
        $this->price=  str_replace(".", "", $this->price);
        $this->price=  str_replace(",", "", $this->price);
        $this->price_has_tax=  str_replace(".", "", $this->price_has_tax);
        $this->price_has_tax=  str_replace(",", "", $this->price_has_tax);
        $this->tax=  str_replace(".", "", $this->tax);
        $this->tax=  str_replace(",", "", $this->tax);
        return parent::beforeSave();
    }
}
