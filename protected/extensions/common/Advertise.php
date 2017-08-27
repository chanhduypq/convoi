<?php

class Advertise extends CApplicationComponent {
    /**
     * 
     */
    const LEFT_POSITION='left';
    const RIGHT_POSITION='right';
    const TOP_POSITION='top';
    const BOTTOM_POSITION='bottom';
    const CENTER_POSITION='center';
    /**
     * 
     */
    const HTML_TYPE='html';
    const YOUTUBE_TYPE='youtube';
    const IMAGE_TYPE='image';
    /**
     *
     * @var string 
     */
    private $run_time;    
    /**
     *
     * @var string 
     */
    private $current_time_at_server;
    /**
     * 
     */
    public function __construct() {
        /**
         * get thời gian chạy quảng cáo
         */
        $row=Yii::app()->db->createCommand()
                ->select(
                        array(                    
                            'run_time',                                   
                            'run_date',                                   
                        )
                )
                ->from("advertise")
                ->join("advertise_ip", "advertise_ip.advertise_id=advertise.id")
                ->join("ip", "ip.id=advertise_ip.ip_id")
                ->where("ip.ip='".IP."'")
                ->queryRow()
                ;        
        $temp=  explode(" ", $row['run_date']);
        $run_date=$temp[0];            
        
        $temp=  explode(" ", $row['run_time']);
        $this->run_time=$temp[1];
        
        $this->run_time=  $run_date." ".  $this->run_time;
        /**
         * get thời gian hiện tại trên server
         * tránh trường hợp PC/Laptop/mobile của user set sai thời gian thực
         */
        $this->current_time_at_server=Yii::app()->db
                ->createCommand("select NOW() as current_time_at_server")
                ->queryScalar();
        
    }

    /**
     * @param string $position_type
     * @param array $customize
     * @param int $second
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function runHtml($position_type,array $customize,$second) { 
        /**
         * 
         */
        if($position_type==self::CENTER_POSITION){
            $html=isset($customize['html_string'])?$customize['html_string']:'MNS';        
        }      
              
        ?>

        <script type="text/javascript">
            <?php 
            if($position_type==self::CENTER_POSITION){
            ?>
            var ads = {
                    'ad_1': {
                        'type': 'html',
                        'html': '<?php echo str_ireplace(array("\r","\n",'\r','\n'),'', $html);?>',
                    }

                };

            <?php
            }
            
