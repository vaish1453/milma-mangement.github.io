<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include 'db.php';

// Add Product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $mrp = $_POST['mrp_price'];
    $wholesale = $_POST['wholesale_price'];

    // Image upload handling
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    mysqli_query($conn, "INSERT INTO products (name, image, mrp_price, wholesale_price) VALUES ('$name', '$image', '$mrp', '$wholesale')");
    echo "<script>alert('Product Added Successfully'); window.location='product.php';</script>";
}

// Update Product
if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $mrp = $_POST['mrp_price'];
    $wholesale = $_POST['wholesale_price'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $image_query = ", image='$image'";
    } else {
        $image_query = "";
    }

    mysqli_query($conn, "UPDATE products SET name='$name', mrp_price='$mrp', wholesale_price='$wholesale' $image_query WHERE id=$id");
    echo "<script>alert('Product Updated Successfully'); window.location='product.php';</script>";
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    echo "<script>alert('Product Deleted Successfully'); window.location='product.php';</script>";
}

// Search
$where = "";
if (isset($_GET['search']) && !empty($_GET['search_text'])) {
    $search = $_GET['search_text'];
    $where = "WHERE name LIKE '%$search%'";
}

$result = mysqli_query($conn, "SELECT * FROM products $where");
?><!DOCTYPE html><html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Management</title>
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
<body><div class="container">
<h2 class="text-center mb-4">Product Management</h2><form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="search_text" class="form-control" placeholder="Search by Product Name">
    </div>
    <div class="col-md-2">
        <button type="submit" name="search" class="btn btn-dark">Search</button>
        <a href="product.php" class="btn btn-secondary">Reset</a>
    </div>
</form><form method="POST" enctype="multipart/form-data" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="text" name="name" class="form-control" placeholder="Product Name" required>
    </div>
    <div class="col-md-3">
        <input type="file" name="image" class="form-control" required>
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" name="mrp_price" class="form-control" placeholder="MRP Price" required>
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" name="wholesale_price" class="form-control" placeholder="Wholesale Price" required>
    </div>
    <div class="col-md-2">
        <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
    </div>
</form><table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Product Name</th>
    <th>Image</th>
    <th>MRP Price</th>
    <th>Wholesale Price</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><img src="uploads/<?= $row['image'] ?>" width="50"></td>
    <td>₹<?= $row['mrp_price'] ?></td>
    <td>₹<?= $row['wholesale_price'] ?></td>
    <td>
        <button class="btn btn-primary btn-sm" onclick="editProduct(<?= $row['id'] ?>, '<?= $row['name'] ?>', '<?= $row['image'] ?>', <?= $row['mrp_price'] ?>, <?= $row['wholesale_price'] ?>)">Edit</button>
        <a href="product.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</a>
    </td>
</tr>
<?php } ?>
</tbody>
</table><!-- Edit Modal --><div class="modal fade" id="editModal" tabindex="-1">
<div class="modal-dialog">
<form method="POST" enctype="multipart/form-data" class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="id" id="edit_id">
        <div class="mb-2">
            <label>Product Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Product Image (Leave empty to keep existing)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-2">
            <label>MRP Price</label>
            <input type="number" step="0.01" name="mrp_price" id="edit_mrp" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Wholesale Price</label>
            <input type="number" step="0.01" name="wholesale_price" id="edit_wholesale" class="form-control" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" name="update_product" class="btn btn-success">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    </div>
</form>
</div>
</div><a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>

</div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script><script>
function editProduct(id, name, image, mrp, wholesale) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_mrp').value = mrp;
    document.getElementById('edit_wholesale').value = wholesale;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script></body>
</html>