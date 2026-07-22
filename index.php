<?php
require_once "db.php";

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");

$message = $_GET["message"] ?? "";
$type = $_GET["type"] ?? "success";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Product Inventory System</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <header>
        <h1>Product Inventory System</h1>
        <p>PHP and MySQL CRUD Application</p>
    </header>
    <?php if ($message !== ""): ?>

<div class="alert <?php echo htmlspecialchars($type); ?>">
    <?php echo htmlspecialchars($message); ?>
</div>

<?php endif; ?>

<section class="card">

<h2>Add Product</h2>

<form action="save_product.php" method="POST" class="product-form">

    <div class="form-group">
        <label for="product_name">Product Name</label>

        <input
            type="text"
            id="product_name"
            name="product_name"
            required
        >
    </div>

    <div class="form-group">
        <label for="category">Category</label>

        <input
            type="text"
            id="category"
            name="category"
            required
        >
    </div>

    <div class="form-group">
        <label for="price">Price</label>

        <input
            type="number"
            id="price"
            name="price"
            min="0"
            step="0.01"
            required
        >
    </div>

    <div class="form-group">
        <label for="quantity">Quantity</label>

        <input
            type="number"
            id="quantity"
            name="quantity"
            min="0"
            required
        >
    </div>

    <div class="form-group">
        <label for="supplier">Supplier</label>

        <input
            type="text"
            id="supplier"
            name="supplier"
            required
        >
    </div>

    <div class="form-actions">
        <button type="submit" class="btn primary">
            Add Product
        </button>

        <button type="reset" class="btn secondary">
            Clear
        </button>
    </div>

</form>

</section>

</div>

</body>
</html>

<?php
$conn->close();
?>