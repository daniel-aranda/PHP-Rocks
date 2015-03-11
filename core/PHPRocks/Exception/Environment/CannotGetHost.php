<?php
namespace PHPRocks\Exception\Environment;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 * @package: PHPRocks\Exception\Environment
 *
 */

class CannotGetHost extends \PHPRocks\Exception\Environment
{

    public function __construct(array $server)
    {
        $message = 'Can\'t get the host. ' . var_export($server, true);
        parent::__construct($message);
    }

}