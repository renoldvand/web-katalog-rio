<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/db.php';
require_once '../includes/functions.php';

 $message = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "Produk berhasil dihapus!";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($_POST['name']);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    $price = floatval($_POST['price']);
    $rating = floatval($_POST['rating']);
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = $_POST['id'];
        $image = $_POST['existing_image'];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $newImage = uploadImage($_FILES['image']);
            if ($newImage) {
                $image = $newImage;
            }
        }
        
        $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, description=?, price=?, rating=?, image=? WHERE id=?");
        if ($stmt->execute([$name, $category, $description, $price, $rating, $image, $id])) {
            $message = "Produk berhasil diperbarui!";
        }
    } else {
        // Insert
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = uploadImage($_FILES['image']);
        }
        
        $stmt = $pdo->prepare("INSERT INTO products (name, category, description, price, rating, image) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $category, $description, $price, $rating, $image])) {
            $message = "Produk berhasil ditambahkan!";
        }
    }
}

// Get all products
 $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
 $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get product for editing
 $editProduct = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin</title>
    <link rel="stylesheet" href="assets/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="manage-products.php" class="active"><i class="fas fa-box"></i> Kelola Produk</a></li>
                <li><a href="../index.php" target="_blank"><i class="fas fa-eye"></i> Lihat Website</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="content-header">
                <h1>Kelola Produk</h1>
                <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <h2><?php echo $editProduct ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h2>
                <form method="POST" enctype="multipart/form-data">
                    <?php if ($editProduct): ?>
                        <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo $editProduct['image']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?php echo $editProduct['name'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Smartphone" <?php echo ($editProduct['category'] ?? '') == 'Smartphone' ? 'selected' : ''; ?>>Smartphone</option>
                            <option value="Laptop" <?php echo ($editProduct['category'] ?? '') == 'Laptop' ? 'selected' : ''; ?>>Laptop</option>
                            <option value="Tablet" <?php echo ($editProduct['category'] ?? '') == 'Tablet' ? 'selected' : ''; ?>>Tablet</option>
                            <option value="Audio" <?php echo ($editProduct['category'] ?? '') == 'Audio' ? 'selected' : ''; ?>>Audio</option>
                            <option value="Gaming" <?php echo ($editProduct['category'] ?? '') == 'Gaming' ? 'selected' : ''; ?>>Gaming</option>
                            <option value="Aksesoris" <?php echo ($editProduct['category'] ?? '') == 'Aksesoris' ? 'selected' : ''; ?>>Aksesoris</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" required><?php echo $editProduct['description'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Harga (Rp)</label>
                        <input type="number" id="price" name="price" class="form-control" 
                               value="<?php echo $editProduct['price'] ?? ''; ?>" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="rating">Rating (0-5)</label>
                        <input type="number" id="rating" name="rating" class="form-control" 
                               value="<?php echo $editProduct['rating'] ?? ''; ?>" min="0" max="5" step="0.1" required>
                    </div>

                    <script>
                    document.querySelector('form').addEventListener('submit', function(e) {
                        const fileInput = document.getElementById('image');
                        const file = fileInput.files[0];
                        
                        if (file) {
                            // Check file size (5MB max)
                            if (file.size > 5 * 1024 * 1024) {
                                e.preventDefault();
                                alert('File terlalu besar! Maksimal 5MB.');
                                return;
                            }
                            
                            // Check file type
                            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                            if (!allowedTypes.includes(file.type)) {
                                e.preventDefault();
                                alert('Tipe file tidak diizinkan! Gunakan JPG, PNG, GIF, atau WebP.');
                                return;
                            }
                        }
                    });
                    </script>
                    
                    <div class="form-group">
                        <label for="image">Gambar Produk</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                        <small style="color: #666; display: block; margin-top: 5px;">
                            Format: JPG, PNG, GIF, WebP | Maksimal: 5MB
                        </small>
                        <?php if ($editProduct && $editProduct['image']): ?>
                            <div style="margin-top: 10px;">
                                <small style="color: #666;">Gambar saat ini:</small><br>
                                <img src="../assets/images/products/<?php echo $editProduct['image']; ?>" 
                                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; margin-top: 5px;">
                                <br>
                                <small style="color: #666;"><?php echo $editProduct['image']; ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> <?php echo $editProduct ? 'Update Produk' : 'Simpan Produk'; ?>
                    </button>
                </form>
            </div>
            
            <div class="product-table" style="margin-top: 30px;">
                <h2 style="padding: 20px; border-bottom: 2px solid #eee;">Daftar Produk</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Rating</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="../assets/images/products/<?php echo $product['image'] ?: 'placeholder.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                </td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                                <td><?php echo formatRupiah($product['price']); ?></td>
                                <td>
                                    <span style="color: #ffc107;">
                                        <?php echo generateStars($product['rating']); ?> <?php echo $product['rating']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="?edit=<?php echo $product['id']; ?>" class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?delete=<?php echo $product['id']; ?>" 
                                           class="btn-delete" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
