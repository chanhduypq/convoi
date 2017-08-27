<?php

class MainCommand extends CConsoleCommand {

    public function run($args) {
        $this->runBirthday();
        $this->runEvent();
        $this->runAppointment();
    }

    private function runBirthday(){
        $this->insertNewBirthday();
    }
    private function insertNewBirthday(){
        $data=  $this->getBirthdayData();
        $created=date("Y-m-d H:i:s");
        if(is_array($data)&&count($data)>0){
            foreach ($data as $row) {     
                Yii::app()->db->createCommand()->insert('notification_logs', array(
                    'content'=>"Today is <code>".$row['username']."</code> 's birthday",
                    'type_change'=>NotificationLogs::TYPE_BIRTHDAY,
                    'received_user_id'=>$row['received_user_id'],
                    'birthday'=>$row['birthday'],
                    'created'=>$created,
                    ));
            }
        }
    }
    private function getBirthdayData(){
        $birthday_user_array=  $this->getBirthdayUsers();
        
        $return_user_array=array();
        foreach ($birthday_user_array as $key => $user) {                       
            $team_user_id_array=  $this->getSameTeamUserIds($user['id']); 
            $team_user_id_array = array_diff($team_user_id_array, array($user['id']));
            if(is_array($team_user_id_array)&&count($team_user_id_array)>0){
                foreach ($team_user_id_array as $user_id) { 
                    $user['received_user_id']=$user_id;
                    $return_user_array[]=$user;
                }
            }            
        }
        foreach ($birthday_user_array as $key => $user) {            
            $partner_user_id_array=  $this->getPartnerUserIds($user['id']); 
            $partner_user_id_array = array_diff($partner_user_id_array, array($user['id']));
            if(is_array($partner_user_id_array)&&count($partner_user_id_array)>0){
                foreach ($partner_user_id_array as $user_id) { 
                    $user['received_user_id']=$user_id;
                    $return_user_array[]=$user;
                }
            }            
        }
       
        if(count($return_user_array)>0){
            $return_user_array=  array_unique($return_user_array,SORT_REGULAR);
        }
        
        return $return_user_array;
    }

    private function insertTodayEvent(){
        $event_array=  $this->getTodayEvents();
        $created=date("Y-m-d H:i:s");
        foreach ($event_array as $key => $event) {    
            $content="The event starts today.<br/>Title: <code>".$event['title']."</code><br/>No:<code>".$event['object_id']."</code>";
            Yii::app()->db->createCommand()->insert('notification_logs', array(
                    'content'=>$content,
                    'username'=>$event['username'],
                    'user_id'=>$event['user_id'],
                    'object_name'=>'event',
                    'object_id'=>$event['object_id'],
                    'type_change'=>NotificationLogs::TYPE_EVENT_START,
                    'received_user_id'=>$event['received_user_id'],
                    'can_view'=>1,
                    'created'=>$created                    
                    ));


        }
    }
    private function insertTodayAppointment(){
        $event_array=  $this->getTodayAppointments();
        $created=date("Y-m-d H:i:s");
        foreach ($event_array as $key => $event) { 
            $content="The appointment starts today.<br/>Title: <code>".$event['title']."</code><br/>No:<code>".$event['object_id']."</code>";
            Yii::app()->db->createCommand()->insert('notification_logs', array(
                    'content'=>$content,
                    'username'=>$event['username'],
                    'user_id'=>$event['user_id'],
                    'object_name'=>'appointment',
                    'object_id'=>$event['object_id'],
                    'type_change'=>NotificationLogs::TYPE_APPOINTMENT_START,
                    'received_user_id'=>$event['received_user_id'],
                    'can_view'=>1,
                    'created'=>$created                    
                    ));


        }
    }
    private function runEvent(){
        $this->insertTodayEvent();
    }
    private function runAppointment(){
        $this->insertTodayAppointment();
    }

