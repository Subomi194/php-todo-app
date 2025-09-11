<?php

    declare(strict_types=1);

    require_once __DIR__ . '/TodoDTO.php';

    class TodoModel
    {
        private PDO $pdo;
        private int $userId;

        public function __construct(PDO $pdo, int $userId){
            $this->pdo =$pdo;
            $this->userId =$userId;
        }

        public function getUserTasks(): array{
            $stmt = $this->pdo->prepare("
                SELECT * FROM todos WHERE user_id = :user_id AND status !='deleted' ORDER BY created_at DESC
            ");
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $tasks = [];
            foreach ($rows as $row) {
                $tasks[] = TodoDTO::create($row);
            }

            return $tasks;
        }
      
        public function insertTask(string $task): bool{
            $stmt = $this->pdo->prepare("
                INSERT INTO todos (user_id, task, status) 
                VALUES (:user_id, :task, 'pending')
            ");
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->bindParam(':task', $task);

            return $stmt->execute();
        }
    
        public function deleteTask(int $taskId): int{
            $stmt = $this->pdo->prepare("
                DELETE FROM todos WHERE id = :id AND user_id = :user_id
            ");
            $stmt->bindParam(':id', $taskId);
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();
            
            return $stmt->rowCount();
        }

        public function toggleTaskStatus(int $taskId): bool{
            $stmt = $this->pdo->prepare("
                SELECT status FROM todos WHERE id = :id AND user_id = :user_id
            ");
            $stmt->bindParam(':id', $taskId);
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return false; // task not found
            }

            $newStatus = ($row['status'] === 'completed') ? 'pending' : 'completed';

            $stmt = $this->pdo->prepare("
                UPDATE todos SET status = :status WHERE id = :id AND user_id = :user_id
            ");
            $stmt->bindParam(':status', $newStatus);
            $stmt->bindParam(':id', $taskId);
            $stmt->bindParam(':user_id', $this->userId);

            return $stmt->execute();
        }


    }





    
    