            $this->runSchedule($second);
            ?>
            function runAdvertising(){
                run=true;
                <?php
                if($position_type==self::CENTER_POSITION){
                    $this->callFunctionAdPopupPro();
                }
                else{
                    $this->callFunctionMeerkat($position_type, $customize);
                }
                
                ?>
            }
        </script>
        <?php

    }
    /**
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function callFunctionAdPopupPro(){?>
        $('body').adPopupPro({
             ads: ads,
             show_type: 'always',
             show_chance: 1,
             mobile: true,
             tablets: true,
             min_screen_size: '0x0',
             internet_explorer: 'normal',
             os: '',
             cookie_id: '1234',
             width: 400,
             height: 300,
             show_close_button: true,
             close_button: 'dark',
             popup_color: 'light',
             popup_shadow: true,
             overlay_opacity: 0.5,
             overlay_color: 'light',
             overlay_close: true,
             escape_close: true,
             auto_close_after: false

        });
    <?php        
    }
    /**
     * @param string $position_type        
     * @param array $customize
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function callFunctionMeerkat($position_type,array $customize){
        $html=isset($customize['html_string'])?$customize['html_string']:'MNS';        
        $width=isset($customize['width'])?$customize['width']:'150';
        $height=isset($customize['height'])?$customize['height']:'150';
        $animation_speed=isset($customize['animation_speed'])?$customize['animation_speed']:'500';   
        $background=isset($customize['background'])?$customize['background']:'#00cccc';               
        ?>
        $('.meerkat').destroyMeerkat();
        $('.meerkat').meerkat({
                background: '<?php echo $background;?>',
                <?php 
                if($position_type==self::TOP_POSITION||$position_type==self::BOTTOM_POSITION){
                ?>
                height: '<?php echo $height;?>px',
                width: '100%',
                <?php
                }
                else if($position_type==self::LEFT_POSITION||$position_type==self::RIGHT_POSITION){
                ?>
                height: '100%',
                width: '<?php echo $width;?>px',
                <?php
                }
                ?>
                position: '<?php echo $position_type;?>',
                close: '.close-meerkat',
                dontShowAgain: '.dont-show',
                animationIn: 'slide',
                animationSpeed: <?php echo $animation_speed;?>,
                removeCookie: '.reset'
        }).addClass('pos-bot').addClass('pos-<?php echo $position_type;?>');
        $('.meerkat .adsense').replaceWith(function(){
                $('.meerkat').append('<div class="adsense"><?php echo str_ireplace(array("\r","\n",'\r','\n'),'', $html);?></div>');
        });                                        
        $('.<?php echo $position_type;?>_').show();
    <?php        
    }
    

    /**
     * @param string $position_type
     * @param array $customize
     * @param int $second
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function runYoutube($position_type,array $customize,$second) {
        /**
         * 
         */        
        
        if($position_type==self::CENTER_POSITION){
            $youtube_url=isset($customize['youtube_url'])?$customize['youtube_url']:'';       
        }   
        ?>
        <script type="text/javascript">
            <?php 
            if($position_type==self::CENTER_POSITION){
            ?>
            var ads = {
                    'ad_1': {
                        'type': 'youtube',
                        'video': '<?php echo $youtube_url;?>',
                    }

                }

            <?php
            }
            
            $this->runSchedule($second);
            ?>
            function runAdvertising(){
                run=true;
                <?php
                if($position_type==self::CENTER_POSITION){
                    $this->callFunctionAdPopupPro();
                }
                else{
                    $this->callFunctionMeerkat($position_type, $customize);
                }
                
                ?>
            }
        </script>
        <?php

    }
    /**
     * @param string $position_type
     * @param array $customize
     * @param int $second
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function runImage($position_type,array $customize,$second) {
        /**
         * 
         */        
        if($position_type==self::CENTER_POSITION){
            $image_src=isset($customize['image_src'])?$customize['image_src']:'';   
            $image_url=isset($customize['image_url'])?$customize['image_url']:NULL;      
        }   
        
        
        ?>
        <script type="text/javascript">

            <?php 
            if($position_type==self::CENTER_POSITION){
            ?>
            var ads = {
                    'ad_1': {
                        'type': 'image',
                        'src': '<?php echo $image_src;?>',                        
                        <?php 
                        if($image_url!=NULL){
                        ?>
                        'link': '<?php echo $image_url;?>',
                        <?php
                        }
                        ?>
                    }

                };
                
            <?php
            }
            
            $this->runSchedule($second);
            ?>

            function runAdvertising(){
                run=true;
                <?php
                if($position_type==self::CENTER_POSITION){
                    $this->callFunctionAdPopupPro();
                }
                else{
                    $this->callFunctionMeerkat($position_type, $customize);
                }
                
                ?>
            }
            
            

            



        </script>
        <?php

    }
    /**
     * include file css, js ứng với jquery plugin adpopup   
     * @param string $position_type
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function initJsCssForAdvertise($position_type){
        if($position_type==self::CENTER_POSITION){?>
            <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->baseUrl; ?>/themes/css/ad/adpopup-pro.min.css" />
            <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/themes/js/ad/adpopup-pro.min.js"></script>
        <?php    
        }
        else{
        ?>
            <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->baseUrl; ?>/themes/css/ad/main.css" />
            <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/themes/js/ad/jquery.meerkat.1.3.js"></script>     
            <?php
        }
            
    }
    
    /**
     * bắt đầu chạy quảng cáo
     * @param string $position_type   
     * @param string $advertise_type  
     * @param int $second 
     * @param array $customize
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function run($position_type,$advertise_type,$second,array $customize) {
        /**
         * 
         */
        $this->initJsCssForAdvertise($position_type);
        /**
         * 
         */       
        if($position_type!=self::CENTER_POSITION){
        ?>
            <div id="meerkat-wrap" style="position: fixed; z-index: 10000; width: 100%; height: 120px; bottom: 0px; display: none;">
                <div id="meerkat-container" style="height: 120px; background-color: #00cccc;">
                    <div class="meerkat pos-bot" style="display: block;">
                        <a href="" class="close-meerkat">close</a>
                        <a class="dont-show">Don't Show Again</a>	
                        <div class="adsense"></div>	
                    </div>                            
                </div>                        
            </div>
        <?php
        }
        if($advertise_type==self::HTML_TYPE){
            $this->runHtml($position_type,$customize,$second);
        }
        else if($advertise_type==self::YOUTUBE_TYPE){
            $this->runYoutube($position_type,$customize,$second);
        }
        else if($advertise_type==self::IMAGE_TYPE){
            $this->runImage($position_type,$customize,$second);
        }
