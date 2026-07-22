<?php

require_once "db.php";

function redirectWithMessage(string $type, string $message): void
{
    header(
        "Location: index.php?type=" . urlencode($type) .
        "&message=" . urlencode($message)
    );
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$productId = $_POST["id"] ?? "";
$productName = trim($_POST["product_name"] ?? "");
$category = trim($_POST["category"] ?? "");
$price = $_POST["price"] ?? "";
$quantity = $_POST["quantity"] ?? "";
$supplier = trim($_POST["supplier"] ?? "");

{
    redirectWithMessage("error", "Invalid product ID.");
}

$productId = (int) $productId;

if (
    $productName === "" ||
    $category === "" ||
    $price === "" ||
    $quantity === "" ||
    $supplier === ""
) {
    redirectWithMessage("error", "All fields are required.");
}

if (!is_numeric($price) || (float) $price < 0) {
    redirectWithMessage("error", "Please enter a valid price.");
}

$price = (float) $price;

 {
    redirectWithMessage(
        "error",
        "Please enter a valid non-negative quantity."
    );
}

$quantity = (int) $quantity;

try {
    $checkStmt = $conn->prepare(
        "SELECT id
         FROM products
         WHERE product_name = ?
         AND id != ?
         LIMIT 1"
    );

    $checkStmt->bind_param("si", $productName, $productId);
    $checkStmt->execute();

    $duplicateResult = $checkStmt->get_result();

    if ($duplicateResult->num_rows > 0) {
        $checkStmt->close();

        redirectWithMessage(
            "error",
            "Another product already uses the name {$productName}."
        );
    }

    $checkStmt->close();

    $stmt = $conn->prepare(
        "UPDATE products
         SET product_name = ?,
             category = ?,
             price = ?,
             quantity = ?,
             supplier = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "ssdisi",
        $productName,
        $category,
        $price,
        $quantity,
        $supplier,
        $productId
    );

    $stmt->execute();

    $stmt->close();
    $conn->close();

    redirectWithMessage(
        "success",
        "Product updated successfully."
    );

} catch (mysqli_sql_exception $exception) {
    $conn->close();

    if ($exception->getCode() === 1062) {
        redirectWithMessage(
            "error",
            "The product name {$productName} already exists."
        );
    }

    redirectWithMessage(
        "error",
        "Database error. Unable to update the product."
    );
}
?>