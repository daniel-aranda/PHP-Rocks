<?php
namespace PHPRocks\Exception\Util;
use PHPRocks\Exception\Base;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 * @package: PHPRocks\Exception
 *
 */

class JSON extends Base
{

    public function __construct($json, $type, $error){

        $message = 'Error '. $type .' ' . $json . PHP_EOL . $error;

        parent::__construct($message);
    }

}