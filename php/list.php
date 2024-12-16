<?php
include "db.php";
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = ""; 
$error_message = "";
$tasks = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task']) && empty($_POST['task_id'])) {
    $task = mysqli_real_escape_string($link, $_POST['task']);

    // Add task.
    $sql = "INSERT INTO todos (title, user_id) VALUES ('$task', $user_id)";
    if (mysqli_query($link, $sql)) {
        $success_message = "Task added successfully!";
    } else {
        $error_message = "Failed to add task. Please try again.";
    }
}

// Delete task.
if (isset($_GET['delete'])) {
    $task_id = intval($_GET['delete']);
    $sql = "DELETE FROM todos WHERE id = $task_id AND user_id = $user_id";
    if (mysqli_query($link, $sql)) {
        $success_message = "Task deleted successfully!";
    } else {
        $error_message = "Failed to delete task.";
    }
}

// Edit task.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);
    $task = mysqli_real_escape_string($link, $_POST['task']);

    $sql = "UPDATE todos SET title = '$task' WHERE id = $task_id AND user_id = $user_id";
    if (mysqli_query($link, $sql)) {
        $success_message = "Task updated successfully!";
    } else {
        $error_message = "Failed to update task.";
    }
}

// Display task.
$sql = "SELECT id, title FROM todos WHERE user_id = $user_id";
$result = mysqli_query($link, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tasks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="icon" type="image/jpg" href="../images/favicon.jpg">
</head>

<body>
    <fieldset>
        <legend>Tasks</legend>
        <div class="welcome">
            <h1>To-Do List</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <hr>
            <br>
        </div>

        <?php if (!empty($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>
        <?php if (!empty($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

        <div class="task">
            <form method="post" action="">
                <input type="text" name="task" placeholder="Add a new task..." required>
                <input type="hidden" name="task_id" id="task_id" value="">
                <button type="submit" class="addTask">Add Task</button>
            </form>
        </div>

        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <?php echo htmlspecialchars($task['title']); ?>
                    <div class="buttonGroup">
                        <button class="editTask" onclick="editTask(<?php echo $task['id']; ?>, '<?php echo htmlspecialchars($task['title']); ?>')">Edit</button>
                        <a href="?delete=<?php echo $task['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?')">
                            <button class="deleteTask">Delete</button>
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>

    <script>
        function editTask(id, title) {
            document.querySelector('input[name="task"]').value = title;
            document.querySelector('input[name="task_id"]').value = id;
        }
    </script>

    <footer>
        <p>&copy; 2024 To-Do List. All Rights Reserved.</p>
    </footer>
</body>

</html>