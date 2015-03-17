<?php

$previous_error_reporting = error_reporting(0);

include 'vendor/autoload.php';

$error_handler = new \PHPRocks\ErrorHandler();

$error_handler->addEventHandler(\PHPRocks\ErrorHandler::EXCEPTION_EVENT, function(\Exception $exception) use($previous_error_reporting){
    error_reporting($previous_error_reporting);
    echo $exception->getMessage();
});

throw new \Exception('random exception');
