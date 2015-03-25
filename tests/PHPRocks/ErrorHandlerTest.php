<?php
namespace PHPRocks\Test;
use PHPRocks\ErrorHandler;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class ErrorHandlerTest extends Base
{

    /**
     * @var ErrorHandler
     */
    private $error_handler;

    private $listener_hook;

    protected function setUp()
    {
        $this->error_handler = new ErrorHandler();

        $this->listener_hook = $this->getMockBuilder('Random')
            ->setMethods(['listener'])
            ->getMock();

    }

    public function tearDown()
    {
        $this->error_handler->undoHandlers();
    }

    public function testErrorHandler(){
        $this->listener_hook->expects($this->once())
            ->method('listener');

        $this->error_handler->addEventHandler(ErrorHandler::SOFT_ERROR_EVENT, function(ErrorHandler\ErrorEntity $error){
            $this->listener_hook->listener();

            $this->assertSame(2, $error->number);
            $this->assertSame('Invalid argument supplied for foreach()', $error->message);
            $this->assertSame(__FILE__, $error->file);
            $this->assertSame(__LINE__ + 3, $error->line);

        });
        foreach(99 as $test){};
    }

    public function testUndoErrorHandler(){
        $this->listener_hook->expects($this->exactly(0))
            ->method('listener');

        $this->error_handler->addEventHandler(ErrorHandler::SOFT_ERROR_EVENT, function(ErrorHandler\ErrorEntity $error){
            $this->listener_hook->listener();
        });
        $this->error_handler->undoHandlers();

        try{
            foreach(99 as $test){};
        }catch(\Exception $e){
        }
    }

}