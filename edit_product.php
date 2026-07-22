<?php

require_once "db.php";

$productId = $_GET["id"] ?? "";


$productId = (int) $productId;

$stmt = $conn->prepare(
    "SELECT id, product_name, category, price, quantity, supplier
     FROM products
     WHERE id = ?"
);

$stmt->bind_param("i", $productId);
$stmt->execute();

$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header("Location: index.php");
    exit;
}

$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>

    <h1>Edit Product</h1>

    <form action="update_product.php" method="POST">

        <input
            type="hidden"
            name="id"
            value="<?= htmlspecialchars($product["id"]) ?>"
        >

        <label for="product_name">Product Name</label>
        <input
            type="text"
            id="product_name"
            name="product_name"
            value="<?= htmlspecialchars($product["product_name"]) ?>"
            required
        >

        <label for="category">Category</label>
        <input
            type="text"
            id="category"
            name="category"
            value="<?= htmlspecialchars($product["category"]) ?>"
            required
        >

        <label for="price">Price</label>
        <input
            type="number"
            id="price"
            name="price"
            value="<?= htmlspecialchars($product["price"]) ?>"
            min="0"
            step="0.01"
            required
        >

        <label for="quantity">Quantity</label>
        <input
            type="number"
            id="quantity"
            name="quantity"
            value="<?= htmlspecialchars($product["quantity"]) ?>"
            min="0"
            required
        >

        <label for="supplier">Supplier</label>
        <input
            type="text"
            id="supplier"
            name="supplier"
            value="<?= htmlspecialchars($product["supplier"]) ?>"
            required
        >

        <button type="submit">Update Product</button>

        <a href="index.php">Cancel</a>

    </form>

</body>
</html>