    private function deleteOldBirthday(){
        Yii::app()->db->createCommand("delete from notification_logs where birthday is not null and birthday <> '0000-00-00' and DATE(birthday) < DATE(NOW())")->execute();                                
    }
    private function deleteOldTodayEvent(){
        Yii::app()->db->createCommand("delete from notification_logs where type_change='EVENT_START' and object_name='event' and object_id IN (select id from event where DATE(start_date) < DATE(NOW()))")->execute();                                
    }
    
    
//    private function insertNewBirthdayForPartner(){
//        $user_array=  $this->getBirthdayUsers();
//        
//        $created=date("d.m.Y H:i:s");
//        foreach ($user_array as $key => $user) {
//            $title="Today is ".$user['username']." 's birthday";            
//            $other_user_id_array=  $this->getPartnerUserIds($user['id']); 
//            $other_user_id_array = array_diff($other_user_id_array, array($user['id']));
//            if(is_array($other_user_id_array)&&count($other_user_id_array)>0){
//                foreach ($other_user_id_array as $user_id) {                    
//                    $log = new NotificationLogs();                    
//                    $log->title=$title;
//                    $log->type_change=  NotificationLogs::TYPE_BIRTHDAY;
//                    $log->received_user_id = $user_id;
//                    $log->birthday=$user['birthday'];
//                    $log->created=  $created;
//                    $log->user_id=null;
//                    $log->save();
//                }
//            }
//        }
//        
//    }
    private function getTodayEvents(){
        $event_array=array();        
        $events=Yii::app()->db->createCommand("select distinct username,received_user_id,event.title,object_id,notification_logs.user_id from notification_logs inner join event on event.id=notification_logs.object_id and DATE(event.start_date)=DATE(NOW()) where notification_logs.object_name='event'")->queryAll();        
        if(is_array($events)&&count($events)>0){
            foreach ($events as $key=>$event) {
                $event_array[]=array('username'=>$event['username'],'received_user_id'=>$event['received_user_id'],'title'=>$event['title'],'object_id'=>$event['object_id'],'user_id'=>$event['user_id']);
            }
        }        
        return $event_array;
    }
     private function getTodayAppointments(){
        $event_array=array();        
        $events=Yii::app()->db->createCommand("select distinct username,received_user_id,appointment.title,object_id,notification_logs.user_id from notification_logs inner join appointment on appointment.id=notification_logs.object_id and DATE(appointment.start_date)=DATE(NOW()) where notification_logs.object_name='appointment'")->queryAll();        
        if(is_array($events)&&count($events)>0){
            foreach ($events as $key=>$event) {
                $event_array[]=array('username'=>$event['username'],'received_user_id'=>$event['received_user_id'],'title'=>$event['title'],'object_id'=>$event['object_id'],'user_id'=>$event['user_id']);
            }
        }        
        return $event_array;
    }
    private function getSameTeamUserIds($user_id){
        $user_id_array=array();        
        $users=Yii::app()->db->createCommand("select a.sub_acount_id,a.user_id from user_sub_account as a where a.user_id=$user_id OR a.user_id IN (select b.user_id from user_sub_account as b where b.sub_acount_id=$user_id)")->queryAll();        
        
        if(is_array($users)&&count($users)>0){
            foreach ($users as $key=>$user) {
                $user_id_array[]=$user['sub_acount_id'];
            }
            $user_id_array[]=$users[0]['user_id'];
        }        
        if(count($user_id_array)>0){
            $user_id_array=  array_unique($user_id_array);
        }
        return $user_id_array;
    }
    private function getPartnerUserIds($user_id){
        $user_id_array=array();  
        $users=Yii::app()->db->createCommand("select a.partner_id,a.user_id from user_partners as a where a.user_id=$user_id OR a.user_id IN (select b.user_id from user_partners as b where b.partner_id=$user_id)")->queryAll();        
        if(is_array($users)&&count($users)>0){
            foreach ($users as $key=>$user) {
                $user_id_array[]=$user['partner_id'];
            }
            $user_id_array[]=$users[0]['user_id'];
        }    
        if(count($user_id_array)>0){
            $user_id_array=  array_unique($user_id_array);
        }        
        return $user_id_array;
    }
    
    private function getBirthdayUsers(){        
        $user_array=array();
        $users=User::model()->findAll("DATE(birthday) = DATE(NOW())"); 
        if(is_array($users)&&count($users)>0){
            foreach ($users as $key=>$user) {
                $user_array[]=array('id'=>$user->id,'username'=>$user->username,'birthday'=>$user->birthday);
            }
        }
        return $user_array;
    }
    
            

}