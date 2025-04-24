<?php
require_once 'config/database.php';

// Update product images
$updates = [
    ['name' => 'Fresh Milk', 'image_url' => 'images/milk.cms'],
    ['name' => 'Premium Cheese', 'image_url' => 'images/cheese.jpg'],
    ['name' => 'Organic Butter', 'image_url' => 'images/Cocoa-Butter-VI-600x400.webp'],
    ['name' => 'Paneer', 'image_url' => 'images/panner.png']
];

foreach ($updates as $update) {
    $query = "UPDATE products SET image_url = ? WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $update['image_url'], $update['name']);
    $stmt->execute();
}

echo "Product images updated successfully!";
?> 