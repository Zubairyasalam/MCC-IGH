<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $controller = new \App\Http\Controllers\AdminController();
    $controller = new \App\Http\Controllers\AdminController();
    $request = \Illuminate\Http\Request::create('/admin', 'GET');
    $response = $controller->index($request);
    echo "RENDER SUCCESSFUL! Status: " . $response->status() . "\n";
    $viewContent = $response->render();
    echo "VIEW RENDERED LENGTH: " . strlen($viewContent) . " bytes\n";
} catch (\Throwable $e) {
    echo "ERROR OCCURRED: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
