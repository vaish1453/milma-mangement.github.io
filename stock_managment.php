<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
include 'db.php';

// Handle Stock Update
if (isset($_POST['update_stock'])) {
    $id = $_POST['id'];
    $add_in = $_POST['add_stock_in'];
    $add_out = $_POST['add_stock_out'];
    $manufactured_date = $_POST['manufactured_date'];
    $expiry_date = $_POST['expiry_date'];
    $arrived_date = $_POST['arrived_date'];
    $remarks = $_POST['remarks'];

    // Fetch current product details
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id"));
    $current_stock_in = $product['stock_in'];
    $current_stock_out = $product['stock_out'];
    $name = $product['name'];

    // Prevent negative stock
    $new_total_in = $current_stock_in + $add_in;
    $new_total_out = $current_stock_out + $add_out;

    if ($new_total_out > $new_total_in) {
        echo "<script>alert('Not enough stock available!'); window.location='stock_managment.php';</script>";
        exit;
    }

    // Update products table
    $update = "UPDATE products SET 
        stock_in = $new_total_in, 
        stock_out = $new_total_out, 
        manufactured_date = '$manufactured_date', 
        expiry_date = '$expiry_date' 
        WHERE id = $id";
    mysqli_query($conn, $update);

    $current_stock = $new_total_in - $new_total_out;

    // Insert into stock_history
    mysqli_query($conn, "INSERT INTO stock_history 
    (product_id, name, arrived_date, stock_in, stock_out, current_stock_in, current_stock_out, current_stock, manufactured_date, expiry_date, remarks) 
    VALUES 
    ('$id', '$name', '$arrived_date', '$add_in', '$add_out', '$new_total_in', '$new_total_out', '$current_stock', '$manufactured_date', '$expiry_date', '$remarks')");

    echo "<script>alert('Stock Updated Successfully'); window.location='stock_managment.php';</script>";
}

$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Stock Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f2f5; padding: 40px; }
.container { background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
.table-danger td { background: #ffe0e0 !important; }
</style>
</head>
<body>

<div class="container">
<h2 class="text-center mb-4">Stock Management</h2>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>Product Name</th>
    <th>Current Stock In</th>
    <th>Add Stock In</th>
    <th>Current Stock Out</th>
    <th>Add Stock Out</th>
    <th>Available Stock</th>
    <th>Arrived Date</th>
    <th>Manufactured Date</th>
    <th>Expiry Date</th>
    <th>Remarks</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php while ($row = mysqli_fetch_assoc($result)) { 
    $available_stock = $row['stock_in'] - $row['stock_out'];
    $lowStock = $available_stock < 5;
?>
<tr class="<?php if ($lowStock) echo 'table-danger'; ?>">
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['stock_in']; ?></td>
    <td>
        <form method="POST" class="d-flex flex-wrap" onsubmit="return confirm('Confirm update?')">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <input type="number" name="add_stock_in" class="form-control mb-2 me-2" placeholder="Qty In" min="0" required>
    </td>
    <td><?php echo $row['stock_out']; ?></td>
    <td>
            <input type="number" name="add_stock_out" class="form-control mb-2 me-2" placeholder="Qty Out" min="0" required>
    </td>
    <td><?php echo $available_stock; ?></td>
    <td>
            <input type="date" name="arrived_date" class="form-control mb-2" required>
    </td>
    <td>
            <input type="date" name="manufactured_date" class="form-control mb-2" value="<?php echo $row['manufactured_date']; ?>" required>
    </td>
    <td>
            <input type="date" name="expiry_date" class="form-control mb-2" value="<?php echo $row['expiry_date']; ?>" required>
    </td>
    <td>
            <input type="text" name="remarks" class="form-control mb-2" placeholder="Remarks">
    </td>
    <td>
            <button type="submit" name="update_stock" class="btn btn-success">Update</button>
        </form>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
<a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>