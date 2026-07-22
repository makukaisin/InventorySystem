<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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

if (filter_var($quantity, FILTER_VALIDATE_INT) === false) {
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

$stmt = null;

try {
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

    $stmt->execute();

    header(
        "Location: index.php?type=success&message=" .
        urlencode("Product added successfully.")
    );
} catch (mysqli_sql_exception $error) {
    if ($error->getCode() === 1062) {
        header(
            "Location: index.php?type=error&message=" .
            urlencode("Product name already exists. Please use another name.")
        );
    } else {
        header(
            "Location: index.php?type=error&message=" .
            urlencode("Unable to add product. Please try again.")
        );
    }
} finally {
    if ($stmt !== null) {
        $stmt->close();
    }

    $conn->close();
}

exit;
?>