<?php
namespace PHPRocks\Test;
use PHPRocks\ErrorHandler;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class ErrorHandlerExternalTest extends Base
{

    public function testFatalError()
    {
        $error = 'PHPRocks trapped: Allowed memory size of 262144 bytes exhausted (tried to allocate ';
        $this->assertContains($error, shell_exec('php tests/fixtures/fatal-error.php'));
    }

    public function testUndoFatalError()
    {
        $this->assertSame(null, shell_exec('php tests/fixtures/undo-fatal-error.php'));
    }

    public function testException()
    {
        $this->assertSame('random exception', shell_exec('php tests/fixtures/exception-error.php'));
    }

}