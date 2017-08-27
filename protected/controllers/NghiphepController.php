<?php

class NghiphepController extends Controller {

    public function init() {
        parent::init();
    }

    public function actionSave() {
        $id=Yii::app()->request->getParam("id_ungtien");
        if($id!=""){
            $model= Nghiphep::model()->findByPk($id);
        }
        else{
            $model=new Nghiphep();        
            $model->setIsNewRecord(true);
        }
        
        
        $model->content=Yii::app()->request->getParam("content");

        
        $model->start_date =FunctionCommon::convertDateForDB(Yii::app()->request->getParam("start_date")).date(" H:i:s");
        $model->end_date =FunctionCommon::convertDateForDB(Yii::app()->request->getParam("end_date")).date(" H:i:s");
        
        
        $model->so_ngay_nghi =  str_replace(",", ".", Yii::app()->request->getParam("so_ngay_nghi"));

        
        $model->stt=Yii::app()->request->getParam("stt");
        if($model->stt=='1'){
            $model->so_ngay_con_lai = 12-$model->so_ngay_nghi;
            
        }
        else{
            $so_ngay_con_lai=Yii::app()->db->createCommand("select so_ngay_con_lai from nghiphep where user_id=".Yii::app()->session['user_id']." and stt=".($model->stt-1)." and year=".date("Y"))->queryScalar();
            if($so_ngay_con_lai==FALSE||$so_ngay_con_lai==''){
                $so_ngay_con_lai=12;
            }
            $model->so_ngay_con_lai = $so_ngay_con_lai-$model->so_ngay_nghi;
        }
        
        $model->user_id=Yii::app()->session['user_id'];


        $model->year=date("Y");
        $model->save(FALSE);
        $this->send_mail($model->so_ngay_nghi,$model->so_ngay_con_lai,Yii::app()->request->getParam("start_date"),Yii::app()->request->getParam("end_date"),Yii::app()->request->getParam("content"));
        
    }
    protected function send_mail($so_ngay_nghi,$so_ngay_con_lai,$start_date,$end_date,$noidung){
        $temp=  explode(" ", Yii::app()->session['danh_xung_full_name']);
        $full_name='';
        for($i=1;$i<count($temp);$i++){
            $full_name.=$temp[$i].' ';
        }
        $mail=Yii::app()->Smtpmail;
        $mail->CharSet = 'utf-8';
        $mail->SetFrom(Yii::app()->session['email'], $full_name);
        $mail->Subject    = $full_name."nghỉ phép $so_ngay_nghi ngày (từ $start_date đến $end_date)";
        $content='<style>
                    ul#content_ul li{
                        margin-left: 50px;
                        list-style: disc !important;
                    }                    
                  </style>';
        $content.='<div style="font-family:verdana;">Xin chào MNS,<br><br>';
        $content.=$full_name."thông báo thời gian nghỉ phép $so_ngay_nghi ngày như sau.<br>";
        $content.="<ul id='content_ul'>";
        $content.="<li>Bắt đầu nghỉ từ $start_date, ngày đi làm lại $end_date</li>";
        $content.="<li>Nội dung: $noidung</li>";
        $content.="<li>Số ngày phép còn lại năm ".date("Y").": $so_ngay_con_lai ngày</li>";
        $content.="</ul>";
        $content.="Trân trọng,<br>$full_name</div>";
        $mail->MsgHTML($content);
        $mail->AddAddress("all@mns.vn");
        $mail->Send();
        
        
    }

    

}
