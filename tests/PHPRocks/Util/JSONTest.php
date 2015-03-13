<?php
namespace PHPRocks\Test;
use PHPRocks\Util\JSON;
use PHPRocks\Util\System;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class JSONTest extends Base
{

    protected function setUp() {

    }

    public function testDecode() {
        $this->assertSame(["daniel"], JSON::decode('["daniel"]'));
    }

    public function testEncode() {
        $this->assertSame('["daniel"]', JSON::encode(["daniel"]));
    }

    public function testEncodePHP5_4() {
        System::$phpVersion = function(){
            return '5.4.37';
        };
        $this->assertSame('["daniel"]', JSON::encode(["daniel"]));
        System::$phpVersion = null;
    }

    public function testDecodeException() {
        $this->setExpectedException('PHPRocks\Exception\Util\JSON');
        JSON::decode('["daniel"');
    }

    public function testEncodeException() {
        $this->setExpectedException('PHPRocks\Exception\Util\JSON');
        JSON::encode(utf8_decode('¢'));
    }

}