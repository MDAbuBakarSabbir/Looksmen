<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $p = new App\Models\Product();
    $p->title = 'Test';
    $p->slug = 'test';
    $p->category_id = 1;
    $p->description = 'Test';
    $p->old_price = 10;
    $p->new_price = 5;
    $p->stock = 10;
    $p->code = '123';
    $p->cod = 1;
    $p->todays_deal = 0;
    $p->status = 1;
    $p->points = 0;
    $p->save();
    echo 'Success';
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
