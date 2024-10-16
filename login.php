<?php
session_start();
require 'db_con.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = $db_con->prepare("SELECT * FROM users WHERE username=?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    
    if ($result->num_rows > 0) { 
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['user_id']; 
    $_SESSION['username'] = $user['username']; 
    header("Location: index.php"); 
    exit(); 
} else { 
    $error = "Username atau password salah!"; 
} 
    
    $sql->close();
    $db_con->close();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        </style>
</head>
<body>
<div class="container">
    <h1>Login</h1>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class='alert'>
                <?= $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']);?>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <?php if (!empty($error)) echo "<p class='text-danger'>$error</p>"; ?>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>