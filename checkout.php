<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

$total = 0;
$cartItems = [];

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM products WHERE id IN ($ids)";
    $result = mysqli_query($conn, $query);

    while ($product = mysqli_fetch_assoc($result)) {
        $product['quantity'] = $_SESSION['cart'][$product['id']];
        $product['subtotal'] = $product['price'] * $product['quantity'];
        $total += $product['subtotal'];
        $cartItems[] = $product;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save order to database
    $userId = $_SESSION['user_id'];
    $query = "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("id", $userId, $total);
    $stmt->execute();
    
    $orderId = $conn->insert_id;
    
    foreach ($cartItems as $item) {
        $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
    
    $_SESSION['cart'] = [];
    
    header('Location: success.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Milk Masters</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="retro-header">
        <nav>
            <div class="logo">
                <h1>Milk Masters</h1>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.php">Cart</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="checkout-main">
        <div class="checkout-container" style="margin-top: 3rem; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 3rem auto;">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 1.5rem;">Checkout</h2>
            <p style="font-size: 1.2rem; margin-bottom: 2rem;">Total Amount: <strong>$<?php echo number_format($total, 2); ?></strong></p>
            <form method="POST" action="checkout.php">
                <button type="submit" class="checkout-button" style="display: inline-block; padding: 1rem 2rem; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: 5px; font-weight: 500; transition: background-color 0.3s ease;">Pay Now</button>
            </form>
        </div>
    </main>

    <footer class="retro-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Milk Masters - Your trusted source for premium dairy products since 2024.</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: info@milkmasters.com</p>
                <p>Phone: (123) 456-7890</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Milk Masters. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 