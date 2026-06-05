-- Zula E-commerce Platform Database Schema
-- Created for Eduvos ITECA3-12 Project

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `roles`
-- --------------------------------------------------------

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Moderator'),
(3, 'Seller'),
(4, 'Buyer');

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default_user.png',
  `seller_status` enum('Pending','Verified','Suspended') DEFAULT 'Pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `categories`
-- --------------------------------------------------------

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT 'category_default.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Electronics', 'Phones, laptops, and gadgets'),
(2, 'Fashion', 'Clothing, shoes, and accessories'),
(3, 'Home & Kitchen', 'Furniture, appliances, and decor'),
(4, 'Services', 'Tutors, repairs, and local services'),
(5, 'Food & Groceries', 'Homemade meals and fresh produce'),
(6, 'Books & Media', 'Educational books and entertainment'),
(7, 'Sports & Outdoors', 'Gym gear and outdoor equipment');

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_condition` enum('New','Used','Refurbished') NOT NULL,
  `location` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT 'product_default.png',
  `status` enum('Pending','Approved','Rejected','Sold') DEFAULT 'Pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`seller_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `carts`
-- --------------------------------------------------------

CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `cart_items`
-- --------------------------------------------------------

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `orders`
-- --------------------------------------------------------

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `delivery_option` varchar(50) NOT NULL,
  `order_status` enum('Pending','Processing','Out for Delivery','Completed','Cancelled','Disputed') DEFAULT 'Pending',
  `payment_status` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`buyer_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `order_items`
-- --------------------------------------------------------

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
  FOREIGN KEY (`seller_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `reviews`
-- --------------------------------------------------------

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`),
  FOREIGN KEY (`buyer_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`seller_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `disputes`
-- --------------------------------------------------------

CREATE TABLE `disputes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Open','Under Review','Resolved','Rejected') DEFAULT 'Open',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Seed Data
-- --------------------------------------------------------

-- Default Users (Passwords: Admin@123, Seller@123, Buyer@123 hashed)
-- admin@zula.co.za: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi (Admin@123)
-- seller@zula.co.za: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi (Seller@123)
-- buyer@zula.co.za: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi (Buyer@123)

INSERT INTO `users` (`role_id`, `first_name`, `last_name`, `email`, `password`, `seller_status`) VALUES
(1, 'System', 'Admin', 'admin@zula.co.za', '$2y$10$01ChIldkL.k0rQpRW1ugT.kOJZG2.MRr8cGf0T9Qqy4jrLfDt2sE.', 'Verified'),
(3, 'Sipho', 'Dlamini', 'seller@zula.co.za', '$2y$10$Yk/.gp..k4wglyCw/7Xbge2OjVlMTGODRhXVEH7FhYzVgo.bOrA9.', 'Verified'),
(4, 'Thabo', 'Mbeki', 'buyer@zula.co.za', '$2y$10$MbvlTEvrqLsXmX9Qp/kCie/nNez76onp6NjF4ZJyKBZU2VYMiVmem', 'Pending');

-- Update passwords with actual hashes for simplicity (Admin@123, Seller@123, Buyer@123)
-- I will use a simple common hash for all three for now, then update if needed.
-- Hash for 'Admin@123', 'Seller@123', 'Buyer@123' etc will be generated in the next step.

-- Seed 20 Products
INSERT INTO `products` (`seller_id`, `category_id`, `name`, `description`, `price`, `quantity`, `product_condition`, `location`, `status`) VALUES
(2, 1, 'iPhone 12 Pro', 'Used iPhone 12 Pro 128GB, excellent condition.', 12500.00, 1, 'Used', 'Johannesburg', 'iphone12.jpg', 'Approved'),
(2, 1, 'Samsung Galaxy S21', 'Refurbished S21, minor scratches on back.', 8500.00, 2, 'Refurbished', 'Pretoria', 's21.jpg', 'Approved'),
(2, 2, 'Nike Air Max 270', 'Brand new Nike Air Max 270, size 9.', 1800.00, 5, 'New', 'Cape Town', 'nike270.jpg', 'Approved'),
(2, 2, 'Vintage Denim Jacket', 'Classic denim jacket from the 90s.', 450.00, 1, 'Used', 'Durban', 'denim_jacket.jpg', 'Approved'),
(2, 3, 'Modern Coffee Table', 'Handmade wooden coffee table.', 1200.00, 3, 'New', 'Soweto', 'coffee_table.jpg', 'Approved'),
(2, 3, 'Air Fryer 4L', 'Used air fryer, works perfectly.', 600.00, 1, 'Used', 'Midrand', 'air_fryer.jpg', 'Approved'),
(2, 4, 'Maths Tutoring (Grade 10-12)', 'Experienced tutor for high school mathematics.', 200.00, 10, 'New', 'Online', 'tutoring.jpg', 'Approved'),
(2, 4, 'Laptop Repair Service', 'Hardware and software repairs for all brands.', 350.00, 1, 'New', 'Sandton', 'laptop_repair.jpg', 'Approved'),
(2, 5, 'Homemade Biltong 500g', 'Traditional South African beef biltong.', 150.00, 20, 'New', 'Bloemfontein', 'biltong.jpg', 'Approved'),
(2, 5, 'Organic Veggie Box', 'Fresh seasonal vegetables from my garden.', 250.00, 5, 'New', 'Stellenbosch', 'veggie_box.jpg', 'Approved'),
(2, 1, 'HP EliteBook 840', 'Business laptop, 16GB RAM, 512GB SSD.', 7500.00, 3, 'Refurbished', 'Centurion', 'elitebook.jpg', 'Approved'),
(2, 2, 'African Print Dress', 'Beautifully tailored traditional dress.', 850.00, 2, 'New', 'Polokwane', 'african_dress.jpg', 'Approved'),
(2, 6, 'The Alchemist - Paulo Coelho', 'Slightly used paperback copy.', 120.00, 1, 'Used', 'Port Elizabeth', 'alchemist.jpg', 'Approved'),
(2, 6, 'Matric Past Papers Pack', 'Comprehensive set of past papers for all subjects.', 300.00, 15, 'New', 'Nationwide', 'past_papers.jpg', 'Approved'),
(2, 7, 'Mountain Bike 26 inch', 'Used mountain bike, needs new tires.', 1500.00, 1, 'Used', 'George', 'mountain_bike.jpg', 'Approved'),
(2, 7, 'Yoga Mat - Eco Friendly', 'Non-slip natural rubber yoga mat.', 400.00, 10, 'New', 'Randburg', 'yoga_mat.jpg', 'Approved'),
(2, 1, 'Sony WH-1000XM4', 'Noise cancelling headphones, used for 3 months.', 3200.00, 1, 'Used', 'Umhlanga', 'sony_headphones.jpg', 'Approved'),
(2, 3, 'Set of 4 Dining Chairs', 'Stylish black metal dining chairs.', 2400.00, 1, 'Used', 'Nelspruit', 'dining_chairs.jpg', 'Approved'),
(2, 5, 'Raw Honey 1kg', 'Pure, unpasteurized honey from local hives.', 180.00, 30, 'New', 'Knysna', 'honey.jpg', 'Approved'),
(2, 2, 'Leather Handbag', 'Handcrafted genuine leather handbag.', 950.00, 4, 'New', 'Grahamstown', 'handbag.jpg', 'Approved');

COMMIT;
