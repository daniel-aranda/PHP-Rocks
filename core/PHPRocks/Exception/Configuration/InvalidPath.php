<?php
namespace PHPRocks\Exception\Configuration;
use PHPRocks\Exception\Configuration;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 * @package: PHPRocks\Exception
 *
 */

class InvalidPath extends Configuration{

    public function __construct($path){

        $message = 'Invalid path: ' . $path;

        parent::__construct($message);
    }

}