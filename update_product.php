<?php

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$productName = trim($_POST["product_name"] ?? "");
$category = trim($_POST["category"] ?? "");
$price = $_POST["price"] ?? "";
$quantity = $_POST["quantity"] ?? "";
$supplier = trim($_POST["supplier"] ?? "");

if (
    $productName === "" ||
    $category === "" ||
    $price === "" ||
    $quantity === "" ||
    $supplier === ""
) {
    header(
        "Location: index.php?type=error&message=" .
        urlencode("All fields are required.")
    );

    exit;
}

if (!is_numeric($price) || $price < 0) {
    header(
        "Location: index.php?type=error&message=" .
        urlencode("Please enter a valid price.")
    );

    exit;
}

if (!filter_var($quantity, FILTER_VALIDATE_INT) && $quantity != 0) {
    header(
        "Location: index.php?type=error&message=" .
        urlencode("Please enter a valid quantity.")
    );

    exit;
}

if ($quantity < 0) {
    header(
        "Location: index.php?type=error&message=" .
        urlencode("Quantity cannot be negative.")
    );

    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO products
    (product_name, category, price, quantity, supplier)
    VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "ssdis",
    $productName,
    $category,
    $price,
    $quantity,
    $supplier
);

if ($stmt->execute()) {

    header(
        "Location: index.php?type=success&message=" .
        urlencode("Product added successfully.")
    );

} else {

    header(
        "Location: index.php?type=error&message=" .
        urlencode("Unable to add product.")
    );
}

$stmt->close();
$conn->close();

exit;
?>