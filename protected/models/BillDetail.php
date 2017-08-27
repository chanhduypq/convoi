<?php

/**
 * 
 */
class BillDetail extends CActiveRecord {
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
        return 'bill_detail';
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
        return parent::beforeSave();
    }
    /**
     * 
     */
    public function afterSave() {
        parent::afterSave();
        /**
         * update số lượng tồn kho
         */
        $model=  GoodslLeft::model()->findByPk($this->goods_id);
        $model->quantity_left=$model->quantity_left-$this->quantity;
//        $model->save(FALSE);
        Yii::app()->db->createCommand("update goods_left set quantity_left=".$model->quantity_left." where id=".$this->goods_id)->execute();       
        /**
         * 
         */
        GoodslLeft::update_avg_price($this->goods_id);        
        GoodslLeft::update_so_khach_hang_so_hoa_don($this->goods_id);
    }
    /**
     * 
     */
    public function afterDelete() {
        parent::afterDelete();
        /**
         * update số lượng tồn kho
         */
        $model=  GoodslLeft::model()->findByPk($this->goods_id);
        $model->quantity_left=$model->quantity_left+$this->quantity;
        $model->save(FALSE);
    }
    

    

    

}
