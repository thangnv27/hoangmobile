<?php

/**
 * @author Ngo Van Thang <ngothangit@gmail.com>
 * @copyright (c) 2013
 */

if(!function_exists('Response')){
    /**
     * Get response
     * 
     * @param string $key
     * @return mixed
     */
    function Response($body = "", $statusCode = 200, $contentType = "text/html") {
        // create some body messages  
        $message = "";

        // this is purely optional, but makes the pages a little nicer to read  
        // for your users.  Since you won't likely send a lot of different status codes,  
        // this also shouldn't be too ponderous to maintain  
        switch ($statusCode) {
            case 401:
                $message = 'You must be authorized to view this page.';
                break;
            case 404:
                $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                break;
            case 500:
                $message = 'The server encountered an error processing your request.';
                break;
            case 501:
                $message = 'The requested method is not implemented.';
                break;
            default:
                $message = $body;
                break;;
        }

        echo $message;
    }
}