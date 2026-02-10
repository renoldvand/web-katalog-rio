<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/db.php';
require_once '../includes/functions.php';

// Get statistics
 $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
 $avgRating = $pdo->query("SELECT AVG(rating) FROM products")->fetchColumn();
 $totalValue = $pdo->query("SELECT SUM(price) FROM products")->fetchColumn();

// Get recent products
 $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
 $recentProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="assets/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="manage-products.php"><i class="fas fa-box"></i> Kelola Produk</a></li>
                <li><a href="../index.php" target="_blank"><i class="fas fa-eye"></i> Lihat Website</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="content-header">
                <h1>Selamat Datang, Admin!</h1>
                <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div class="stat-card" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h3 style="color: #666; font-size: 0.9rem; margin-bottom: 10px;">Total Produk</h3>
                            <p style="font-size: 2rem; font-weight: bold; color: #667eea;"><?php echo $totalProducts; ?></p>
                        </div>
                        <i class="fas fa-box" style="font-size: 2.5rem; color: #667eea; opacity: 0.3;"></i>
                    </div>
                </div>
                
                <div class="stat-card" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h3 style="color: #666; font-size: 0.9rem; margin-bottom: 10px;">Rating Rata-rata</h3>
                            <p style="font-size: 2rem; font-weight: bold; color: #ffc107;"><?php echo number_format($avgRating, 1); ?></p>
                        </div>
                        <i class="fas fa-star" style="font-size: 2.5rem; color: #ffc107; opacity: 0.3;"></i>
                    </div>
                </div>
                
                <div class="stat-card" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h3 style="color: #666; font-size: 0.9rem; margin-bottom: 10px;">Total Nilai</h3>
                            <p style="font-size: 1.5rem; font-weight: bold; color: #28a745;"><?php echo formatRupiah($totalValue); ?></p>
                        </div>
                        <i class="fas fa-chart-line" style="font-size: 2.5rem; color: #28a745; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
            
            <div class="product-table">
                <h2 style="padding: 20px; border-bottom: 2px solid #eee;">Produk Terbaru</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Rating</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentProducts as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                                <td><?php echo formatRupiah($product['price']); ?></td>
                                <td>
                                    <span style="color: #ffc107;">
                                        <?php echo generateStars($product['rating']); ?> <?php echo $product['rating']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($product['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
