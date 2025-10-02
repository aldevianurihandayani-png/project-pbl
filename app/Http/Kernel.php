<?php

// app/Http/Kernel.php
protected $routeMiddleware = [
    // ...
    'is_admin' => \App\Http\Middleware\IsAdmin::class,
];
