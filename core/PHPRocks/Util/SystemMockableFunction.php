<?php
namespace PHPRocks\Util;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class SystemMockableFunction
{

    /**
     * @var PHPUnit_Framework_MockObject_MockObject | null
     */
    private $mock = null;

    public function __construct(\PHPUnit_Framework_MockObject_MockObject $mock = null){
        $this->mock = $mock;
    }

    public function __call($name, $arguments){
        if( !is_null($this->mock) ){
            if( !is_callable([$this->mock, $name]) ){
                throw new \PHPRocks\Exception\Util\SystemMockableFunction($name);
            }
            $callable = [$this->mock, $name];
        }else{
            if( !function_exists($name) ){
                throw new \PHPRocks\Exception\Util\SystemMockableFunction($name);
            }
            $callable = $name;
        }
        return call_user_func_array($callable, $arguments);
    }

}