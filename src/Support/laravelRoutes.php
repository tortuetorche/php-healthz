<?php

Route::get('/healthz', function() {
    $healthz = app(\Gentux\Healthz\Healthz::class);
    $results = $healthz->run();
    if ($results->hasFailures()) {
        return 'fail';
    }

    return 'ok';
});

Route::get('/healthz/ui', function() {
    $healthz = app(\Gentux\Healthz\Healthz::class);
    $html = $healthz->html();

    return response($html)->header('Content-Type', 'text/html');
});
