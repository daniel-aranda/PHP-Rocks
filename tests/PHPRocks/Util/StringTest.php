<?php
namespace PHPRocks\Test;
use PHPRocks\Util\String;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class StringTest extends Base
{

    protected function setUp() {
        
    }

    public function testUnderscoreToCamelCase() {
        $this->assertSame('DanielTest', String::underscoreToCamelCase('daniel_test'));
        $this->assertSame('Daniel', String::underscoreToCamelCase('daniel'));
        $this->assertSame('DanielTest2', String::underscoreToCamelCase('daniel_test_2'));
    }

}