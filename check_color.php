<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$val = \App\Models\Setting::where('key', 'primary_color')->value('value');
echo "COLOR IS: " . ($val ?? 'null') . "\n";
