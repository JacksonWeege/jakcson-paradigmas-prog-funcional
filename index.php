<!DOCTYPE html>
<html>
<head>
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <h1>Gerenciador de Tarefas</h1>
    
    <form action="index.php" method="post">
        <input type="text" name="task" placeholder="Adicionar nova tarefa" required>
        <button type="submit">Adicionar</button>
    </form>
    
    <h2>Tarefas Pendentes</h2>
<?php

$filename = 'tasks.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = $_POST['task'] . PHP_EOL;
    file_put_contents($filename, $task, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $tasks = file($filename, FILE_IGNORE_NEW_LINES);
    $taskId = $_GET['id'];
    unset($tasks[$taskId]);
    file_put_contents($filename, implode(PHP_EOL, $tasks));
    http_response_code(200);
    exit;
}

$tasks = file($filename, FILE_IGNORE_NEW_LINES);
foreach ($tasks as $taskId => $task) {
    echo '<li id="task-' . $taskId . '">' . $task . ' <button onclick="deleteTask(' . $taskId . ')">Excluir</button>
                                                      <button onclick="completeTask(' . $taskId . ')">Concluida</button></li>';
}

?>
    <script>
        function deleteTask(taskId) {
            fetch('index.php?id=' + taskId, { method: 'POST' })
                .then(response => {
                    if (response.ok) {
                        document.getElementById('task-' + taskId).remove();
                    }
                });
        }

        function completeTask(taskId) {
            fetch('index.php?id=' + taskId + '&action=complete', { method: 'POST' })
                .then(response => {
                    if (response.ok) {
                        document.getElementById('task-' + taskId).classList.add('completed');
                    }
                });
        }
    </script>
</body>
</html>
