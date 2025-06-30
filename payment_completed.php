<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include 'db.php';

// Handle Add Payment
if (isset($_POST['add_payment'])) {
    $customer = $_POST['customer_name'];
    $mobile = $_POST['mobile_number'];
    $paid = $_POST['paid_amount'];
    $bill = $_POST['bill_amount'];
    $payment_date = $_POST['payment_date'];

    if (strlen($mobile) != 10 || !ctype_digit($mobile)) {
        echo "<script>alert('Mobile number must be exactly 10 digits');</script>";
    } elseif ($paid > $bill) {
        echo "<script>alert('Paid amount cannot exceed Bill amount');</script>";
    } else {
        $pending = $bill - $paid;
        mysqli_query($conn, "INSERT INTO payments (customer_name, mobile_number, paid_amount, bill_amount, pending_amount, payment_date) 
                             VALUES ('$customer', '$mobile', '$paid', '$bill', '$pending', '$payment_date')");
        echo "<script>alert('Payment Added Successfully'); window.location='payment_completed.php';</script>";
    }
}

// Handle Edit Payment
if (isset($_POST['update_payment'])) {
    $id = $_POST['id'];
    $customer = $_POST['customer_name'];
    $mobile = $_POST['mobile_number'];
    $paid = $_POST['paid_amount'];
    $bill = $_POST['bill_amount'];
    $payment_date = $_POST['payment_date'];

    if (strlen($mobile) != 10 || !ctype_digit($mobile)) {
        echo "<script>alert('Mobile number must be exactly 10 digits');</script>";
    } elseif ($paid > $bill) {
        echo "<script>alert('Paid amount cannot exceed Bill amount');</script>";
    } else {
        $pending = $bill - $paid;
        mysqli_query($conn, "UPDATE payments SET 
                             customer_name='$customer', mobile_number='$mobile', paid_amount='$paid', bill_amount='$bill', pending_amount='$pending', payment_date='$payment_date' 
                             WHERE id=$id");
        echo "<script>alert('Payment Updated Successfully'); window.location='payment_completed.php';</script>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM payments WHERE id=$id");
    echo "<script>alert('Payment Deleted Successfully'); window.location='payment_completed.php';</script>";
}

// Filtering Logic
$where = "";
$title = "All Payments";
if (isset($_GET['filter']) && $_GET['filter'] == "completed") {
    $where = "WHERE pending_amount = 0";
    $title = "Completed Payments";
} elseif (isset($_GET['filter']) && $_GET['filter'] == "pending") {
    $where = "WHERE pending_amount > 0";
    $title = "Pending Payments";
} elseif (isset($_GET['search']) && !empty($_GET['search_mobile'])) {
    $search_mobile = $_GET['search_mobile'];
    $where = "WHERE mobile_number LIKE '%$search_mobile%'";
}

$result = mysqli_query($conn, "SELECT * FROM payments $where ORDER BY payment_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f0f2f5;
    padding: 40px;
}
.container {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}
</style>
</head>
<body>

<div class="container">
<h2 class="text-center mb-4"><?= $title ?></h2>

<!-- Filter Buttons -->
<div class="mb-3">
    <a href="payment_completed.php" class="btn btn-secondary">All Payments</a>
    <a href="payment_completed.php?filter=completed" class="btn btn-success">Completed Payments</a>
    <a href="payment_completed.php?filter=pending" class="btn btn-warning">Pending Payments</a>
</div>

<!-- Search Form -->
<form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="search_mobile" class="form-control" placeholder="Search by Mobile Number">
    </div>
    <div class="col-md-2">
        <button type="submit" name="search" class="btn btn-dark">Search</button>
    </div>
</form>

<!-- Add Payment Form -->
<form method="POST" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" required>
    </div>
    <div class="col-md-2">
        <input type="text" name="mobile_number" class="form-control" placeholder="Mobile Number" maxlength="10" required>
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" name="paid_amount" class="form-control" placeholder="Paid Amount" required>
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" name="bill_amount" class="form-control" placeholder="Bill Amount" required>
    </div>
    <div class="col-md-2">
        <input type="date" name="payment_date" class="form-control" required>
    </div>
    <div class="col-md-1">
        <button type="submit" name="add_payment" class="btn btn-success w-100">Add</button>
    </div>
</form>

<!-- Payments Table -->
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Customer Name</th>
    <th>Mobile</th>
    <th>Paid</th>
    <th>Pending</th>
    <th>Bill</th>
    <th>Date</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['customer_name'] ?></td>
    <td><?= $row['mobile_number'] ?></td>
    <td>₹<?= $row['paid_amount'] ?></td>
    <td>₹<?= $row['pending_amount'] ?></td>
    <td>₹<?= $row['bill_amount'] ?></td>
    <td><?= $row['payment_date'] ?></td>
    <td>
        <button class="btn btn-primary btn-sm" onclick="editPayment(
            <?= $row['id'] ?>, 
            '<?= $row['customer_name'] ?>', 
            '<?= $row['mobile_number'] ?>', 
            <?= $row['paid_amount'] ?>, 
            <?= $row['bill_amount'] ?>, 
            '<?= $row['payment_date'] ?>')">Edit</button>
        <a href="payment_completed.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this payment?')">Delete</a>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
<a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
<div class="modal-dialog">
<form method="POST" class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Edit Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="id" id="edit_id">
        <div class="mb-2">
            <label>Customer Name</label>
            <input type="text" name="customer_name" id="edit_customer" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Mobile Number</label>
            <input type="text" name="mobile_number" id="edit_mobile" class="form-control" required maxlength="10">
        </div>
        <div class="mb-2">
            <label>Paid Amount</label>
            <input type="number" name="paid_amount" id="edit_paid" class="form-control" required step="0.01">
        </div>
        <div class="mb-2">
            <label>Bill Amount</label>
            <input type="number" name="bill_amount" id="edit_bill" class="form-control" required step="0.01">
        </div>
        <div class="mb-2">
            <label>Payment Date</label>
            <input type="date" name="payment_date" id="edit_date" class="form-control" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" name="update_payment" class="btn btn-success">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    </div>
</form>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editPayment(id, customer, mobile, paid, bill, date) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_customer').value = customer;
    document.getElementById('edit_mobile').value = mobile;
    document.getElementById('edit_paid').value = paid;
    document.getElementById('edit_bill').value = bill;
    document.getElementById('edit_date').value = date;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
</body>
</html>