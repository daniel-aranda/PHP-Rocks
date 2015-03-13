<?php
namespace PHPRocks\Test;
use PHPRocks\Util\System;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class SystemTest extends Base
{

    protected function setUp() {

    }

    public function testPhpVersionEqualsTo() {

        $this->assertTrue(System::phpVersionEqualsTo(phpversion()));

    }

    public function testPhpVersionNotEqualsTo() {

        $this->assertFalse(System::phpVersionEqualsTo('5.3'));

    }

    public function testPhpVersionOverwrite() {

        System::$phpVersion = function(){
            return '2054.98';
        };
        $this->assertSame('2054.98', System::phpVersion());
        $this->assertNotSame(phpversion(), System::phpVersion());
        System::$phpVersion = null;

        $this->assertNotSame('2054.98', System::phpVersion());
        $this->assertSame(phpversion(), System::phpVersion());
    }

}