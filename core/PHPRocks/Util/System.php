<?php
namespace PHPRocks\Util;
use PHPRocks\Environment;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class System
{

    public static $phpVersion = null;

    public static function phpVersion(){
        if( !is_callable(static::$phpVersion) ){
            static::$phpVersion = function(){
                return phpversion();
            };
        }
        return static::$phpVersion->__invoke();
    }

    public static function phpVersionEqualsTo($version) {
        $version = str_replace('.', '\.', $version);
        return !!preg_match('/^'. $version .'/', static::phpVersion());
    }

}