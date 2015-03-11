<?php
namespace PHPRocks\Exception\Configuration;
use PHPRocks\Exception\Configuration;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 * @package: PHPRocks\Exception
 *
 */

class ValueNotSet extends Configuration{

    public function __construct($value){

        $message = 'Value not set: ' . $value;

        parent::__construct($message);
    }

}