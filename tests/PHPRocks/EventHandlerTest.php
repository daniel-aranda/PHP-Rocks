<?php
namespace PHPRocks\Test;
use PHPRocks\EventHandler;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class EventHandlerTest extends Base
{
    /**
     * @var EventHandler
     */
    private $event_handler;

    private $listener_hook;

    protected function setUp(){
        $this->event_handler = $this->getMockForTrait('PHPRocks\EventHandler');

        $this->listener_hook = $this->getMockBuilder('Random')
            ->setMethods(['listener'])
            ->getMock();
    }

    public function testAddEventHandler()
    {

        $this->listener_hook->expects($this->once())
            ->method('listener');

        $event_name = 'testing_event';

        $this->event_handler->addEventHandler($event_name, function(){
            $this->listener_hook->listener();
        });

        $this->event_handler->trigger($event_name);
    }

    public function testCalledTwice()
    {

        $this->listener_hook->expects($this->exactly(3))
            ->method('listener');

        $event_name = 'testing_event';

        $this->event_handler->addEventHandler($event_name, function(){
            $this->listener_hook->listener();
        });

        $this->event_handler->trigger($event_name);
        $this->event_handler->trigger($event_name);
        $this->event_handler->trigger($event_name);
    }

    public function testRemoveEventHandler()
    {

        $this->listener_hook->expects($this->once())
            ->method('listener');

        $event_name = 'testing_event';

        $closure = function(){
            $this->listener_hook->listener();
        };

        $this->event_handler->addEventHandler($event_name, $closure);

        $this->event_handler->trigger($event_name);

        $this->event_handler->removeEventHandler($event_name, $closure);

        $this->event_handler->trigger($event_name);
        $this->event_handler->trigger($event_name);
    }

    public function testRemoveEventHandlerNotFoundEvent()
    {
        $this->setExpectedException('PHPRocks\Exception\EventHandler\EventNotFound');

        $this->event_handler->removeEventHandler('random', function(){});

    }

    public function testRemoveEventHandlerNotFoundEventDueClosure()
    {
        $this->setExpectedException('PHPRocks\Exception\EventHandler\EventNotFound');

        $this->event_handler->addEventHandler('random', function(){});
        $this->event_handler->removeEventHandler('random', function(){});

    }

}