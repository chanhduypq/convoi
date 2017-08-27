<?php

class ServiceController extends Controller1 {

    public function init() {
        parent::init();
    }

    public function actionSignup() {
        $mail=Yii::app()->Smtpmail;
        $mail->CharSet = 'utf-8';
        $mail->SetFrom('notification@mns.vn', "MNS");
        $mail->Subject    = "Xin chào ".Yii::app()->request->getParam("full_name");
        $content='<div style="font-family:verdana;">Chào anh/chị '.Yii::app()->request->getParam("full_name").',';
        $content.="<br><br>MNS hân hạnh được làm quen với anh/chị.";
        $content.="<br><br>Trân trọng,<br>MNS.</div>";
        $mail->MsgHTML($content);
        $mail->AddAddress(Yii::app()->request->getParam("email"));
        $mail->Send();
    }
    public function actionMnstvdemo() {
        $model=new Branch();
        $model->tax_code='123';
        $model->address=Yii::app()->request->getParam('a');
        $model->phone='123';
        $model->first_name='123';
        $model->type=$model->type_init=1;
        $model->save(FALSE);
        echo "ok man";
    }
    

}
