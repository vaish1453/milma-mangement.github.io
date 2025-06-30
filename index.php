<?php
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == 'admin' && $password == 'admin123') {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Milma Product Management</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/bg2.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            backdrop-filter: blur(3px);
        }

        h1 {
            color: #fff;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.7);
            margin-bottom: 20px;
        }

        .login-card {
            width: 400px;
            background: rgba(0, 0, 0, 0.7); /* Light black transparent */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.6);
            text-align: center;
            color: #fff;
        }

        .login-card h3 {
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }

        .form-control {
            margin-bottom: 15px;
            text-align: center;
        }

        .btn-center {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>

<h1>Milma Product Management</h1>

<div class="login-card">
    <h3>Admin Login</h3>

    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" class="form-control" placeholder="Enter Admin Username" required>
        <input type="password" name="password" class="form-control" placeholder="Enter Password" required>

        <div class="btn-center">
            <button type="submit" name="login" class="btn btn-primary w-50">Login</button>
        </div>
    </form>
</div>

</body>
</html>