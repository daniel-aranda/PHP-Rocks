<?php
namespace PHPRocks\Test;
use PHPRocks\Util\SystemMockableFunction;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class SystemMockableFunctionTest extends Base
{

    protected function setUp() {
    }

    public function testCallingMockOfNativeFunction() {

        $mock = $this->getMockBuilder('Random')
            ->setMethods([
                'phpversion'
            ])
            ->getMock();

        $mockable = new SystemMockableFunction($mock);

        $mock->expects($this->exactly(2))
            ->method('phpversion')
            ->willReturn('95.9');

        $mockable->phpversion();
        $this->assertSame('95.9', $mockable->phpversion());

    }

    public function testCallingUseNativeFunction() {

        $mockable = new SystemMockableFunction();

        $this->assertSame(phpversion(), $mockable->phpversion());

    }

    public function testInvalidMockMethod(){

        $this->setExpectedException('PHPRocks\Exception\Util\SystemMockableFunction');

        $mock = $this->getMockBuilder('Random')
            ->getMock();

        $mockable = new SystemMockableFunction($mock);

        $mockable->phpversion();

    }

    public function testInvalidNativeFunction(){

        $this->setExpectedException('PHPRocks\Exception\Util\SystemMockableFunction');

        $mockable = new SystemMockableFunction();

        $mockable->random_function();

    }

}