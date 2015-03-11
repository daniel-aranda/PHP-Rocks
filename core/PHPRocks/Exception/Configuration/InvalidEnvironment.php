<?php
namespace PHPRocks\Exception\Configuration;
use PHPRocks\Exception\Configuration;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 * @package: PHPRocks\Exception
 *
 */

class InvalidEnvironment extends Configuration{

    public function __construct($environment){

        $message = 'Invalid environment: ' . $environment;

        parent::__construct($message);
    }

}