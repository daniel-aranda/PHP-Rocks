<?php
namespace PHPRocks;
use PHPRocks\ErrorHandler\ErrorEntity;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */
class ErrorHandler{

    use EventHandler;

    const SOFT_ERROR_EVENT = 'soft_error_event';
    const EXCEPTION_EVENT = 'exception_event';
    const FATAL_ERROR_EVENT = 'fatal_error_event';

    private $active = true;

    public function __construct()
    {
        $this->setHandlers();
    }

    private function setHandlers()
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);

        register_shutdown_function(function(){
            // @codeCoverageIgnoreStart
            $this->handleFatalError();
            // @codeCoverageIgnoreEnd
        });

    }

    public function undoHandlers(){
        restore_error_handler();
        restore_exception_handler();
        $this->active = false;
    }

    public function handleError($number, $message, $file, $line)
    {

        $error = new ErrorEntity();
        $error->number = $number;
        $error->message = $message;
        $error->file = $file;
        $error->line = $line;

        $this->trigger(self::SOFT_ERROR_EVENT, [$error]);

    }

    /**
     * @param \Exception $exception
     * @codeCoverageIgnore
     */
    public function handleException(\Exception $exception)
    {
        $this->trigger(self::EXCEPTION_EVENT, [$exception]);
    }

    /**
     * @param $error
     * @codeCoverageIgnore
     */
    public function throwFatalError($fatal_error){

        $error = new ErrorEntity();

        $error->file = $fatal_error['file'];
        $error->line = $fatal_error['line'];
        $error->message = $fatal_error['message'];
        $error->number = $fatal_error['type'];

        $this->trigger(self::FATAL_ERROR_EVENT, [$error]);

    }

    /**
     * @codeCoverageIgnore
     */
    private function handleFatalError()
    {

        if( !$this->active ){
            return null;
        }

        $error = error_get_last();

        if( $error !== NULL ){

            if($error['type'] === E_ERROR || $error['type'] === E_PARSE){
                $this->throwFatalError($error);
            }

        }

    }

}