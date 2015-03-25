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

    public static function phpVersionEqualsTo($version, $current_version) {
        $version = str_replace('.', '\.', $version);
        return !!preg_match('/^'. $version .'/', $current_version);
    }

}