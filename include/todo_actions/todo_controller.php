<?php

    require_once __DIR__ . "/../config/session_config.php";

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /PHP101/todo");
        exit();
    }

    if (isset($_SESSION['user_id'])) {
        
        try{
            require_once __DIR__ . '/../config/database.php';
            require_once __DIR__ . "/todo_model.php";

            $todoModel = new TodoModel($pdo, $_SESSION['user_id']);

            //add task
            if (isset($_POST["addTask"])){
                $task = trim($_POST['task']);
                if (!empty($task)) {
                    $todoModel->insertTask($task);
                }
            } elseif (isset($_POST["deleteTask"])) {
                $taskId = (int)$_POST['task_id'];
                $todoModel->deleteTask($taskId);

            } elseif (isset($_POST['toggleTask'])) {
                $taskId = (int)$_POST['task_id'];
                $todoModel->toggleTaskStatus($taskId);
                
            }

            header("Location: /PHP101/todo");
            exit();

        }catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }

    }else{
        header("Location: /PHP101/login");
        exit();
    }

   
