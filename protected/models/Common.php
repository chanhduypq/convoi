<?php
class Common{
    const TIEN_MAT='1';
    const CHUYEN_KHOAN_ACB='2';
    
    public static function update_complete_and_socai($model,$thu_or_chi,$id_type,$giao_dich){
        /**
         * update complete
         */
        $sum=Yii::app()->db->createCommand()
                ->select("sum($thu_or_chi)")
                ->from("socai")
                ->where("$id_type=".$model->id)
                ->queryScalar();
        if($sum==FALSE||$sum==''){
            $sum=0;
        }
        if($sum==$model->sum_and_sumtax){            
            if($model->is_complete!=1){
                $model->is_complete=1;
                $model->save(FALSE);
            }

            $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("$id_type=" . $model->id . " and $thu_or_chi<>0")
                    ->queryAll();
            if(!is_array($rows)||count($rows)==0){
                return;
            }
            if(count($rows)==1){
                $trang_thai = '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai' where id=" . $rows[0]['id'])->execute();
            }
            else{

                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 26px;width: 20px;height: 20px;">' . ($i+1) . '</div>' .
                                '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>'.
                                '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>';
                    Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai' where id=" . $r['id'])->execute();
                }
            }
            
            
        }
        else{
            if($model->is_complete!=0){
                $model->is_complete=0;
                $model->save(FALSE);
            }
            
            $rows = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("socai")
                    ->where("$id_type=" . $model->id . " and $thu_or_chi<>0")
                    ->queryAll();
            if(!is_array($rows)||count($rows)==0){
                return;
            }
            if(count($rows)==1){
                $trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">1</div>'.
                                   '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
                Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai' where $id_type=" . $model->id." and $thu_or_chi<>0")->execute();           
            }
            else{
                for($i=0;$i<count($rows);$i++){
                    $r=$rows[$i];
                    $trang_thai=$r['trang_thai'];
                    $trang_thai=  str_replace("26px", "47px", $trang_thai);
                    $trang_thai=  str_replace('<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/complete.png"/>', "", $trang_thai);
                    Yii::app()->db->createCommand("update socai set trang_thai='$trang_thai' where id=" . $r['id'])->execute();
                }
                
            }
            
            //vì số tiền vừa update lớn hơn số tiền trong sổ cái, nên phai insert 1 record vào sổ cái.
            $content=$rows[0]['content']; 
            $tham_chieu=$rows[0]['tham_chieu'];
            $trang_thai = '<div style="position: absolute;margin-top: -40px;margin-left: 47px;width: 20px;height: 20px;">' . (count($rows)+1) . '</div>' .
                    '<img style="width: 39px;height: 39px;" src="' . Yii::app()->theme->baseUrl . '/images/icon/socai/not_complete.png"/>';
            $count=Yii::app()->db->createCommand("select count(*) from socai where $id_type=" . $model->id." and $thu_or_chi=0")->queryScalar();
            if($count==FALSE||$count==""||$count==0){
                Yii::app()->db->createCommand("insert into socai ("
                            . "thu,"
                            . "chi,"
                            . "created_at,"
                            . "$id_type,"
                            . "giao_dich,"
                            . "thanh_toan,"
                            . "tham_chieu,"
                            . "content,"
                            ."tm,"
                            . "trang_thai"
                            . ") "
                            . "values ("
                            . "0,"
                            . "0,"
                            . "'" . FunctionCommon::get_last_time_of_current_month() . "',"
                            . $model->id . ","
                            . "'$giao_dich',"
                            . PaymentMethod::CHUA_THANH_TOAN . ","
                            . "'" . $tham_chieu . "',"
                            . "'" . $content . "',"
                            .($model->sum_and_sumtax-$sum).","
                            . "'" . $trang_thai . "'"
                            . ")")
                    ->execute();
            }
            
            
        }

    }
}

