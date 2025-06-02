<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_text'])) {
    $task_text = trim($_POST['task_text']);
    if (!empty($task_text)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task_text) VALUES (?, ?)");
        $stmt->execute([$user_id, $task_text]);
    }
}

if (isset($_GET['complete'])) {
    $task_id = $_GET['complete'];
    $stmt = $pdo->prepare("UPDATE tasks SET completed = TRUE WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
}

if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
}

$stmt = $pdo->prepare("SELECT id, task_text, completed FROM tasks WHERE user_id = ? ORDER BY id");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #1e1e2f;
            color: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #2a2a3d;
            padding: 30px;
            margin-top: 20px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 0 15px rgba(0,0,0,0.6);
        }

        .todo-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .todo-form input {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            outline: none;
        }

        .todo-form button {
            padding: 10px 20px;
            background-color: #ff9a76;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .task-list {
            list-style: none;
            padding: 0;
        }

        .task-list li {
            background: #3a3a4f;
            margin-bottom: 10px;
            padding: 12px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-list li.completed {
            text-decoration: line-through;
            opacity: 0.6;
        }

        .task-actions .btn {
            background-color: #5c5cff;
            padding: 6px 10px;
            border-radius: 6px;
            color: white;
            margin-left: 8px;
            text-decoration: none;
        }

        .task-actions .delete {
            background-color: #ff4d4d;
        }

        .logout {
            display: block;
            text-align: center;
            margin-top: 20px;
            background-color: #d63384;
            padding: 10px 15px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
        }

        header {
            width: 100%;
            background: #2d2d44;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .profile-photo {
            width: 360px;
            height: 180px;
            object-fit: cover;
            border-radius: 16px;
            border: 3px solid #ff9a76;
        }

        .header-text h1, .header-text h2 {
            margin: 0;
        }

        .header-text h1 {
            font-size: 20px;
        }

        .header-text h2 {
            font-size: 16px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <img src="photo.jpg" alt="Hood" class="profile-photo">
            <div class="header-text">
                <h1>Alogo Situmorang</h1>
                <h2>235314088</h2>
            </div>
        </div>
    </header>
    <div class="container">
        <h2>To-Do List</h2>
        <form method="POST" action="todo.php" class="todo-form">
            <i class="fas fa-plus"></i>
            <input type="text" name="task_text" placeholder="Tambah tugas baru..." required>
            <button type="submit">Tambah</button>
        </form>
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li class="<?php echo $task['completed'] ? 'completed' : ''; ?>">
                    <?php echo htmlspecialchars($task['task_text']); ?>
                    <div class="task-actions">
                        <?php if (!$task['completed']): ?>
                            <a href="todo.php?complete=<?php echo $task['id']; ?>" class="btn small">Selesai</a>
                        <?php endif; ?>
                        <a href="todo.php?delete=<?php echo $task['id']; ?>" class="btn small delete">Hapus</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="logout.php" class="btn logout">Logout</a>
    </div>
</body>
</html>
