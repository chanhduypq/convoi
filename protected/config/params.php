<?php

// this contains the application parameters that can be maintained via GUI
return array(
	/**
         * tiêu đề hiển thị trên trình duyệt
         */
	'title'=>'MNS INVOICE - Hóa Đơn',
        /**
         * footer của trang web (nội dung phía dưới cùng của trsng web)
         */
	'copyrightInfo'=>'<a>© mns invoice</a>',
        /**
         * 
         */
        'languages' => array( 'en' => 'English', 'vi' => 'Vietnamese'),
        /**
         * số chữ số tối đa của một số hóa đơn
         */
        'NUMBER_OF_DIGIT_OF_BILLNUMBER'=>7,        
        /**
         * số hóa đơn khởi tạo
         * Ví dụ: 
         *      500 thi số hóa đơn khởi tạo là 0000500
         *      2000 thi số hóa đơn khởi tạo là 0002000
         */
        'INIT_BILL_NUMBER'=>'500',
        /**
         * 
         */
        'DOMAIN_NAME'=>'http://convoi.mns.local',
        /**
         * 
         */
        'PATH_ROOT'=>'/xampp/htdocs/2016',
        /**
         * danh sách hóa đơn, hàng hóa, khách hàng se có nhiều kết quả
         * hệ thống se phân chia thành nhiều trang khác nhau: trang 1, 2, 3,....
         * số kết quả hiển thị trên 1 trang
         */
         'number_of_items_per_page'=>20,
        /**
         * text hiển thị tại các button save, close tại các popup
         */
        'text_for_button_save'=>'Lưu',
        'text_for_button_close'=>'Đóng',
        /**
         * text hiển thị cho label nhà cung cấp tại menu và tại những chỗ khác
         */
        'label_for_supplier'=>'Nhà cung ứng',
        /**
         * 
         */
        'key_list_of_get_method'=>array(
                                        'id',
                                        'socai_id',
                                        'payment_method_type',
                                        'socai_ids',
                                  ),
        /**
         * các page có chức năng search
         */
        'controller_list_for_search'=>array(
                                        'invoiceinputfull',
                                        'invoicefull',
                                        'sxdvfull',
                                        'invoicechiphifull', 
                                        'customerfull',
                                        'customersxdvfull',
                                        'customerkxhdfull',
                                        'supplierfull',
                                        'goodsinputfull',
                                        'goodsfull',
                                        'goodsleftfull',  
                                        'international',
                                        'internationalinput',
                                        'thuchi',
                                        'taikhoanacb',
                                        'socai',
                                        'kxhdfull',
                                        'laisuatfull',
                                        'chiphikhdfull',
                                  ),
        /**
         * các page có nút THÊM MỚI ở header
         */
        'controller_list_has_create_button'=>array(
                                        'invoiceinputfull',
                                        'invoicefull',
                                        'sxdvfull',
                                        'invoicechiphifull',
                                        'customerfull',
                                        'customersxdvfull',
                                        'customerkxhdfull',
                                        'supplierfull',
                                        'goodsinputfull',
                                        'goodsfull',
                                        'goodsleftfull', 
                                        'user', 
                                        'international',
                                        'internationalinput',
                                        'kxhdfull',
                                        'laisuatfull',
                                        'chiphikhdfull',
                                  ),
        /**
         * các page hiển thị danh sách thông tin
         */
        'controller_list_for_show_list'=>array(
                                        'invoiceinputfull',
                                        'invoicefull',
                                        'sxdvfull', 
                                        'invoicechiphifull',
                                        'customerfull',
                                        'customersxdvfull',
                                        'customerkxhdfull',
                                        'supplierfull',
                                        'goodsinputfull',
                                        'goodsfull',
                                        'goodsleftfull', 
                                        'user', 
                                        'international',
                                        'internationalinput',
                                        'thuchi',
                                        'taikhoanacb',
                                        'socai',
                                        'kxhdfull',
                                        'laisuatfull',
                                        'chiphikhdfull',
                                  ),
);
