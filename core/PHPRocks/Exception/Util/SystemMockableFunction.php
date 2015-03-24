<?php
namespace PHPRocks\Exception\Util;
use PHPRocks\Exception\Base;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 * @package: PHPRocks\Exception
 *
 */

class SystemMockableFunction extends Base
{

    public function __construct($name){

        $message = 'This is not callable: ' . $name;

        parent::__construct($message);
    }

}