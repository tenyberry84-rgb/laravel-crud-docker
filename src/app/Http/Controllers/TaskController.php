<?php

namespace App\Http\Controllers;

use PDO;
use PDOException;

class TaskController
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->createTable();
        } catch (PDOException $e) {
            // Return empty PDO for now if DB not ready
            $this->pdo = null;
        }
    }

    private function createTable()
    {
        if (!$this->pdo) return;
        
        $sql = "CREATE TABLE IF NOT EXISTS tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            completed BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    public function index()
    {
        if (!$this->pdo) {
            return json_encode(['error' => 'Database not connected']);
        }

        $stmt = $this->pdo->query("SELECT * FROM tasks ORDER BY id DESC");
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode(['tasks' => $tasks]);
    }

    public function store()
    {
        if (!$this->pdo) {
            return json_encode(['error' => 'Database not connected']);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, completed) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['completed'] ?? 0
        ]);
        
        $id = $this->pdo->lastInsertId();
        return json_encode(['message' => 'Task created', 'id' => $id]);
    }

    public function show($id)
    {
        if (!$this->pdo) {
            return json_encode(['error' => 'Database not connected']);
        }

        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$task) {
            http_response_code(404);
            return json_encode(['error' => 'Task not found']);
        }
        
        return json_encode(['task' => $task]);
    }

    public function update($id)
    {
        if (!$this->pdo) {
            return json_encode(['error' => 'Database not connected']);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = ?, description = ?, completed = ? WHERE id = ?");
        $stmt->execute([
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['completed'] ?? 0,
            $id
        ]);
        
        return json_encode(['message' => 'Task updated']);
    }

    public function destroy($id)
    {
        if (!$this->pdo) {
            return json_encode(['error' => 'Database not connected']);
        }

        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        
        return json_encode(['message' => 'Task deleted']);
    }
}
