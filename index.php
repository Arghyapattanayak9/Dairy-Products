<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Masters - Premium Dairy Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
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
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
                <h2 class="retro-title">Premium Dairy Products</h2>
                <p class="retro-subtitle">Experience the finest quality dairy products from local farms</p>
                <a href="products.php" class="cta-button retro-button">
                    <span>Shop Now</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="hero-pattern"></div>
        </section>

        <section class="featured-products">
            <h2 class="section-title" data-aos="fade-up">Featured Products</h2>
            <div class="product-grid">
                <?php
                require_once 'config/database.php';
                
                $query = "SELECT * FROM products WHERE name IN ('Fresh Milk', 'Premium Cheese', 'Organic Butter', 'Paneer')";
                $result = mysqli_query($conn, $query);
                
                while($product = mysqli_fetch_assoc($result)) {
                    echo '<div class="product-card" data-aos="fade-up" data-aos-delay="' . ($product['id'] * 200) . '">';
                    echo '<div class="product-image">';
                    echo '<img src="' . $product['image_url'] . '" alt="' . $product['name'] . '">';
                    echo '<div class="product-overlay">';
                    echo '<a href="products.php?id=' . $product['id'] . '" class="view-button">View Details</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="product-info">';
                    echo '<h3>' . $product['name'] . '</h3>';
                    echo '<p class="price">$' . $product['price'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>

        <section class="features">
            <div class="feature-card" data-aos="fade-right">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Fast Delivery</h3>
                <p>Quick and reliable delivery to your doorstep</p>
            </div>
            <div class="feature-card" data-aos="fade-up">
                <div class="feature-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Organic Products</h3>
                <p>100% natural and organic dairy products</p>
            </div>
            <div class="feature-card" data-aos="fade-left">
                <div class="feature-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h3>Quality Guaranteed</h3>
                <p>Premium quality products from local farms</p>
            </div>
        </section>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });

        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    </script>
</body>
</html>