<?php

namespace core\router\error;

class ErrorHandler {
    public static function handleError($errno, $errstr, $errfile, $errline) {
        $error_type = '';

        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                $error_type = 'Fatal Error';
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $error_type = 'Warning';
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $error_type = 'Notice';
                break;
            default:
                $error_type = 'Unknown Error';
                break;
        }

        $error_message = "[" . $error_type . "] " . $errstr . " in " . $errfile . " on line " . $errline;

        echo $error_message;
    }
}

set_error_handler(array('MyErrorHandler', 'handleError'));
