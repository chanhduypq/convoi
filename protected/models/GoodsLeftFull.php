<?php

/**
 * 
 */
class GoodsLeftFull extends CActiveRecord {    
    private $_defaultLimitScopeDisabled = false; 

    public function disableLimitDefaultScope()
    {
          $this->_defaultLimitScopeDisabled = true;
          return $this;
    }

    public function getLimitDefaultScopeDisabled() {
        return $this->_defaultLimitScopeDisabled;
    }

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
        return 'goods_left_full_view';
    }
    public function defaultScope(){
        return $this->getLimitDefaultScopeDisabled() ?
            array(
                'group'=>'goodsunit_id',
                'order'=>Yii::app()->session['goods_left_list_sort'],
            )
            : 
            array(
                'group'=>'goodsunit_id',
                'order'=>Yii::app()->session['goods_left_list_sort'],
                'limit'=>Yii::app()->params['number_of_items_per_page'],
            )
        ;
    }    
}
