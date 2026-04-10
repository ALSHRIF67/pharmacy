<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
Illuminate\Support\Facades\DB::setDefaultConnection(config('database.default'));
$cols = Illuminate\Support\Facades\DB::select('SHOW COLUMNS FROM sale_items');
foreach ($cols as $c) {
    echo $c->Field . PHP_EOL;
}
