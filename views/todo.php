<?php

    require_once __DIR__ . "/../include/config/session_config.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: /PHP101/login");
        exit();
    }

    require __DIR__ . '/../include/config/database.php';
    require __DIR__ . "/../include/todo_actions/todo_model.php";

    $todoModel = new TodoModel($pdo, $_SESSION['user_id']);
    $tasks = $todoModel->getUserTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='/PHP101/css/home.css'>
    <title>To-Do List</title>
</head>
<body>

    <div class='To-Dolist'>
        <h1><?php echo htmlspecialchars($_SESSION['user']); ?>'s To-Do List</h1>

        
        <form action="/PHP101/todo-action" method="POST">
            <input class='input-box' type="text" name="task" placeholder="Enter a task..." required>
            <button class="add-button" type="submit" name="addTask">Add</button>
        </form>

        <!-- Show the tasks -->
        <ul>
            <?php if (empty($tasks)): ?>
                <li>No tasks yet — add one above.</li>
            <?php else: ?>

                <?php foreach ($tasks as $todo): ?>
                    <li class='task-item'>

                        <!-- Task -->
                        <span class="<?php echo $todo->isCompleted() ? 'completed-task' : ''; ?>">
                            <?php echo htmlspecialchars($todo->task); ?>
                        </span>

                        
                        <div class="task-actions">

                            <!-- Toggle -->
                            <form action="/PHP101/todo-action" method="POST">
                                <input type="hidden" name="task_id" value="<?php echo (int)$todo->id; ?>">

                                <button type="submit" name="toggleTask"
                                    class="<?php echo $todo->isCompleted() ? 'undo-btn' : 'active-btn'; ?>">
                                    <?php echo $todo->isCompleted() ? 'Undo' : 'Mark Completed'; ?>
                                </button>
                            </form>

                            <!-- Delete -->
                            <form action="/PHP101/todo-action" method="POST">
                                <input type="hidden" name="task_id" value="<?php echo (int)$todo->id; ?>">
                                
                                <button type="submit" name="deleteTask" class="delete-btn">Delete</button>
                            </form>
                        </div>

                    </li>

                <?php endforeach; ?>
            <?php endif; ?>
            
        </ul>

    </div>    
</body>
</html>
