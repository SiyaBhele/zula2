<?php
/**
 * Sanitize output
 */
function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Format price
 */
function formatPrice($price) {
    return 'R ' . number_format($price, 2);
}

/**
 * Get categories from DB
 */
function getCategories($pdo) {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll();
}

/**
 * Get product status badge class
 */
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Approved': return 'bg-success';
        case 'Pending': return 'bg-warning text-dark';
        case 'Rejected': return 'bg-danger';
        case 'Sold': return 'bg-info';
        default: return 'bg-secondary';
    }
}

/**
 * Get order status badge class
 */
function getOrderStatusBadgeClass($status) {
    switch ($status) {
        case 'Completed': return 'bg-success';
        case 'Pending': return 'bg-warning text-dark';
        case 'Processing': return 'bg-primary';
        case 'Out for Delivery': return 'bg-info';
        case 'Cancelled': return 'bg-danger';
        case 'Disputed': return 'bg-dark';
        default: return 'bg-secondary';
    }
}
?>
