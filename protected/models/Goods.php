<?php

/**
 * 
 */
class Goods extends CActiveRecord {

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
        return 'goods';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('unit_full_name,goods_full_name,goods_short_hand_name,tax', 'required', 'message' => 'Vui lòng nhập {attribute}.'),
            array('goods_full_name_for_unique_validate', 'unique', 'criteria' => array(
                    'condition' => '`unit_full_name`=:unit_full_name',
                    'params' => array(
                        ':unit_full_name' => $this->unit_full_name
                    )
                ),
                'message' => 'Đã tồn tại hàng hóa này rồi. Vui lòng nhập lại.'
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'unit_full_name' => 'Đơn vị tính',
            'goods_full_name' => 'Tên đầy đủ',
            'goods_short_hand_name' => 'Tên viết tắt',
            'tax' => 'Thuế VAT',
            'goods_full_name_for_unique_validate' => 'Hàng hóa',
        );
    }

    public function afterSave() {
        parent::afterSave();
        /**
         * nếu tạo một loạt hàng hóa cùng tên đầy đủ và tên viết tắt nhưng đơn vị tính và giá tiền khác nhau
         * thi khởi tạo một group hàng hóa mới
         * group_id mới này dc lấy bằng chính hàng hóa ứng với đơn vị tính đầu tiên         
         */
        $group_id = Yii::app()->db->createCommand()
                ->select("group_id")
                ->from("goods")
                ->where("goods_full_name=:goods_full_name and goods_short_hand_name=:goods_short_hand_name and id <> " . $this->id, array(
                    ':goods_full_name' => $this->goods_full_name,
                    ':goods_short_hand_name' => $this->goods_short_hand_name,
                        )
                )
                ->queryScalar();
        if ($group_id != FALSE) {
            Yii::app()->db->createCommand()->update("goods", array('group_id' => $group_id), 'id=' . $this->id);
        } else {
            Yii::app()->db->createCommand()->update("goods", array('group_id' => $this->id), 'id=' . $this->id);
        }
        if($this->getIsNewRecord()){
            $goods_left=new GoodslLeft();
            $goods_left->setIsNewRecord(true);
            $goods_left->id=  $this->id;
            $goods_left->so_nguoi_nuoc_ngoai=$goods_left->so_to_khai=$goods_left->quantity_left=$goods_left->avg_price=$goods_left->so_khach_hang=$goods_left->so_hoa_don=0;
            $goods_left->save(FALSE);
        }
    }
    public function afterDelete() {
        parent::afterDelete();
        GoodslLeft::model()->deleteByPk($this->id);
    }
    /**
     * 
     * @param string $for_bill cái này dùng để xác định, lấy danh sách hàng hóa để user chọn hàng hóa cho việc bán hàng hay nhập kho (tai các page update/create hóa đơn nhập kho/bán hàng)
     * @return array
     */
    public static function getAllGoods($for_bill='0'){
        $select= Yii::app()->db->createCommand()
                ->select();
        $select->from("goods");
        if($for_bill=='1'){
            $select->where("id IN (select id from goods_left where quantity_left>0)");//số lượng tồn kho phải lớn hơn 0
        }
        $select->group("group_id");
        $goods=$select->queryAll();     
        return $goods;
    }
    /**
     * get tất cả hàng hóa nhập khẩu
     * @return array
     */
    public static function get_all_import_goods() {                
        $goods= Yii::app()->db->createCommand()
                ->select()
                ->from("goods")
                ->where("is_international=1")
                ->group("group_id")
                ->queryAll(); 
        return $goods;
    }

}
