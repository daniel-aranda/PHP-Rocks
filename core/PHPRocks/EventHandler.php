<?php
namespace PHPRocks;
use PHPRocks\Exception\EventHandler\EventNotFound;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */


trait EventHandler
{

    /**
     * @var array
     */
    private $events = [];

    /**
     * @param $event_name
     * @param array $arguments
     * @return null
     */
    public function trigger($event_name, $arguments = [])
    {
        if( !array_key_exists($event_name, $this->events) )
        {
            return null;
        }

        foreach($this->events[$event_name] as $event){
            call_user_func_array($event, $arguments);
        }

        return true;
    }

    /**
     * @param $event_name
     * @param callable $listener
     */
    public function addEventHandler($event_name, \Closure $listener)
    {
        $this->validateEventName($event_name);
        $this->events[$event_name][] = $listener;
    }

    /**
     * @param $event_name
     * @param callable $listener
     * @throws EventNotFound
     */
    public function removeEventHandler($event_name, \Closure $listener)
    {
        $this->validateEventName($event_name);
        $index = array_search($listener, $this->events[$event_name]);

        if( $index === false ){
            throw new EventNotFound($event_name);
        }

        array_splice($this->events[$event_name], $index, 1);

    }

    /**
     * @param $event_name
     */
    private function validateEventName($event_name)
    {
        if( !array_key_exists($event_name, $this->events) ){
            $this->events[$event_name] = [];
        }
    }

}