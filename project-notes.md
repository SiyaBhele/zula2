# Zula Project Notes

## 1. Introduction

Zula is a South African Consumer-to-Consumer (C2C) e-commerce platform designed to empower informal traders, students, and township entrepreneurs. It addresses critical gaps in the informal trading sector by providing a structured, secure, and trustworthy marketplace. Key features include seller verification, a robust review system, and dispute management, ensuring accountability and safety for both buyers and sellers. Built to fulfill the Eduvos ITECA3-12 Web Development and e-Commerce project requirements, Zula offers a comprehensive suite of tools, including role-based access control (RBAC) for administrators, dedicated dashboards for sellers to manage inventory and orders, and an intuitive browsing and purchasing experience for buyers. The platform is fully responsive, ensuring accessibility across mobile, tablet, and desktop devices.

## 2. Technical Stack

*   **Backend**: PHP 8+
*   **Database**: MySQL
*   **Frontend**: HTML5, CSS3, JavaScript
*   **Framework/Library**: Bootstrap 5, Font Awesome
*   **Security**: Prepared statements (PDO), password hashing (`password_hash()`), secure PHP sessions.

## 3. Feature List

*   **Authentication**: Registration, login, logout, role-based redirects, session protection.
*   **Main Website**: Homepage, product browsing, product details, cart, checkout, buyer dashboard, order history, profile management, support page.
*   **Seller Dashboard**: Add products, manage products, view seller orders, track product approval status.
*   **Admin Dashboard**: Manage users, assign roles, verify/suspend sellers, approve/reject products, manage categories, view all orders, manage disputes, view reports/statistics.
*   **Product System**: Detailed product listings (ID, seller, category, name, description, price, quantity, condition, location, image, status).
*   **Search & Filters**: Filter by name, category, price range, location, condition.
*   **Cart & Checkout**: Add/remove items, update quantity, simulated payment, order creation.
*   **Order Management**: Track order and payment statuses.
*   **Reviews & Ratings**: Buyer reviews for sellers after completed orders.
*   **Seller Verification**: Admin verification process for sellers.
*   **Dispute Management**: Buyers can open disputes; admins can manage and resolve them.

## 4. Database Table Explanations

*   **`roles`**: Defines user roles (Admin, Moderator, Seller, Buyer).
*   **`users`**: Stores user information, including credentials, role, and seller verification status.
*   **`categories`**: Product categories for organization and filtering.
*   **`products`**: Details of items listed for sale, linked to sellers and categories. Includes approval status.
*   **`carts`**: Active shopping carts linked to users.
*   **`cart_items`**: Items currently in a user's cart.
*   **`orders`**: Completed purchases, including total amount, shipping details, and overall status.
*   **`order_items`**: Specific products purchased within an order, recording the price at the time of purchase.
*   **`reviews`**: Feedback and ratings left by buyers for sellers.
*   **`disputes`**: Issues raised by buyers regarding specific orders.

## 5. Suggested CRC Cards

| Class Name | Responsibilities | Collaborators |
| :--- | :--- | :--- |
| **User** | Manage personal info, authenticate | Role |
| **Buyer** | Browse products, add to cart, checkout, review sellers, open disputes | Product, Cart, Order, Review, Dispute |
| **Seller** | Add/manage products, view orders, manage profile | Product, Order |
| **Admin** | Manage users, approve products, manage categories, resolve disputes | User, Product, Category, Dispute |
| **Product** | Store product details, manage inventory, track approval status | Category, Seller (User) |
| **Order** | Store order details, track status, calculate total | Buyer (User), OrderItem |
| **Review** | Store rating and comment | Buyer (User), Seller (User), Order |
| **Dispute** | Store dispute details, track resolution status | Buyer (User), Order |

## 6. Suggested EERD Relationships

*   **User (1) - (M) Product**: A seller can list many products.
*   **Category (1) - (M) Product**: A category contains many products.
*   **User (1) - (1) Cart**: A user has one active cart.
*   **Cart (1) - (M) CartItem**: A cart contains many items.
*   **Product (1) - (M) CartItem**: A product can be in many carts.
*   **User (1) - (M) Order**: A buyer can place many orders.
*   **Order (1) - (M) OrderItem**: An order contains many items.
*   **Product (1) - (M) OrderItem**: A product can be part of many orders.
*   **Order (1) - (1) Review**: An order can have one review.
*   **Order (1) - (1) Dispute**: An order can have one dispute.

## 7. Suggested Context Diagram Actors

*   **Buyer**: Browses, purchases, reviews, disputes.
*   **Seller**: Lists products, manages inventory, fulfills orders.
*   **Admin**: Moderates platform, manages users/products/disputes.
*   **Payment Gateway (Simulated)**: Processes transactions.

## 8. Suggested DFD Level 0 Processes

1.  **Manage Users**: Registration, login, profile updates, role assignment.
2.  **Manage Products**: Listing, approval, updating, deletion.
3.  **Process Orders**: Cart management, checkout, payment simulation, order creation.
4.  **Manage Feedback**: Submitting reviews, opening disputes, resolving disputes.

## 9. Suggested Use Case Diagram Actors/Use Cases

*   **Buyer**: Register, Login, Browse Products, Search/Filter, Add to Cart, Checkout, View Orders, Leave Review, Open Dispute.
*   **Seller**: Register, Login, Add Product, Edit Product, View Seller Orders.
*   **Admin**: Login, Manage Users, Verify Sellers, Approve Products, Manage Categories, View All Orders, Resolve Disputes, View Reports.

## 10. Coding Sample Explanations

### PHP Login/Auth
The authentication system uses `password_hash()` during registration and `password_verify()` during login to securely handle passwords. Sessions (`$_SESSION`) are used to maintain state across pages. The `requireLogin()` and `requireRole()` functions in `includes/auth.php` enforce access control, redirecting unauthorized users.

### HTML Product Cards
Product cards are built using Bootstrap 5's card component (`.card`). They utilize responsive grid classes (`.col-md-4`, `.col-lg-3`) to ensure a mobile-first layout. Badges (`.badge`) are used to display product condition and status clearly.

### JavaScript Validation
Bootstrap's built-in validation classes (`.needs-validation`, `.was-validated`) are used in conjunction with a small custom JavaScript snippet in `assets/js/script.js` to intercept form submissions and provide immediate visual feedback to the user if required fields are missing or invalid.

### CSS Responsive Design
The custom `assets/css/style.css` file defines CSS variables for consistent branding (colors, fonts). It includes media queries (`@media (max-width: 768px)`) to adjust typography and layout elements specifically for smaller screens, complementing Bootstrap's responsive grid.

### MySQL Schema
The database schema (`database/zula_db.sql`) uses `InnoDB` engine to support foreign key constraints, ensuring referential integrity (e.g., deleting a user cascades to delete their products and carts). `ENUM` types are used for statuses (e.g., 'Pending', 'Approved') to restrict data entry to valid options. Prepared statements are used in all PHP database interactions to prevent SQL injection.
