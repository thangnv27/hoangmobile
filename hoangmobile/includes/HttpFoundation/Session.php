<?php
/**
 * @author Ngo Van Thang <ngothangit@gmail.com>
 * @copyright (c) 2013
 */

/**
 * Session status:<br />
 * 0 = PHP_SESSION_DISABLED<br />
 * 1 = PHP_SESSION_NONE<br />
 * 2 = PHP_SESSION_ACTIVE
 */
if(phpversion() >= 5.4){
    if(session_status() != 2) session_start();
}else{
    if (!isset($_SESSION)) session_start();
}
if(!isset($_SESSION['custom_ss']) or !is_array($_SESSION['custom_ss'])) $_SESSION['custom_ss'] = array();

###################### FLASH MESSAGE ###########################################
/**
 * Flash-Messages provide a way to preserve messages across different  
 * HTTP-Requests. This object manages those messages. 
 * 
 * Note: make sure you call session_start() in order to make this code work
 */

if(!function_exists('setFlash')){
    /**
     * Set value for flash message
     * 
     * @param string $name
     * @param string $value
     */
    function setFlash($name, $value) {
        $msg = serialize($value);
        unset($_SESSION['flash_message']); // unset all old flash session
        $_SESSION['flash_message'][$name] = $msg;
    }
}

if(!function_exists('getFlash')){
    /**
     * Get flash message by name
     * 
     * @param string $name Session name
     * @param type $default
     * @return string
     */
    function getFlash($name, $default = null) {
        $msg = unserialize($_SESSION['flash_message'][$name]);
        if ($msg == "")
            return null;
        unset($_SESSION['flash_message'][$name]); // remove the session after being retrieve  
        return $msg;
    }
}

if(!function_exists('hasFlash')){
    /**
     * Check exists session name
     * 
     * @param string $name Session name
     * @return boolean
     */
    function hasFlash($name) {
        if (isset($_SESSION['flash_message'])) {
            if(array_key_exists($name, $_SESSION['flash_message']))
                return true;
        }
        return false;
    }
}

################################################################################
if(!function_exists('setSession')){
    /**
     * Set session value
     * 
     * @param string $name Session key
     * @param mixed $value
     */
    function setSession($name, $value){
        $_SESSION['custom_ss'][$name] = $value;
    }
}

if(!function_exists('getSession')){
    /**
     * Get session value
     * 
     * @param string $name Session key
     * @return array
     */
    function getSession($name){
        if(isset($_SESSION['custom_ss'][$name]))
            return $_SESSION['custom_ss'][$name];
        else
            return null;
    }
}

if(!function_exists('hasSession')){
    /**
     * Check exists session name
     * 
     * @param string $name Session name
     * @return boolean
     */
    function hasSession($name) {
        if (isset($_SESSION['custom_ss'])) {
            if(array_key_exists($name, $_SESSION['custom_ss']))
                return true;
        }
        return false;
    }
}

if(!function_exists('getSessionAll')){
    /**
     * Get all session
     * 
     * @return array
     */
    function getSessionAll(){
        if(isset($_SESSION))
            return $_SESSION;
        else
            return array();
    }
}

if(!function_exists('destroySession')){
    /**
     * Destroy the session
     * 
     * @param string|array $name
     */
    function destroySession($name){
        if(is_string($name) and isset($_SESSION['custom_ss'][$name])){
            unset($_SESSION['custom_ss'][$name]);
        }elseif(is_array($name)){
            foreach ($name as $value) {
                if(isset($_SESSION['custom_ss'][$value]))
                    unset($_SESSION['custom_ss'][$value]);
            }
        }
    }
}