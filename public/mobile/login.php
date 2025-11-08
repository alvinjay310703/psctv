<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$_POST = json_decode(file_get_contents('php://input'), true) ?? [];

if (empty($_POST['email']) || empty($_POST['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing email or password']);
    exit;
}

header('User-Agent: Mozilla/5.0');

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$request->server->set('REQUEST_URI', '/api/technician/login');

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
