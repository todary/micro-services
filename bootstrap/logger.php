<?php
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

if (env('APP_ENV') != "testing" && !is_writable(storage_path('logs'))) {
    $app->configureMonologUsing(function ($monolog) {

        $formatter = new LineFormatter(null, null, true, true);
        $formatter->includeStacktraces(true);

        $handler = (new StreamHandler('php://stdout'))
            ->setFormatter($formatter);

        $monolog->setHandlers([$handler]);

        return $monolog;
    });
}
