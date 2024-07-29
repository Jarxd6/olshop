<?php
session_start();
include 'config.php';

if (isset($_GET['sku'])) {
    $sku = $_GET['sku'];

    if (isset($_SESSION['products'][$sku])) {
        $product = $_SESSION['products'][$sku];
        $productName = $product['name'];
        $price = $product['price'];
        $qty = $product['qty'];
        $total = $price * $qty;

        // Simpan transaksi ke database
        $stmt = $conn->prepare("INSERT INTO transactions (sku, product_name, price, qty, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdii", $sku, $productName, $price, $qty, $total);

        if ($stmt->execute()) {
            // Hapus produk dari keranjang
            unset($_SESSION['products'][$sku]);
            $message = "Transaksi berhasil! Produk dengan SKU $sku telah dibeli.";
        } else {
            $message = "Error recording transaction: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Produk dengan SKU $sku tidak ditemukan di keranjang.";
    }
} else {
    $message = "SKU tidak ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transaksi Berhasil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <div class="alert alert-success" role="alert">
        <?php echo $message; ?>
    </div>
    <a href="index.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>