<?php
    declare(strict_types=1);

    class TodoDTO
    {
        public function __construct(
            public readonly int $id,
            public readonly string $task,
            public readonly string $status,
            public readonly string $createdAt
        ){}

        public static function create(array $row): TodoDTO 
        {
            return new self(
                (int)$row['id'],
                $row['task'],
                $row['status'],
                $row['created_at']
            );

        }

        public function isCompleted(): bool
        {
            return $this->status === 'completed';
        }
    }

    