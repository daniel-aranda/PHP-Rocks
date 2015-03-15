<?php
namespace PHPRocks\Exception\EventHandler;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 * @package: PHPRocks\Exception\EventHandler
 *
 */

class EventNotFound extends \PHPRocks\Exception\EventHandler
{

    public function __construct($event_name)
    {
        $message = 'Event not found: ' . $event_name;
        parent::__construct($message);
    }

}