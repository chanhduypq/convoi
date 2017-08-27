<?php

/**
 * 
 */
class Demo extends CActiveRecord {
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
        return 'user';
    }

    public static $conection;
    public function getDbConnection() {
        if(self::$conection!==null)
        return self::$conection;

        else{
            self::$conection = Yii::app()->db2; // main.php - DB config name

            if(self::$conection instanceof CDbConnection){
                self::$conection->setActive(true);
                return self::$conection;
            }
            else
                throw new CDbException(Yii::t('yii',"Active Record requires a '$conection' CDbConnection application component."));
        }
    }

    





}
