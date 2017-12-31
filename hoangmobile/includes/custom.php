<?php
/**
 * Get current request url
 * 
 * @return tring
 */
function getCurrentRquestUrl(){
    $prefix = "http://";
    if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]=="on"){
        $prefix = "https://";
    }
    return $prefix . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
}

/*----------------------------------------------------------------------------*/
# City list of Vietnamese
/*----------------------------------------------------------------------------*/
if(!function_exists('get_city_list')){
    function get_city_list(){
        return array(
            "An Giang", "Bà Rịa - Vũng Tàu", "Bạc Liêu", "Bắc Kạn", "Bắc Giang", "Bắc Ninh", "Bến Tre", "Bình Dương",
            "Bình Định", "Bình Phước", "Bình Thuận", "Cà Mau", "Cao Bằng", "Cần Thơ", "Đà Nẵng", "Đắk Lắk", "Đắk Nông",
            "Đồng Nai", "Đồng Tháp", "Điện Biên", "﻿Gia Lai", "Hà Giang", "Hà Nam", "Hà Nội", "Hà Tĩnh", "Hải Dương", 
            "Hải Phòng", "Hòa Bình", "Hậu Giang", "Hưng Yên", "TP. Hồ Chí Minh", "Khánh Hòa", "Kiên Giang", "Kon Tum", 
            "Lai Châu", "Lào Cai", "Lạng Sơn", "Lâm Đồng", "Long An", "Nam Định", "Nghệ An", "Ninh Bình", "Ninh Thuận", 
            "Phú Thọ", "Phú Yên", "Quảng Bình", "Quảng Nam", "Quảng Ngãi", "Quảng Ninh", "Quảng Trị", "Sóc Trăng", 
            "Sơn La", "Tây Ninh", "Thái Bình", "Thái Nguyên", "Thanh Hóa", "Thừa Thiên - Huế", "Tiền Giang", 
            "Trà Vinh", "Tuyên Quang", "Vĩnh Long", "Vĩnh Phúc", "Yên Bái", "Nơi khác", 
        );
    }
}

if(!function_exists('TConvertObjectToArray')){
    /**
     * Convert Object to Array
     * 
     * @param Object $object
     * @return array
     */
    function TConvertObjectToArray($object) {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }
}

/**
 * Remove special char
 * 
 * @param string $string
 * @return string
 */
function removeSpecialChar($string){
    $specialChar = array("!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=", ";", ":", "'", "\"", ",", ".", "/", "<", ">", "?", );
    foreach ($specialChar as $key => $value) {
        $pos = strpos($string, $value);
        if($pos){
            $string = str_replace(substr($string, $pos, 2), ucwords(substr($string, $pos+1, 1)), $string);
        }
    }
    return $string;
}

/**
 * Generate random string 
 * 
 * @param integer $length default length = 32
 * @return string
 */
function random_string($length = 32) {
    $key = '';
    $rand = str_split(strtolower(md5(time() * microtime())));
    $keys = array_merge(range(0, 9), range('a', 'z'));
    $keys = array_merge($keys, $rand);

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}

/**
 * Replaces url entities with -
 *
 * @param string $fragment
 * @return string
 */
function clean_entities($fragment) {
    $translite_simbols = array(
        '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
        '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
        '#(ì|í|ị|ỉ|ĩ)#',
        '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
        '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
        '#(ỳ|ý|ỵ|ỷ|ỹ)#',
        '#(đ)#',
        '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
        '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
        '#(Ì|Í|Ị|Ỉ|Ĩ)#',
        '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
        '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
        '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
        '#(Đ)#',
        "/[^a-zA-Z0-9\-\_]/",
    );
    $replace = array(
        'a',
        'e',
        'i',
        'o',
        'u',
        'y',
        'd',
        'A',
        'E',
        'I',
        'O',
        'U',
        'Y',
        'D',
        '-',
    );
    $fragment = preg_replace($translite_simbols, $replace, $fragment);
    $fragment = preg_replace('/(-)+/', '-', $fragment);

    return $fragment;
}

/**
 * Read properties file
 * 
 * @param type $filename path to properties file
 * @return array key=>value
 */
function readProperties($filename) {
    $list = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $language = array();
    foreach ($list as $lang) {
        $arr = explode('=', $lang);
        if (count($arr) == 2) {
            $language[trim($arr[0])] = trim($arr[1]);
        }
    }
    return $language;
}

/* GET THUMBNAIL URL */
function get_image_url() {
    $image_id = get_post_thumbnail_id();
    $image_url = wp_get_attachment_image_src($image_id, 'full');
    $image_url = $image_url[0];
    if($image_url != ""){
        echo $image_url;
    }else{
        bloginfo( 'stylesheet_directory' );
        echo "/images/no_image_available.jpg";
    }
}
/**
 * Get post thumbnail url
 * 
 * @param integer $post_id
 * @param type $size
 * @return string
 */
function get_post_thumbnail_url($post_id, $size){
    return wp_get_attachment_url( get_post_thumbnail_id($post_id, $size) );
}

/**
 * Remove BBCODE from text document
 * @param string $code text document
 * @return string text document
 */
function removeBBCode($code) {
    $code = preg_replace("/(\[)(.*?)(\])/i", '', $code);
    $code = preg_replace("/(\[\/)(.*?)(\])/i", '', $code);
//    $code = preg_replace("/http(.*?).(.*)/i", '', $code);
//    $code = preg_replace("/\<a href(.*?)\>/", '', $code);
//    $code = preg_replace("/:(.*?):/", '', $code);
    $code = str_replace("\n", '', $code);
    return $code;
}
/**
 * Get short content from full contents
 * 
 * @param integer $length 
 * @return string
 */
function get_short_content($contents, $length){
    $short = "";
    $contents = strip_tags($contents);
    if (strlen(removeBBCode($contents)) >= $length) {
        $text = explode(" ", substr(removeBBCode($contents), 0, $length));
        for ($i = 0; $i < count($text) - 1; $i++) {
            if($i == count($text) - 2){
                $short .= $text[$i];
            }else{
                $short .= $text[$i] . " ";
            }
        }
        $short .= "...";
    } else {
        $short = removeBBCode($contents) . "...";
    }
    return $short;
}

/**
 * Video Youtube
 */
function shortcode_youtube($content = NULL, $width = 296, $height = 254) {
    if ("" === $content)
        return 'No YouTube Video ID Set';
    $id = $text = $content;
    return '<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/v/' . $id . '"></param><embed src="http://www.youtube.com/v/' . $id . '" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'"></embed></object>';
}

/**
 * Tests a string to see if it's a valid email address
 *
 * @param	string	Email address
 *
 * @return	boolean
 */
function is_valid_email($email) {
//    return filter_var($email, FILTER_VALIDATE_EMAIL);
//    return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$^", $email);
    return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s\'"<>@,;]+\.+[a-z]{2,6}))$#si', $email);
}

/**
 * Display with <pre> tag on browser
 * @param All format $value
 */
function preTag($value) {
    if (is_string($value)) {
        echo "<pre>";
        echo($value);
        echo "</pre>";
    } else {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }
}

/**
 * Init display error messages
 */
function myDebug(){
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
}