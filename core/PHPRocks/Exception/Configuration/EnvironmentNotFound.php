<?php
namespace PHPRocks\Exception\Configuration;
use PHPRocks\Exception\Configuration;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 * @package: PHPRocks\Exception
 *
 */

class EnvironmentNotFound extends Configuration{

    public function __construct($domain){

        $message = 'Environment not found for this domain: ' . $domain;

        parent::__construct($message);
    }

}