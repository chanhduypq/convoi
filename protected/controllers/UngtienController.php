<?php

class UngtienController extends Controller {

    public function init() {
        parent::init();
    }

    public function actionSave() {
        $ungtien_hoantra=Yii::app()->request->getParam("ungtien_hoantra");
        $tien=Yii::app()->request->getParam("tien");
        $tien=  str_replace(".", "", $tien);
        $id=Yii::app()->request->getParam("id_ungtien");
        if($id!=""){
            $model=  Ungtien::model()->findByPk($id);
        }
        else{
            $model=new Ungtien();        
            $model->setIsNewRecord(true);
        }
        
        
        $model->content=Yii::app()->request->getParam("content");

        $model->created_at =FunctionCommon::convertDateForDB(Yii::app()->request->getParam("created_at")).date(" H:i:s");
        if($ungtien_hoantra=='1'){
            $model->hoan_tra = 0;
            $model->ung_tien = $tien;
        }
        else{
            $model->hoan_tra = $tien;
            $model->ung_tien = 0;
        }
        
        

        
        $model->stt=Yii::app()->request->getParam("stt");
        if($model->stt=='1'){
            $model->tm=0;
//            $model->tm = $tien;
//            if($ungtien_hoantra=='0'){
//                $model->tm=$tien*(-1);
//            }
        }
        else{
            $tm=Yii::app()->db->createCommand("select tm from ung_tien where user_id=".Yii::app()->session['user_id']." and stt=".($model->stt-1))->queryScalar();
            if($tm==FALSE||$tm==''){
                $tm=0;
            }
            $model->tm=$tm;
//            if($ungtien_hoantra=='1'){
//                $model->tm=$tien+$tm;
//            }
//            else{
//                $model->tm=$tm-$tien;
//            }
        }
        $model->user_id=Yii::app()->session['user_id'];


        $model->save(FALSE);
        
        
        /**
         * update tat ca các record thuchi trong tháng hiện tại
         */
//        $created_at=  explode(" ", $model->created_at);
//        $created_at=$created_at[0];
//        $created_at=  explode("-", $created_at);
//        $year=$created_at[0];
//        $month=$created_at[1];
//        ThuChi::update_records($month, $year);
        /**
         * ghi log
         */
//        $thuchi_history_model=new ThuchiHistory();
//        $thuchi_history_model->thuchi_id=$model->id;
//        $thuchi_history_model->created_at=$model->created_at;
//        $thuchi_history_model->thu=$model->thu;
//        $thuchi_history_model->chi=$model->chi;
//        $thuchi_history_model->tm=$model->tm;
//        $thuchi_history_model->type=$model->type;
//        $thuchi_history_model->content=$model->content;
//        $thuchi_history_model->log_date=date("Y-m-d H:i:s");
//        $thuchi_history_model->user_id=Yii::app()->session['user_id'];
//        $thuchi_history_model->save(false);
        
    }

    public function actionConfirm() {
        $user = User::model()->findByPk(Yii::app()->session['user_id']);
        $xac_nhan=Yii::app()->request->getParam("xac_nhan");        
        $id=Yii::app()->request->getParam("id");
        $model=  Ungtien::model()->findByPk($id);
        $model->ung_tien=  str_replace(".", "", $model->ung_tien);
        $model->hoan_tra=  str_replace(".", "", $model->hoan_tra);
        $model->tm=  str_replace(".", "", $model->tm);
        $model->created_at=Yii::app()->db->createCommand("select created_at from ung_tien where id=$id")->queryScalar();
        if($xac_nhan=='1'){
            $yes_no='đồng ý';
        }
        else{
            $yes_no='không đồng ý';
        }
        $lydo=$user->danh_xung . " " . $user->full_name." $yes_no".' "'.Yii::app()->request->getParam("lydo").'" ('. date("Y.m.d").")";
        $model->xac_nhan=$lydo;
        $model->dong_y=$xac_nhan;
        $model->type = Yii::app()->request->getParam("thanh_toan",NULL);
        if($model->stt=='1'){
            if($xac_nhan=='1'){
                if($model->ung_tien!='0'&&$model->ung_tien!=""){
                    $model->tm = $model->ung_tien;
                }
                else{
                    $model->tm = $model->hoan_tra*(-1);
                }
            }
            
        }
        else{
            if($xac_nhan=='1'){
                $tm=Yii::app()->db->createCommand("select tm from ung_tien where user_id=".$model->user_id." and stt=".($model->stt-1))->queryScalar();
                if($tm==FALSE||$tm==''){
                    $tm=0;
                }
                if($model->ung_tien!='0'&&$model->ung_tien!=""){
                    $model->tm=$model->ung_tien+$tm;
                }
                else{
                    $model->tm=$tm-$model->hoan_tra;
                }
            }
            
        }
        $model->save(FALSE);
    }
    

}
