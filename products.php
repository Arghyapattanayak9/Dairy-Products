<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Milk Masters</title>
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

    <main class="products-main">
        <section class="products-section">
            <h2>Our Dairy Products</h2>
            <div class="product-filters">
                <select id="category-filter">
                    <option value="">All Categories</option>
                    <option value="Milk">Milk</option>
                    <option value="Cheese">Cheese</option>
                    <option value="Butter">Butter</option>
                    <option value="Paneer">Paneer</option>
                </select>
                <div class="search-box">
                    <input type="text" id="search-input" placeholder="Search products...">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <div class="product-grid">
                <?php
                $query = "SELECT * FROM products";
                $result = mysqli_query($conn, $query);
                
                while($product = mysqli_fetch_assoc($result)) {
                    echo '<div class="product-card" data-category="' . $product['category'] . '">';
                    echo '<img src="' . $product['image_url'] . '" alt="' . $product['name'] . '">';
                    echo '<h3>' . $product['name'] . '</h3>';
                    echo '<p class="description">' . $product['description'] . '</p>';
                    echo '<p class="price">$' . $product['price'] . '</p>';
                    echo '<div class="product-actions">';
                    echo '<a href="product.php?id=' . $product['id'] . '" class="view-button">View Details</a>';
                    echo '<button class="add-to-cart" data-id="' . $product['id'] . '">Add to Cart</button>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilter = document.getElementById('category-filter');
            const searchInput = document.getElementById('search-input');
            const productCards = document.querySelectorAll('.product-card');

            function filterProducts() {
                const selectedCategory = categoryFilter.value;
                const searchTerm = searchInput.value.toLowerCase();

                productCards.forEach(card => {
                    const category = card.dataset.category;
                    const productName = card.querySelector('h3').textContent.toLowerCase();
                    const productDesc = card.querySelector('.description').textContent.toLowerCase();

                    const matchesCategory = selectedCategory === '' || category === selectedCategory;
                    const matchesSearch = productName.includes(searchTerm) || productDesc.includes(searchTerm);

                    card.style.display = matchesCategory && matchesSearch ? 'block' : 'none';
                });
            }

            categoryFilter.addEventListener('change', filterProducts);
            searchInput.addEventListener('input', filterProducts);
        });

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                <?php if (isset($_SESSION['user_id'])): ?>
                fetch('cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=1`
                })
                .then(response => response.text())
                .then(data => {
                    alert('Product added to cart!');
                })
                .catch(error => console.error('Error:', error));
                <?php else: ?>
                alert('Please login to add items to cart!');
                window.location.href = 'login.php';
                <?php endif; ?>
            });
        });
    </script>
</body>
</html> 