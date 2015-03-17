<?php

$previous_error_reporting = error_reporting(0);

include 'vendor/autoload.php';

$error_handler = new \PHPRocks\ErrorHandler();

$error_handler->addEventHandler(\PHPRocks\ErrorHandler::FATAL_ERROR_EVENT, function(\PHPRocks\ErrorHandler\ErrorEntity $error) use($previous_error_reporting){
    error_reporting($previous_error_reporting);
    echo 'PHPRocks trapped: ' . $error->message;
});

include 'fatal-error-generator.php';
