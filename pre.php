<?php
register_shutdown_function(function () {
    $err = error_get_last();
    if ($err) {
        @file_put_contents(__DIR__.'/php_shutdown.txt', print_r($err, true)."\n\n".print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true));
    }
});
set_exception_handler(function ($e) {
    @file_put_contents(__DIR__.'/php_exception.txt', get_class($e).": " . $e->getMessage() . "\n\n" . $e->getTraceAsString());
});
