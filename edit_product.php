<?php

require_once "db.php";

$id = $_GET["id"] ?? "";

if (!filter_var($id, FILTER_VALIDATE_INT)) {
    header(
        "Location: index.php?type=error&message=" .
        urlencode("Invalid product ID.")
    );

    exit;
}

$stmt = $conn->prepare(
    "SELECT * FROM products WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$product = $result->fetch_assoc();

if (!$product) {
    header(
        "Location: index.php?type=error&message=" .
        urlencode("Product not found.")
    );

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Product</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container small-container">

    <header>
        <h1>Edit Product</h1>
        <p>Update product information</p>
    </header>

    <section class="card">

        <form action="update_product.php" method="POST" class="product-form">

            <input
                type="hidden"
                name="id"
                value="<?php echo $product["id"]; ?>"
            >

            <div class="form-group">
                <label for="product_name">Product Name</label>

                <input
                    type="text"
                    id="product_name"
                    name="product_name"
                    value="<?php echo htmlspecialchars($product["product_name"]); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="category">Category</label>

                <input
                    type="text"
                    id="category"
                    name="category"
                    value="<?php echo htmlspecialchars($product["category"]); ?>"
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
                    value="<?php echo $product["price"]; ?>"
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
                    value="<?php echo $product["quantity"]; ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="supplier">Supplier</label>

                <input
                    type="text"
                    id="supplier"
                    name="supplier"
                    value="<?php echo htmlspecialchars($product["supplier"]); ?>"
                    required
                >
            </div>

            <div class="form-actions">

                <button type="submit" class="btn primary">
                    Update Product
                </button>

                <a href="index.php" class="btn secondary">
                    Cancel
                </a>

            </div>

        </form>

    </section>

</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>