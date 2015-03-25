<?php
namespace PHPRocks\Util;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class JSON
{

    /**
     * @param $value
     * @param int $options
     * @param int $depth
     */
    public static function encode($value, $options = 0, $depth = 512){
        $json = json_encode($value, $options, $depth);

        if( json_last_error() !== JSON_ERROR_NONE ){
            throw new \PHPRocks\Exception\Util\JSON(var_export($value, true), 'encode', static::lastError());
        }

        return $json;
    }

    /**
     * @param $json
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     */
    public static function decode($json, $assoc = false, $depth = 512, $options = 0){
        $data = json_decode($json, $assoc, $depth, $options);

        if( json_last_error() !== JSON_ERROR_NONE ){
            throw new \PHPRocks\Exception\Util\JSON($json, 'decode', static::lastError());
        }

        return $data;
    }

    private static function lastError(){
        return function_exists('json_last_error_msg') ? json_last_error_msg() : '';
    }

}