//        if($position_type!=self::CENTER_POSITION){
//            $this->runNo($position_type, $customize, $second);
//        }
//        else{
//            if($advertise_type==self::HTML_TYPE){
//                $this->runHtml($position_type,$customize,$second);
//            }
//            else if($advertise_type==self::YOUTUBE_TYPE){
//                $this->runYoutube($position_type,$customize,$second);
//            }
//            else if($advertise_type==self::IMAGE_TYPE){
//                $this->runImage($position_type,$customize,$second);
//            }
//        }    
        
           

    }
    /**
     * bắt đầu chạy quảng cáo
     * @param array $position_type
     * @param array $customize
     * @param int $second
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function runNo($position_type,array $customize,$second) {      
        /**
         * 
         */
        $html=isset($customize['html_string'])?$customize['html_string']:'MNS';        
        $width=isset($customize['width'])?$customize['width']:'150';
        $height=isset($customize['height'])?$customize['height']:'150';
        $animation_speed=isset($customize['animation_speed'])?$customize['animation_speed']:'500';   
        $background=isset($customize['background'])?$customize['background']:'#00cccc';
        ?>
        
        <script type="text/javascript">
            
            <?php
            $this->runSchedule($second);
            ?>           

            function runAdvertising(){
                run=true;
                
                

            }
              
            
            



        </script>
        <?php

    }
    /**
     * decalre 2 variable: run_time, current_time
     * run_time: thời gian chạy quảng cáo
     * current_time: thời gian thật được lấy từ server (tránh trường hợp thời gian của PC hay laptop hay mobile của user bị sai)    
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function declareRunTimeAndCurrentTime(){
        /**
         * get date, time chạy quảng cáo
         */
        $temp=  explode(" ",$this->run_time);
        $date=$temp[0];
        $time=$temp[1];
        /**
         * tách ngày tháng năm giờ phút ra
         */
        $temp=  explode("-",$date);
        $day=$temp[2];
        $month=$temp[1];
        $year=$temp[0];

        $temp=  explode(":",$time);            
        $minute=$temp[1];
        $hour=$temp[0];
        ?>
        var run_time = new Date();
        run_time.setDate(<?php echo (int)$day;?>);
        run_time.setMonth(<?php echo (int)$month;?>);
        run_time.setYear(<?php echo (int)$year;?>);
        run_time.setHours(<?php echo (int)$hour;?>);
        run_time.setMinutes(<?php echo (int)$minute;?>);
        run_time.setSeconds(0);
        <?php    
        /**
         * get date, time của thời gian thật (thời gian hiện tại)
         */
        $temp=  explode(" ",$this->current_time_at_server);
        $date=$temp[0];
        $time=$temp[1];
        /**
         * tách ngày tháng năm giờ phút ra
         */
        $temp=  explode("-",$date);
        $day=$temp[2];
        $month=$temp[1];
        $year=$temp[0];

        $temp=  explode(":",$time);            
        $minute=$temp[1];
        $hour=$temp[0];
        ?>
        var current_time = new Date();
        current_time.setDate(<?php echo (int)$day;?>);
        current_time.setMonth(<?php echo (int)$month;?>);
        current_time.setYear(<?php echo (int)$year;?>);
        current_time.setHours(<?php echo (int)$hour;?>);
        current_time.setMinutes(<?php echo (int)$minute;?>);
        current_time.setSeconds(0);
    <?php
    }
    /**     
     * chạy quảng cáo
     * hình thức chạy: 
     *    cứ x giây kiểm tra một lần, kiểm tra thời gian thật đã đúng với thời gian chạy quảng cáo hay chưa.
     *    nếu đúng thi chạy quảng cáo.
     *    nếu sai thi x giây se không được kiểm tra nữa khi quảng cáo đã được chạy
     * @param int $second
     * @return html
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function runSchedule($second){?> 
        /**
         * 
         */
        var run=false;
        <?php
        $this->declareRunTimeAndCurrentTime();
        ?>
        /**
         * 
         */
        function checkRun() {
            var diffMs = (run_time - current_time); 
            var diffMins = Math.round(((diffMs % 86400000) % 3600000) / <?php echo $second;?>000); 


            if(run == false&&diffMins==0){
                runAdvertising();

            } 
            else {
                current_time.setSeconds(current_time.getSeconds() + <?php echo $second;?>);
                window.setTimeout(checkRun, <?php echo $second;?>000); 
            }
        }
        /**
         * 
         */        
        checkRun();
    <?php       
    }
    
    

    

}
