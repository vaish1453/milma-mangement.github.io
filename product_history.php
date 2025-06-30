<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include 'db.php';

// Handle Search by Arrived Date
$where = "";
if (isset($_GET['search']) && !empty($_GET['arrived_date'])) {
    $arrived_date = $_GET['arrived_date'];
    $where = "WHERE arrived_date = '$arrived_date'";
}

$result = mysqli_query($conn, "SELECT * FROM stock_history $where ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product History</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f2f5; padding: 40px; }
.container { background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
</style>
</head>
<body>

<div class="container">
<h2 class="text-center mb-4">Product History</h2>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="date" name="arrived_date" class="form-control" value="<?php echo isset($_GET['arrived_date']) ? $_GET['arrived_date'] : ''; ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" name="search" class="btn btn-dark">Search</button>
        <a href="product_history.php" class="btn btn-secondary">Reset</a>
    </div>
</form>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Product Name</th>
    <th>Arrived Date</th>
    <th>Stock In</th>
    <th>Stock Out</th>
    <th>Current Stock In</th>
    <th>Current Stock Out</th>
    <th>Available Stock</th>
    <th>Manufactured Date</th>
    <th>Expiry Date</th>
    <th>Remarks</th>
</tr>
</thead>
<tbody>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['arrived_date']; ?></td>
    <td><?php echo $row['stock_in']; ?></td>
    <td><?php echo $row['stock_out']; ?></td>
    <td><?php echo $row['current_stock_in']; ?></td>
    <td><?php echo $row['current_stock_out']; ?></td>
    <td><?php echo $row['current_stock']; ?></td>
    <td><?php echo $row['manufactured_date']; ?></td>
    <td><?php echo $row['expiry_date']; ?></td>
    <td><?php echo $row['remarks']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>