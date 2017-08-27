<?php

/*
 * Write log when change information
 * Automatically save log
 *
 * Author: Anh Duong
 * Version: 1.1
 * Requires: Yii 1.0.9 version
 */

class NotificationLogsBehavior extends CActiveRecordBehavior {

    public function writeLogForObjectAfterSaveObject($object,$is_new_record){        
        
        $user_id_array = $object->getFilterUserIds();       
        
        if (!$is_new_record) {
            if (isset($user_id_array) && count($user_id_array) > 0) {
                NotificationLogs::model()->deleteAll("object_name='object' and object_id=" . $object->id . " and user_id=" . $object->user_id . "  and received_user_id not in (" . implode(",", $user_id_array) . ")");
            }
        }       
        
        if (is_array($user_id_array) && count($user_id_array) > 0) {
            $data = $this->buildDataForInsert($object,$is_new_record?NotificationLogs::TYPE_NEW:NotificationLogs::TYPE_UPDATE);
            $this->insertNotificationLogs($user_id_array, $data);
        }
    }
    public function getObjects($potentialApplication){        
        $custom_filter=new CustomFilter();
        $custom_filter->queries=array(
            'my_object'=>0,
            'id_object'=>$potentialApplication['object_type'],
            'street_postcode'=>$potentialApplication['where'],
            'radium'=>$potentialApplication['radium'],
            'price'=>$potentialApplication['rent_price'],
            'size'=>$potentialApplication['size'],
            'room'=>$potentialApplication['room'],
            'build'=>$potentialApplication['marketing'],
        );
        $object=new Object();
        $dataProvider=$object->objectSearch($custom_filter);
        return $dataProvider->getData();
    }
    public function writeLogForObjectAfterSavePotentialApplications($potentialApplication){        
        $objects=  $this->getObjects($potentialApplication);
        if(!is_array($objects)||count($objects)==0){
            return;
        }        
        foreach ($objects as $key => $object) {
            $data = $this->buildDataForInsert($object,NotificationLogs::TYPE_NEW);
            $this->insertNotificationLogs(array(Yii::app()->user->id), $data);
        }
        
    }
    private function getReceivedUserIdArray($attr) {
        $rows = NotificationLogs::model()->findAllByAttributes($attr);
        $user_id_array = array();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $key => $value) {
                $user_id_array[] = $value->received_user_id;
            }
        }
        $user_id_array = array_unique($user_id_array);
        return $user_id_array;
    }

    private function insertNotificationLogs($user_id_array, $data) {
        if (!is_array($user_id_array) || count($user_id_array) == 0 || !is_array($data) || count($data) == 0) {
            return;
        }


        foreach ($user_id_array as $user_id) {
            $log = new NotificationLogs();
            foreach ($data as $key => $value) {
                $log->$key = $value;
            }
            $log->received_user_id = $user_id;
            $log->save();
        }
    }

    public function afterDelete($event) {
        //save log        
        $sender = $event->sender;
        $attr = array(
            'object_name' => $sender->tableName(),
            'object_id' => $sender->id,
            'user_id' => Yii::app()->user->id
        );
        $user_id_array = $this->getReceivedUserIdArray($attr);
        if (is_array($user_id_array) && count($user_id_array) > 0) {
            $data = $this->buildDataForInsert($sender, NotificationLogs::TYPE_DELETE);
            $this->insertNotificationLogs($user_id_array, $data);
        }
        NotificationLogs::model()->updateAll(array(
            'can_refuse' => 0,
            'can_accept' => 0,
            'can_view' => 0
                )
                , "object_name='" . $sender->tableName() . "' and object_id=" . $sender->id);

        return true;
    }

    private function buildDataForInsert($sender, $type) {
        if($sender->tableName()=='object'){
            $user_id=$sender->user_id;            
        }
        else{
            $user_id = Yii::app()->user->id;
        }        
        $user = User::model()->findByPk($user_id);
        $content=  $this->buildContent($sender, $type, $user);
        return array(
            'type_change' => $type,
            'object_name' => $sender->tableName(),
            'object_id' => $sender->id,
            'user_id' => $user_id,
            'is_viewed' => Lookup::NO,
            'created' => date("Y-m-d H:i:s"),
            'content' =>Yii::t("g",$content),// ($sender->tableName() != "object") ? $sender->title : $sender->information->headline,
            'username' => $user->username,
            'can_view' => $type == NotificationLogs::TYPE_DELETE ? 0 : 1,
            'can_refuse' => ($type == NotificationLogs::TYPE_DELETE || $sender->tableName() == "object" ||$type==NotificationLogs::TYPE_COMMUTITY_ACCEPT||$type==NotificationLogs::TYPE_COMMUTITY_REFUSE) ? 0 : 1,
            'can_accept' => ($type == NotificationLogs::TYPE_DELETE || $sender->tableName() == "object" ||$type==NotificationLogs::TYPE_COMMUTITY_ACCEPT||$type==NotificationLogs::TYPE_COMMUTITY_REFUSE) ? 0 : 1,
        );
    }
    private function buildContent($sender, $type,$user) {
        $fullName=$user->getFullName();
        $content="";
        if($sender->tableName()=="event"||$sender->tableName()=="appointment"){
            if($type==NotificationLogs::TYPE_NEW){
                $content.="<b>$fullName</b> has invited you to participate in one ".$sender->tableName()."<br/>Title: <code>".$sender->title."</code><br/>Id:<code>".$sender->id."</code>";
            }
            else if($type==NotificationLogs::TYPE_UPDATE){
                $content.="<b>$fullName</b> has changed informations of one ".$sender->tableName()."<br/>Title: <code>".$sender->title."</code><br/>Id:<code>".$sender->id."</code>";
            }
            else if($type==NotificationLogs::TYPE_DELETE){
                $content.="<b>$fullName</b> has deleted one ".$sender->tableName()."<br/>Title: <code>".$sender->title."</code><br/>Id:<code>".$sender->id."</code>";
            }
            
            
        }
        else if($sender->tableName()=='object'){
            
            if($type==NotificationLogs::TYPE_NEW){
                $content.="<b>$fullName</b> has created one real estate with the criteria of your potential customers out.<br>Headline <code>".$sender->information->headline."</code><br/>Id:".$sender->id."</code>";
            }
            else if($type==NotificationLogs::TYPE_UPDATE){
                $content.="<b>$fullName</b> has changed informations of one real estate.<br>Headline <code>".$sender->information->headline."</code><br/>Id:".$sender->id."</code>";
            }
            else if($type==NotificationLogs::TYPE_DELETE){
                $content.="<b>$fullName</b> has deleted one real estate.<br>Headline <code>".$sender->information->headline."</code><br/>Id:".$sender->id."</code>";
            }
        } 
        else if ($sender->tableName() == "invitationpartner") {            
            if($type==NotificationLogs::TYPE_NEW){
                $content.="<b>$fullName</b> has invited you to become a partner.<br/>Title: <code>".$sender->title."</code><br/>Id:<code>".$sender->id."</code>";
            }
            else if($type==NotificationLogs::TYPE_DELETE){
                $content.="<b>$fullName</b> has deleted an invitation to collaborate.<br/>Title: <code>".$sender->title."</code><br/>Id:<code>".$sender->id."</code>";
            }
            
        }
        else if ($sender->tableName() == "community") {            
            if($type==NotificationLogs::TYPE_COMMUTITY_REQUEST){
                $content.="<b>$fullName</b> has sent one request to you about one real estate.<br/>Title: <code>".$sender->title."</code><br/>Id:<code>".$sender->id."</code>";
            }
            else if($type==NotificationLogs::TYPE_COMMUTITY_ACCEPT){                
                $content.="<b>$fullName</b> has accepted a request to you about real estate.<br/>Title: <code>".$sender->title."</code><br/>Id:<code>".$sender->id."</code>";
            }
            else if($type==NotificationLogs::TYPE_COMMUTITY_REFUSE){
                $content.="<b>$fullName</b> has refused a request to you about real estate.<br/>Title: <code>".$sender->title."</code><br/>Id:<code>".$sender->id."</code>";
            }
            
        }        
        return $content;
    }
    

    public function afterSave($event) {

        //save log
        $sender = $event->sender;

        if ($sender->scenario == 'insert') {

            $user_id_array = array();
            if ($sender->tableName() == "appointment") {
                $user_id_array = $sender->invite_members;
            } else if ($sender->tableName() == "community") {
                $user_id_array =array($sender->received_user_id);
            } else if ($sender->tableName() == "event") {
                $user_id_array = $sender->member_id_array;
            } else if ($sender->tableName() == "invitationpartner") {
                $user_id_array = $sender->partner_id_array;
            }



            if (is_array($user_id_array) && count($user_id_array) > 0) {
                $data = $this->buildDataForInsert($sender,$sender->tableName() == "community"?NotificationLogs::TYPE_COMMUTITY_REQUEST:NotificationLogs::TYPE_NEW);
                $this->insertNotificationLogs($user_id_array, $data);
            }
        }
        

        return true;
    }

    public function beforeSave($event) {
        $sender = $event->sender;

        if ($sender->scenario != 'insert') {
            
            $user_id_array = array();
            if ($sender->tableName() == "appointment") {
                $user_id_array = $sender->invite_members;
            }
            else if ($sender->tableName() == "community") {
                $user_id_array =array($sender->user_id);
            }
            else if ($sender->tableName() == "event") {
                $user_id_array = $sender->member_id_array;
            } else if ($sender->tableName() == "invitationpartner") {
                $user_id_array = $sender->partner_id_array;
            } 

            if (is_array($user_id_array) && count($user_id_array) > 0) {
                NotificationLogs::model()->deleteAll("object_name='" . $sender->tableName() . "' and object_id=" . $sender->id . " and user_id=" . Yii::app()->user->id . "  and received_user_id not in (" . implode(",", $user_id_array) . ")");
            }


            if (is_array($user_id_array) && count($user_id_array) > 0) {
                if($sender->tableName() == "community"){
                    
                    $type_change='';
                    if($sender->accept==1){
                        $type_change=  NotificationLogs::TYPE_COMMUTITY_ACCEPT;
                    }
                    else if($sender->accept==0){
                        $type_change=  NotificationLogs::TYPE_COMMUTITY_REFUSE;
                    }
                    
                    $data = $this->buildDataForInsert($sender,$type_change);
                }
                else{
                    $data = $this->buildDataForInsert($sender,NotificationLogs::TYPE_UPDATE);
                }
                
                $this->insertNotificationLogs($user_id_array, $data);
            }
        }



        return true;
    }

}
