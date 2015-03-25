<?php
namespace PHPRocks\Test;
use PHPRocks\Util\System;
use PHPRocks\Util\SystemMockableFunction;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class SystemTest extends Base
{

    /**
     * @var SystemMockableFunction
     */
    private $mockable;

    protected function setUp() {

        $mock = $this->getMockBuilder('Random')
            ->setMethods([
                'phpversion'
            ])
            ->getMock();

        $this->mockable = new SystemMockableFunction($mock);
    }

    public function testPhpVersionEqualsTo() {

        $nativeFunction = new SystemMockableFunction();
        $this->assertTrue(System::phpVersionEqualsTo(phpversion(), $nativeFunction->phpversion()));

    }

    public function testPhpVersionNotEqualsTo() {

        $nativeFunction = new SystemMockableFunction();
        $this->assertFalse(System::phpVersionEqualsTo('5.3', $nativeFunction->phpversion()));

    }

    public function testPhpVersionOverwrite() {

        $mock = $this->mockable->getMock();
        $mock->expects($this->exactly(1))
            ->method('phpversion')
            ->willReturn('2054.98');
        $this->assertTrue(System::phpVersionEqualsTo('2054.98', $mock->phpversion()));
        $this->assertFalse(System::phpVersionEqualsTo('2054.98', phpversion()));

    }

}