<?php

/**
 * 
 */
class BillType extends CActiveRecord {
     

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
        return 'bill_type';
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
        
        return parent::beforeSave();
    }
    

    public function afterSave() {
        /**
         * 
         */
        parent::afterSave();       
        
    }
    public function afterDelete() {
        parent::afterDelete();
         
    }
    public function afterFind() {
        /**
         * 
         */
        parent::afterFind();        
             
    }

    public function relations() {

        return array(
            
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        

        $criteria = new CDbCriteria;     
        
        



        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    

}
