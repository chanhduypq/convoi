<?php

/*
 * Write log when change information
 * Automatically save log
 *
 * Author: Anh Duong
 * Version: 1.1
 * Requires: Yii 1.0.9 version
 */

class UserLogsBehavior extends CActiveRecordBehavior {

    

    public function beforeSave($event) {

        //save log
        $sender = $event->sender;
        if ($sender->scenario != 'insert' && $sender->scenario != 'register'){
            $content='';
            $old = User::model()->findByPk($sender->id);
            foreach ($sender->attributes as $field=>$value){
                if (!in_array($field, array('created', 'updated'))){
                    if ($value != $old->$field){
                        if (strpos($field, '_id') !== false){
                            $obj = Yii::app()->func->nameToClass(str_replace('_id', '', $field));
                            $from_value = isset($old->$obj)?$old->$obj->name:Yii::t('g', 'None');
                            $to_value = isset($sender->$obj)?$sender->$obj->name:Yii::t('g', 'None');
                        }else{
                            $from_value= $old->$field;
                            $to_value = $value;
                        }
                        $content .= '<p>'.Yii::t('g', '<b>:field</b> changed from
                            <i>:from_value</i> to
                            <i>:to_value</i>', array(
                                ':field' =>$sender->getAttributeLabel($field),
                                ':from_value' => $from_value,
                                ':to_value' => $to_value
                            )).'</p>';
                    }

                }
            }
            if($content!=""){
                $subject = Yii::t('g','Change your account information ');
                $to = $sender->email;
                Utils::sendMail(Yii::app()->params['emailout'], $to, $subject, $content); 
            }
        }

        return true;
    }

   

}
