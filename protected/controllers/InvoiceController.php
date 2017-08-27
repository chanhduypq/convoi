<?php

class InvoiceController extends Controller {

    public function actionPreview() {
        if (isset(Yii::app()->session['file_name'])) {
            $file_name = Yii::app()->session['file_name'];
            $this->renderPartial("preview", array('file_name' => $file_name));
        }
    }

    public function actionPrint() {
        if (isset(Yii::app()->session['file_name'])) {
            $file_name = Yii::app()->session['file_name'];
            $this->renderPartial("print", array('file_name' => $file_name));
        }
    }

    public function actionDownload() {
        if (isset(Yii::app()->session['file_name'])) {
            $file_name = Yii::app()->session['file_name'];
            $temp=  explode("_", $file_name);
            $bill_number=$temp[1];
            $date=$temp[2];
            $branch_short_hand_name=Yii::app()->db->createCommand()
                    ->select("branch.short_hand_name")
                    ->from("bill")
                    ->join("branch", "bill.branch_id=branch.id")
                    ->where("bill.bill_number=".intval($bill_number))
                    ->queryScalar();
            $file_name_for_show=$bill_number."_".$date."_".$branch_short_hand_name.".png";
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name_for_show . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name));
            readfile($file_name);
            exit;
        }
    }

}
