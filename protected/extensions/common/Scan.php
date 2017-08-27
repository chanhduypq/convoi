<?php

class Scan extends CApplicationComponent {

    private $video_extension_array = array('mp4');
    private $audio_extension_array = array('mp3');
    private $image_array = array(
        'jpg',
        'jpeg',
        'png',
        'gif',
    );
    private $special_char = array("-", "_", ".", "+", "&");
    private $video_no_img = '/themes/images/no_image_for_film_music/mnsbus-cover-film.png';
    private $music_no_img = '/themes/images/no_image_for_film_music/mnsbus-cover-music.png';

    const MUSIC = 'music';
    const VIDEO = 'video';

    public function __construct() {
        $this->video_no_img = Yii::app()->baseUrl . $this->video_no_img;
        $this->music_no_img = Yii::app()->baseUrl . $this->music_no_img;
    }

//    public function listOutForFilm($dir) {
//
//        $list = array();
//        if (file_exists($dir)) {
//            foreach (scandir($dir) as $f) {
//                if (!$f || $f[0] == '.' || $f == '#recycle' || $f == '@eaDir') {
//                    continue;
//                }
//                if (is_dir($dir . '/' . $f)) {
//
//                    $temp = scandir($dir . '/' . $f);
//                    $img_tuetc = '';
//                    foreach ($temp as $t) {
//                        if (is_file($dir . '/' . $f . "/" . $t) && (in_array(substr($t, -3), $this->image_array) || in_array(substr($t, -4), $this->image_array))) {
////                            $img_tuetc="http://" . $_SERVER['HTTP_HOST'] . "/../".$dir . '/' . $f."/".$t;
//                            $img_tuetc = '/' . $dir . '/' . $f . "/" . $t;
//                        }
//                    }
//                    if ($img_tuetc != "") {
//
//                        $list[] = array(
//                            'href' => "?url=$dir/$f",
//                            'img' => array('src' => $img_tuetc, 'title' => $f, 'alt' => $f),
//                            'text' => (strlen($f) > 20) ? substr($f, 0, 20) . '...' : substr($f, 0, 20),
//                        );
//                    } else {
//                        
//
//
//                        $list[] = array(
//                            'href' => "?url=$dir/$f",
//                            'img' => array('src' => $this->film_no_img, 'title' => $f, 'alt' => $f),
//                            'text' => (strlen($f) > 20) ? substr($f, 0, 20) . '...' : substr($f, 0, 20),
//                        );
//                    }
//                } else {
//                    if (in_array(substr($f, -3), $this->video)) {
//                        $title_no_ext = str_replace($this->special_char, ' ', ucwords(strtolower(substr($f, 0, -4))));
//                        
//
//
//
//
//
//
//                        $list[] = array(
//                            'href' => "?file_name=$dir/$f",
//                            'img' => array('src' => $this->film_no_img, 'title' => $title_no_ext, 'alt' => $title_no_ext),
//                            'text' => (strlen($title_no_ext) > 20) ? substr($title_no_ext, 0, 20) . '...' : substr($title_no_ext, 0, 20),
//                        );
//                    }
//                }
//            }
//        }
//        return $list;
//    }
    /**
     * lấy thông tin folder và file trong một folder
     * @param string $dir
     * @param string $type
     * @return array
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function listOutDirOrFile($dir, $type) {
        $has_file = FALSE;
        $list_of_dir = array();
        $list_of_file = array();

        if ($type == self::MUSIC) {
            $no_img = $this->music_no_img;
            $extension_array = $this->audio_extension_array;
        } else if ($type == self::VIDEO) {
            $no_img = $this->video_no_img;
            $extension_array = $this->video_extension_array;
        } else {
            $no_img = '';
        }

        
        if (file_exists($dir)) {
            foreach (scandir($dir) as $f) {
                if (!$f || $f[0] == '.' || $f == '#recycle' || $f == '@eaDir') {
                    continue;
                }
                if (is_dir($dir . '/' . $f)) {

                    $has_audio=FALSE;
                    $temp = scandir($dir . '/' . $f);
                    $img_src = $no_img;
                    foreach ($temp as $t) {
                        if (is_file($dir . '/' . $f . "/" . $t) && (in_array(strtolower(substr($t, -3)), $this->image_array) || in_array(strtolower(substr($t, -4)), $this->image_array))) {
//                            $img_src="http://" . $_SERVER['HTTP_HOST'] . "/".$dir . '/' . $f."/".$t;
                            $img_src = '/' . $dir . '/' . $f . "/" . $t;
                        }
                        /**
                         * kiểm tra xem folder này có chứa file audio k
                         */
                        if (is_file($dir . '/' . $f . "/" . $t) && (in_array(strtolower(substr($t, -3)), $extension_array))) {
                            $has_audio=TRUE;
                        }
                    }
                    $list_of_dir[] = array(
                                        'href' => "?url=$dir/$f",
                                        'img' => array('src' => $img_src, 'title' => $f, 'alt' => $f),
                                        'text' => (strlen($f) > 20) ? substr($f, 0, 20) . '...' : substr($f, 0, 20),
                                        /**
                                         * mục đích sử dụng cho music
                                         * xem film vẫn có key=>value này nhưng k dc sử dụng trong film/index/index, còn music thi dc sử dụng trong music/index/index
                                         * nếu $value = true thi url sẽ là music/index/play?url=xxx thay vì music/index/index?url=xxx
                                         */ 
                                        'has_audio'=>$has_audio,
                                    );
                } else {
                    if (in_array(strtolower(substr($f, -3)), $extension_array)) {
                        $has_file = true;
                        $title_no_ext = str_replace($this->special_char, ' ', ucwords(strtolower(substr($f, 0, -4))));
//                        $src = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $dir . '/' . $f;
                        $src =  '/' . $dir . '/' . $f;
                        $list_of_file[] = array(
                            'src' => $src,
                            'title' => $title_no_ext,
                        );
                    }
                }
            }
        }
        if ($has_file) {
            return array('has_file' => true, 'list' => $list_of_file);
        }
        return array('has_file' => FALSE, 'list' => $list_of_dir);
    }

    /**
     * lấy thông tin folder và file trong một folder từ folder gốc là video
     * @param string $dir     
     * @return array
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function listOutForFilm($dir) {        
        return $this->listOutDirOrFile($dir, self::VIDEO);
    }
    /**
     * lấy thông tin folder và file trong một folder từ folder gốc là music
     * @param string $dir     
     * @return array
     * @author Trần Công Tuệ <congtue@mns.com> 
     */
    public function listOutForMusic($dir) {
        return $this->listOutDirOrFile($dir, self::MUSIC);
    }  


}
