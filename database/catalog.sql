-- Database: rio_digital_catalog
CREATE DATABASE IF NOT EXISTS `rio_digital_catalog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rio_digital_catalog`;

-- Table structure for table `admin_users`
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO `admin_users` (`username`, `password`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Table structure for table `products`
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT '0.0',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample products
-- INSERT INTO `products` (`name`, `category`, `description`, `price`, `rating`, `image`) VALUES
-- ('iPhone 15 Pro Max', 'Smartphone', 'iPhone terbaru dengan chip A17 Pro, kamera 48MP, dan layar Super Retina XDR 6.7 inci', 19999000, 4.8, 'iphone15.jpg'),
-- ('MacBook Pro 16"', 'Laptop', 'Laptop profesional dengan M3 Max chip, 36GB RAM, dan layar Liquid Retina XDR', 45999000, 4.9, 'macbookpro.jpg'),
-- ('Samsung Galaxy S24 Ultra', 'Smartphone', 'Flagship Android dengan S Pen, kamera 200MP, dan layar Dynamic AMOLED 2X', 17999000, 4.7, 's24ultra.jpg'),
-- ('Dell XPS 15', 'Laptop', 'Laptop premium dengan Intel Core i9, RTX 4070, dan layar OLED 4K', 32999000, 4.6, 'dellxps.jpg'),
-- ('iPad Pro 12.9"', 'Tablet', 'Tablet profesional dengan M2 chip, layar Liquid Retina XDR, dan support Apple Pencil', 15999000, 4.8, 'ipadpro.jpg'),
-- ('Sony WH-1000XM5', 'Audio', 'Headphone noise cancelling terbaik dengan kualitas suara premium dan battery 30 jam', 4999000, 4.7, 'sonywh1000xm5.jpg');
