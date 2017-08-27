<?php

/**
 * 
 */
class GoodsInputFull extends CActiveRecord {    

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
        return 'goods_input_full_view';
    }
    public function defaultScope(){
        return array(
            'group'=>'goodsunit_id',
            'order'=>Yii::app()->session['goods_input_list_sort'],
            'limit'=>Yii::app()->params['number_of_items_per_page'],
        );
    }
}
