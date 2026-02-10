<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

 $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
 $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rio Digital Product Rating</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header-banner">
        <a href="admin/login.php" class="admin-link">
            <i class="fas fa-user-shield"></i> Admin
        </a>
        <h1>Rio Digital Product Rating</h1>
        <p>Temukan produk elektronik terbaik dengan rating dan review terpercaya</p>
    </header>

    <div class="container">
        <div class="product-grid">
            <?php foreach($products as $index => $product): ?>
                <div class="product-card" style="animation-delay: <?php echo $index * 0.1; ?>s">
                    <img src="assets/images/products/<?php echo $product['image'] ?: 'placeholder.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="product-image">
                    <div class="product-content">
                        <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-footer">
                            <span class="product-price"><?php echo formatRupiah($product['price']); ?></span>
                            <div class="product-rating">
                                <?php echo generateStars($product['rating']); ?>
                                <span style="margin-left: 5px; color: #666;"><?php echo $product['rating']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script src="assets/js/script.js"></script>
</body>
</html>
