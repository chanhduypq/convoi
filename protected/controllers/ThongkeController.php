<?php

class ThongkeController extends Controller {
    

    
    public function actionIndex() {
        $month=Yii::app()->request->getParam("month",date("m"));
        $year=Yii::app()->request->getParam("year",date("Y"));
        $only_year=Yii::app()->request->getParam("only_year",date("Y"));

        $params['month']=$month;
        $params['year']=$year;
        $params['only_year']=$only_year;
        
        $array[]=Yii::app()->db->createCommand("SELECT min(year(created_at)) as min from bill")->queryScalar();
        $array[]=Yii::app()->db->createCommand("SELECT min(year(created_at)) as min from bill_chi_phi")->queryScalar();
        $array[]=Yii::app()->db->createCommand("SELECT min(year(created_at)) as min from bill_input")->queryScalar();
        $from_year=  min($array);
        $params['from_year']=$from_year;
        /**
         * 
         */
        //thương mại
        $thuong_mai=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from bill where MONTH(created_at)=$month AND YEAR(created_at)=$year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        if($thuong_mai==""){
            $thuong_mai=0;
        }
        //sản xuất và dịch vu
        $san_xuat_va_dich_vu=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from sxdv where MONTH(created_at)=$month AND YEAR(created_at)=$year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        if($san_xuat_va_dich_vu==""){
            $san_xuat_va_dich_vu=0;
        }
        //không xuất hóa đơn
        $khong_xuat_hoa_don=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from kxhd where MONTH(created_at)=$month AND YEAR(created_at)=$year")->queryScalar();
        if($khong_xuat_hoa_don==""){
            $khong_xuat_hoa_don=0;
        }
        //lãi suất
        $lai_suat=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from lai_suat where MONTH(created_at)=$month AND YEAR(created_at)=$year")->queryScalar();
        if($lai_suat==""){
            $lai_suat=0;
        }
        //nhập kho kinh doanh
        $nhap_kho_kinh_doanh=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from bill_input where MONTH(created_at)=$month AND YEAR(created_at)=$year AND (is_international=0 or is_international is null) AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        if($nhap_kho_kinh_doanh==""){
            $nhap_kho_kinh_doanh=0;
        }
        //chi phí & dịch vụ có hóa đơn
        $chi_phi_dich_vu_co_hd=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as tax_sum from bill_chi_phi where MONTH(created_at)=$month AND YEAR(created_at)=$year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        if($chi_phi_dich_vu_co_hd==""){
            $chi_phi_dich_vu_co_hd=0;
        }
        /**
         * doanh thu ok
         */
        $doanh_thu=$thuong_mai+$san_xuat_va_dich_vu;
        $params['doanh_thu']=$doanh_thu;
        /**
         * nhập kho ok
         */
        $nhap_kho=$nhap_kho_kinh_doanh;
        $params['nhap_kho']=$nhap_kho;
        /**
         * chi phí ok
         */
        $chi_phi_dich_vu_khd=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as tax_sum from chi_phi_khd where MONTH(created_at)=$month AND YEAR(created_at)=$year")->queryScalar();
        if($chi_phi_dich_vu_khd==""){
            $chi_phi_dich_vu_khd=0;
        }
        $chiphi=$chi_phi_dich_vu_co_hd+$chi_phi_dich_vu_khd;
        $params['chiphi']=$chiphi;
        /**
         * tờ khai ok
         */
        $v1=Yii::app()->db->createCommand("select sum(gia_tri_hang_hoa_vnd) as all_sum from bill_input where MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_international=1 AND payment_method_id3<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        $v2=Yii::app()->db->createCommand("select sum(chi_phi_ngan_hang_vnd) as all_sum from bill_input where MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_international=1 AND payment_method_id4<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        $v3=Yii::app()->db->createCommand("select sum(tax_sum) as all_sum from bill_input where MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_international=1 AND payment_method_id5<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        if($v1==""){
            $v1=0;
        }
        if($v2==""){
            $v2=0;
        }
        if($v3==""){
            $v3=0;
        }
        $tokhai=$v1+$v2+$v3;
        $params['tokhai']=$tokhai;
        /**
         * tổng tờ khai, chi phí, nhập kho ok
         */
        $nhap_kho_chiphi_tokhai=$nhap_kho+$chiphi+$tokhai;
        $params['nhap_kho_chiphi_tokhai']=$nhap_kho_chiphi_tokhai;
        /**
         * thuế nhập kho ok
         */
        $v1=Yii::app()->db->createCommand("select sum(tax_sum) as tax_sum from bill_input where MONTH(created_at)=$month AND YEAR(created_at)=$year AND (is_international=0 OR is_international is null) AND id IN (select bill_id from bill_input_detail)")->queryScalar();
        $v2=Yii::app()->db->createCommand("select sum(tax_sum) as tax_sum from bill_chi_phi where MONTH(created_at)=$month AND YEAR(created_at)=$year")->queryScalar();
        if($v1==""){
            $v1=0;
        }
        if($v2==""){
            $v2=0;
        }
        $thue_nhap_kho=$v1+$v2+$v3;
        $params['thue_nhap_kho']=$thue_nhap_kho;
        /**
         * thuế xuất kho ok
         */            
        $v1=Yii::app()->db->createCommand("select sum(tax_sum) as tax_sum from bill where MONTH(created_at)=$month AND YEAR(created_at)=$year")->queryScalar();
        $v2=Yii::app()->db->createCommand("select sum(tax_sum) as tax_sum from sxdv where MONTH(created_at)=$month AND YEAR(created_at)=$year")->queryScalar();
        if($v1==""){
            $v1=0;
        }
        if($v2==""){
            $v2=0;
        }
        $thue_xuat_kho=$v1+$v2;
        $params['thue_xuat_kho']=$thue_xuat_kho;
        /**
         * thuế phải đóng ok
         */
        $thue_phai_dong=$thue_xuat_kho-$thue_nhap_kho;
        $params['thue_phai_dong']=$thue_phai_dong;
        /**
         * doanh thu bán hàng ok
         */
        $v1=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from bill where MONTH(created_at)=$month AND YEAR(created_at)=$year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        $v2=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from sxdv where MONTH(created_at)=$month AND YEAR(created_at)=$year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        $v3=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from kxhd where MONTH(created_at)=$month AND YEAR(created_at)=$year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        //confirm lại
        $v4=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from lai_suat where MONTH(created_at)=$month AND YEAR(created_at)=$year")->queryScalar();
        if($v1==""){
            $v1=0;
        }
        if($v2==""){
            $v2=0;
        }
        if($v3==""){
            $v3=0;
        }
        if($v4==""){
            $v4=0;
        }
        $doanh_thu_ban_hang=$v1+$v2+$v3+$v4;
        $params['doanh_thu_ban_hang']=$doanh_thu_ban_hang;
        /**
         * lợi nhuận
         */
        //A
        $a=$doanh_thu_ban_hang;
        //B ngoại trừ những record không thanh toán
        $v1=Yii::app()->db->createCommand("select sum(gia_tri_hang_hoa_vnd) as all_sum from bill_input where MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_international=1 AND payment_method_id3<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        $v2=Yii::app()->db->createCommand("select sum(chi_phi_ngan_hang_vnd) as all_sum from bill_input where MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_international=1 AND payment_method_id4<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        $v3=Yii::app()->db->createCommand("select sum(tax_sum) as all_sum from bill_input where MONTH(created_at)=$month AND YEAR(created_at)=$year AND is_international=1 AND payment_method_id5<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        if($v1==""){
            $v1=0;
        }
        if($v2==""){
            $v2=0;
        }
        if($v3==""){
            $v3=0;
        }
        //
        $v4=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from bill_input where MONTH(created_at)=$month AND YEAR(created_at)=$year AND (is_international=0 or is_international is null) AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        if($v4==""){
            $v4=0;
        }
        //
        $v5=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as tax_sum from bill_chi_phi where MONTH(created_at)=$month AND YEAR(created_at)=$year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
        if($v5==""){
            $v5=0;
        }
        //
        $b=$v1+$v2+$v3+$v4+$v5+$chi_phi_dich_vu_khd;
        //lợi nhuận ok
        $loi_nhuan=$a-$b;
        if($thue_phai_dong>0){
            $loi_nhuan-=$thue_phai_dong;
        }
        $params['loi_nhuan']=$loi_nhuan;
        /**
         * chart
         */
        //doanh thu
        $rows=Yii::app()->db->createCommand("select MONTH(created_at) as thang,sum(sum)+sum(tax_sum) as all_sum from bill where YEAR(created_at)=$only_year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN." group by MONTH(created_at) order by MONTH(created_at) ASC")->queryAll();
        if(count($rows)==0){
            for($i=0;$i<12;$i++){
                $doanh_thus[]=0;
            }
        }
        else{
            $start=$rows[0]['thang'];
            $end=$rows[count($rows)-1]['thang'];
            for($i=1;$i<$start;$i++){
                $doanh_thus[]=0;
            }
            for($i=$start,$j=0;$i<=$end;$i++){
                $v2=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from sxdv where MONTH(created_at)=$i AND YEAR(created_at)=$only_year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
                $v3=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from kxhd where MONTH(created_at)=$i AND YEAR(created_at)=$only_year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
                //confirm lại
                $v4=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as all_sum from lai_suat where MONTH(created_at)=$i AND YEAR(created_at)=$only_year")->queryScalar();
                if($v2==""){
                    $v2=0;
                }
                if($v3==""){
                    $v3=0;
                }
                if($v4==""){
                    $v4=0;
                }
                $doanh_thus[]=$rows[$j++]['all_sum']+$v2+$v3+$v4;
            }
            for($i=$end+1;$i<=12;$i++){
                $doanh_thus[]=0;
            }
        }
        $params['doanh_thus']=$doanh_thus;
        //chi phí
        for($i=1;$i<=12;$i++){
            $value1=Yii::app()->db->createCommand("select sum(sum)+sum(tax_sum) as all_sum from bill_input where MONTH(created_at)=$i AND YEAR(created_at)=$only_year AND (is_international=0 or is_international is null) AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();        
            $value2=Yii::app()->db->createCommand("select sum(sum)+sum(tax_sum) as tax_sum from bill_chi_phi where MONTH(created_at)=$i AND YEAR(created_at)=$only_year AND payment_method_id<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
            $chi_phi_dich_vu_khd=Yii::app()->db->createCommand("select sum(sum_and_sumtax) as tax_sum from chi_phi_khd where MONTH(created_at)=$i AND YEAR(created_at)=$only_year")->queryScalar();
            if($chi_phi_dich_vu_khd==""){
                $chi_phi_dich_vu_khd=0;
            }
            $v1=Yii::app()->db->createCommand("select sum(gia_tri_hang_hoa_vnd) as all_sum from bill_input where MONTH(created_at)=$i AND YEAR(created_at)=$only_year AND is_international=1 AND payment_method_id3<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
            $v2=Yii::app()->db->createCommand("select sum(chi_phi_ngan_hang_vnd) as all_sum from bill_input where MONTH(created_at)=$i AND YEAR(created_at)=$only_year AND is_international=1 AND payment_method_id4<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
            $v3=Yii::app()->db->createCommand("select sum(tax_sum) as all_sum from bill_input where MONTH(created_at)=$i AND YEAR(created_at)=$only_year AND is_international=1 AND payment_method_id5<>".PaymentMethod::KHONG_THANH_TOAN)->queryScalar();
            if($v1==""){
                $v1=0;
            }
            if($v2==""){
                $v2=0;
            }
            if($v3==""){
                $v3=0;
            }
            $value3=$v1+$v2+$v3;
            $chiphis[]=$value1+$value2+$value3+$chi_phi_dich_vu_khd;
        }       
        $params['chiphis']=$chiphis;
        //
        $max=  (max($doanh_thus)>max($chiphis))?max($doanh_thus):  max($chiphis);
        $scale_step_width=  ceil($max/7);
        $scale_step_width=ceil($scale_step_width/10000000)*10000000;
        $params['scale_step_width']=$scale_step_width;
        /**
         * tài khoản tiền mặt
         */
        $tm_init=Yii::app()->session['tm']= Yii::app()->db->createCommand()->select("tm")->from("thuchi")->where("MONTH(created_at)=".date('m')." AND YEAR(created_at)=".date('Y')." AND is_init=1")->order("created_at DESC")->queryScalar();
        if($tm_init==FALSE||$tm_init==""){
            $tm_init=0;
        }
        $row=Yii::app()->db->createCommand()->select("sum(thu) as sum_thu,sum(chi) as sum_chi")->from("thuchi")->where("MONTH(created_at)=".date('m')." AND YEAR(created_at)=".date('Y')." AND (is_init is null OR is_init=0)")->queryRow();
        if($row['sum_thu']==""){
            $row['sum_thu']=0;
        }
        if($row['sum_chi']==""){
            $row['sum_chi']=0;
        }
        $tai_khoan_tien_mat=$tm_init+$row['sum_thu']-$row['sum_chi'];
        $params['tai_khoan_tien_mat'] =$tai_khoan_tien_mat;
       
        $this->render('index',$params);
    }
   

}
