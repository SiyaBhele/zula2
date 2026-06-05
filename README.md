# Zula E-commerce Platform

## Project Overview

Zula is a Consumer-to-Consumer (C2C) e-commerce marketplace designed for informal traders, students, township entrepreneurs, and ordinary consumers in South Africa. The platform aims to address common challenges in informal online trading, such as lack of trust, seller verification, unsafe payments, unstructured marketplaces, limited delivery coordination, and weak buyer/seller accountability. This project was developed to meet the requirements of the Eduvos ITECA3-12 Web Development and e-Commerce module.

## Features

### Core Features
*   **Authentication System**: Secure user registration, login, logout, role-based redirects, session protection, and password hashing.
*   **Main Website**: Includes a homepage, product browsing, product details, shopping cart, checkout process, buyer dashboard, order history, seller dashboard, and profile management.
*   **Admin Website**: A dedicated administrative interface with Role-Based Access Control (RBAC) for managing users, products, categories, orders, and disputes.
*   **Product System**: Comprehensive product listings with details such as ID, seller ID, category, name, description, price, quantity, condition, location, image, and status.
*   **Search and Filters**: Advanced search capabilities with filtering options by product name, category, price range, location, and condition.
*   **Cart and Checkout**: Functionality to add/remove items from the cart, update quantities, calculate totals, and a simulated payment gateway for academic purposes.
*   **Order Management**: Tracking of order statuses (Pending, Processing, Out for Delivery, Completed, Cancelled, Disputed) and payment statuses (Pending, Paid, Failed, Refunded).
*   **Reviews and Ratings**: Buyers can review sellers after completed orders, with a 1-5 star rating system and comment fields. Average seller ratings are displayed.
*   **Seller Verification**: System to verify or suspend sellers, with a "Verified Seller" badge displayed on product details.
*   **Dispute Management**: Buyers can open disputes for orders, with reasons and descriptions. Admins/moderators can update dispute statuses.
*   **Dashboard Statistics**: Admin dashboard provides total users, sellers, products, pending products, orders, open disputes, and simulated revenue. Seller dashboard shows total products, approved/pending products, orders received, and average rating. Buyer dashboard displays orders placed, active orders, completed orders, and disputes raised.

### Security Measures
*   **Prepared Statements**: Used for all database interactions to prevent SQL injection.
*   **Password Hashing**: Passwords are securely hashed using `password_hash()` and `password_verify()`.
*   **Session Checks**: Robust session management to ensure authenticated access.
*   **Role-Based Access Control**: Prevents unauthorized access to admin and seller-specific pages.
*   **Sanitized Output**: `htmlspecialchars()` is used to prevent XSS attacks.
*   **File Upload Validation**: For product images, only `jpg`, `jpeg`, `png`, `webp` formats are allowed, with a maximum file size of 2MB. Images are stored in `assets/images/products/`.

## Technologies Used

*   **Backend**: PHP 8+
*   **Database**: MySQL
*   **Frontend**: HTML5, CSS3, JavaScript
*   **Framework/Library**: Bootstrap 5, Font Awesome

## Folder Structure

```
zula/
├── index.php
├── login.php
├── register.php
├── logout.php
├── browse.php
├── product-details.php
├── cart.php
├── checkout.php
├── orders.php
├── profile.php
├── support.php
├── includes/
│   ├── db.php
│   ├── auth.php
│   ├── functions.php
│   ├── header.php
│   ├── footer.php
│   └── navbar.php
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── images/
│       └── products/
├── seller/
│   ├── dashboard.php
│   ├── add-product.php
│   ├── manage-products.php
│   ├── edit-product.php
│   └── seller-orders.php
└── admin/
    ├── dashboard.php
    ├── manage-users.php
    ├── add-user.php
    ├── edit-user.php
    ├── manage-roles.php
    ├── manage-products.php
    ├── manage-categories.php
    ├── manage-orders.php
    ├── disputes.php
    └── reports.php
└── database/
    └── zula_db.sql
```

## Database Setup Steps

1.  **Create Database**: Create a MySQL database named `zula_db`.
2.  **Import Schema**: Import the `zula_db.sql` file located in the `database/` directory into your newly created `zula_db` database.
    ```bash
    mysql -u your_username -p zula_db < zula_db.sql
    ```
    (Replace `your_username` with your MySQL username. You will be prompted for your password.)
3.  **Update `db.php`**: If your MySQL username or password is not `root` and empty respectively, update the `$user` and `$pass` variables in `includes/db.php`.

## Default Login Details

For testing purposes, the following default accounts are seeded in the `zula_db.sql` file:

*   **Admin Account**
    *   Email: `admin@zula.co.za`
    *   Password: `Admin@123`
*   **Seller Account**
    *   Email: `seller@zula.co.za`
    *   Password: `Seller@123`
*   **Buyer Account**
    *   Email: `buyer@zula.co.za`
    *   Password: `Buyer@123`

**Note**: Passwords are hashed in the database. The provided passwords are the plain-text versions to use for login.

## How to Run Locally

1.  **Prerequisites**: Ensure you have a web server (like Apache) with PHP 8+ and MySQL installed (e.g., using XAMPP, WAMP, or LAMP stack).
2.  **Clone/Download**: Place the `zula` project folder in your web server's document root (e.g., `htdocs` for XAMPP).
3.  **Database Setup**: Follow the "Database Setup Steps" above.
4.  **Access**: Open your web browser and navigate to `http://localhost/zula/` (or your configured URL).

## How to Host on Free PHP/MySQL Hosting

1.  **Choose a Host**: Select a free PHP/MySQL hosting provider (e.g., 000webhost, InfinityFree). Be aware that free hosting often comes with limitations.
2.  **Upload Files**: Upload all files and folders from the `zula` directory to your hosting account's `public_html` or `htdocs` directory using an FTP client or the host's file manager.
3.  **Create Database**: Create a new MySQL database through your hosting control panel (cPanel, etc.). Note down the database name, username, and password provided by the host.
4.  **Import SQL**: Use phpMyAdmin (usually available in your hosting control panel) to import the `zula_db.sql` file into your new database.
5.  **Update `db.php`**: Edit the `includes/db.php` file with your hosting provider's database credentials (host, db name, user, pass).
6.  **Access**: Your website should now be accessible via your hosting domain.

## Screenshots to Capture for Submission


Ensure all screenshots clearly demonstrate the responsive design and functionality of each page.
