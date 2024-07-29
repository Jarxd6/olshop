<?php
session_start();

// Example product data (this should normally come from a database)
$products = [
    [
        'id' => '1',
        'sku' => 'IQ001',
        'name' => 'Iqoo',
        'price' => '500.00',
        'image' => 'image/iqoo.png'
    ],
    [
        'id' => '2',
        'sku' => 'RED002',
        'name' => 'Redmi',
        'price' => '290.99',
        'image' => 'image/redmi.png'
    ],
    // Add more products as needed
];

// Initialize the cart if not set
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}

// Handle actions like adding to cart, emptying cart, etc.
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'addcart':
            $sku = $_POST['sku'];
            // Find the product by SKU
            foreach ($products as $product) {
                if ($product['sku'] == $sku) {
                    if (isset($_SESSION['products'][$sku])) {
                        $_SESSION['products'][$sku]['qty'] += 1;
                    } else {
                        $_SESSION['products'][$sku] = $product;
                        $_SESSION['products'][$sku]['qty'] = 1;
                    }
                    break;
                }
            }
            break;

        case 'empty':
            $sku = $_GET['sku'];
            unset($_SESSION['products'][$sku]);
            break;

        case 'emptyall':
            $_SESSION['products'] = [];
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PHP Shopping Cart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
  <nav class="navbar navbar-dark bg-success mb-3">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Shopping Cart</span>
      <div class="d-flex">
        <?php if (isset($_SESSION['user'])): ?>
          <a href="logout.php" class="btn btn-danger me-2">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-primary me-2">Login</a>
          <a href="register.php" class="btn btn-secondary">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <?php if (!empty($_SESSION['products'])): ?>
  <nav class="navbar navbar-dark bg-success mb-3">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Shopping Cart</span>
      <a href="index.php?action=emptyall" class="btn btn-danger">Empty cart</a>
    </div>
  </nav>
  <table class="table table-hover">
    <thead class="table-success">
      <tr>
        <th scope="col">Image</th>
        <th scope="col">Name</th>
        <th scope="col">Price</th>
        <th scope="col">Qty</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $total = 0; // Initialize total ?>
      <?php foreach ($_SESSION['products'] as $key => $product): ?>
      <tr>
        <td><img src="<?php echo $product['image'] ?>" width="50px"></td>
        <td><?php echo $product['name'] ?></td>
        <td>$<?php echo $product['price'] ?></td>
        <td><?php echo $product['qty'] ?></td>
        <td>
          <a href="index.php?action=empty&sku=<?php echo $key ?>" class="btn btn-danger">Delete</a>
          <a href="checkout.php?sku=<?php echo $key ?>" class="btn btn-primary mt-2">Buy Now</a>
        </td>
      </tr>
      <?php $total += $product['price'] * $product['qty']; ?>
      <?php endforeach; ?>
      <tr>
        <td colspan="5" class="text-end"><h4>Total: $<?php echo $total ?></h4></td>
      </tr>
    </tbody>
  </table>
  <?php endif; ?>
  <nav class="navbar navbar-dark bg-success mb-3">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Products</span>
    </div>
  </nav>
  <div class="row">
    <?php foreach ($products as $product): ?>
    <div class="col-md-4 mb-4">
      <div class="card">
        <img src="<?php echo $product['image'] ?>" class="card-img-top" alt="Product Image">
        <div class="card-body text-center">
          <h5 class="card-title"><?php echo $product['name'] ?></h5>
          <p class="card-text text-success"><b>$<?php echo $product['price'] ?></b></p>
          <form method="post" action="index.php?action=addcart">
            <input type="hidden" name="sku" value="<?php echo $product['sku'] ?>">
            <button type="submit" class="btn btn-warning">Add To Cart</button>
          </form>
          <a href="checkout.php?sku=<?php echo $product['sku'] ?>" class="btn btn-primary mt-2">Buy Now</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>