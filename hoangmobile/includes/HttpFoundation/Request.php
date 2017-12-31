<?php

/**
 * @author Ngo Van Thang <ngothangit@gmail.com>
 * @copyright (c) 2013
 */

if(!function_exists('getRequestAll')){
    /**
     * Get all http request
     * 
     * @return array
     */
    function getRequestAll(){
        if(isset($_REQUEST))
            return $_REQUEST;
        else
            return array();
    }
}

if(!function_exists('getRequest')){
    /**
     * Get value from request by index key
     * 
     * @param string $key
     * @return mixed
     */
    function getRequest($key){
        if(is_string($key) and isset($_REQUEST[$key])){
            if(is_array($_REQUEST[$key])){
                return $_REQUEST[$key];
            }
            return trim($_REQUEST[$key]);
        }
        return null;
    }
}

if(!function_exists('getRequestMethod')){
    /**
     * Get http request method <br />
     * Method: POST, GET
     * 
     * @return string
     */
    function getRequestMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }
}