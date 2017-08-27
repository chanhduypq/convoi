<?php

/**
 * 
 */
class BillInputDetaiInternationallFull extends CActiveRecord {    
    public $sum_thue_tieu_thu_dac_biet;
    public $sum_thue_nhap_khau;
    public $sum_tax;
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
        return 'bill_international_input_detail_full_view';
    }
    public function afterFind() {
        parent::afterFind();
        $price=  str_replace(".", "", $this->price);
        $quantity=  str_replace(".", "", $this->quantity);
        $sum_thue_tieu_thu_dac_biet=($price*$quantity*$this->thue_tieu_thu_dac_biet)/100;
        $this->sum_thue_tieu_thu_dac_biet= number_format($sum_thue_tieu_thu_dac_biet,0,',','.');        
        
        $sum_thue_nhap_khau=(($sum_thue_tieu_thu_dac_biet+$price*$quantity)*$this->thue_nhap_khau)/100;
        $this->sum_thue_nhap_khau= number_format($sum_thue_nhap_khau,0,',','.');
        
        $sum_tax=(($sum_thue_tieu_thu_dac_biet+$sum_thue_nhap_khau+$price*$quantity)*$this->tax)/100;
        $this->sum_tax= number_format($sum_tax,0,',','.');      
    }
}
