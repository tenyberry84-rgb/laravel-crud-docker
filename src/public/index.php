<?php

require __DIR__.'/../bootstrap.php';

use App\Http\Controllers\TaskController;

// Simple router
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

header('Content-Type: application/json');

$controller = new TaskController();

// Routes
if ($path === '/api/tasks' && $method === 'GET') {
    echo $controller->index();
} elseif ($path === '/api/tasks' && $method === 'POST') {
    echo $controller->store();
} elseif (preg_match('/^\/api\/tasks\/(\d+)$/', $path, $matches) && $method === 'GET') {
    echo $controller->show($matches[1]);
} elseif (preg_match('/^\/api\/tasks\/(\d+)$/', $path, $matches) && $method === 'PUT') {
    echo $controller->update($matches[1]);
} elseif (preg_match('/^\/api\/tasks\/(\d+)$/', $path, $matches) && $method === 'DELETE') {
    echo $controller->destroy($matches[1]);
} elseif ($path === '/' || $path === '') {
    echo json_encode(['message' => 'Laravel CRUD API', 'endpoints' => [
        'GET /api/tasks' => 'List all tasks',
        'POST /api/tasks' => 'Create task',
        'GET /api/tasks/{id}' => 'Show task',
        'PUT /api/tasks/{id}' => 'Update task',
        'DELETE /api/tasks/{id}' => 'Delete task',
    ]]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}
