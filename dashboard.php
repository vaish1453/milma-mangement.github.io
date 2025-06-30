<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Milma Admin Dashboard</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/bg1.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            backdrop-filter: blur(3px);
        }

        .dashboard-container {
            text-align: center;
            width: 100%;
            max-width: 900px;
            padding: 30px;
        }

        h2 {
            margin-bottom: 40px;
            color: #fff;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.7);
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.4);
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 24px rgba(0,0,0,0.5);
        }

        .logout-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
        }

        a.text-decoration-none h4, a.text-decoration-none p {
            color: white;
        }
    </style>
</head>
<body>

<a href="logout.php" class="btn btn-danger logout-btn">Logout</a>

<div class="dashboard-container">
    <h2>Milma Product Management - Admin Dashboard</h2>

    <div class="row g-4 justify-content-center">

        <!-- Products -->
        <div class="col-md-4">
            <a href="product.php" class="text-decoration-none">
                <div class="card text-center p-3" style="background-color: #007bff;">
                    <h4>Products</h4>
                    <p>Manage Product List</p>
                </div>
            </a>
        </div>

        <!-- Stock In -->
        <div class="col-md-4">
            <a href="stock_managment.php" class="text-decoration-none">
                <div class="card text-center p-3" style="background-color:#6a5acd;">
                    <h4>Stock Managment</h4>
                    <p>View Stock Entries & Outflow</p>
                </div>
            </a>
        </div>

        <!-- Payment Completed -->
        <div class="col-md-4">
            <a href="payment_completed.php" class="text-decoration-none">
                <div class="card text-center p-3" style="background-color: #FFc300;">
                    <h4>Payment Details</h4>
                    <p>Track Completed & Pending Payments</p>
                </div>
            </a>
        </div>

<!-- History -->
        <div class="col-md-4">
            <a href="product_history.php" class="text-decoration-none">
                <div class="card text-center p-3" style="background-color:#28a745;">
                    <h4>History</h4>
                    <p>View Full History</p>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>