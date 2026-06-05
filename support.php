<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$pageTitle = 'Support Center';
include 'includes/header.php';
?>

<div class="container">
    <div class="text-center mb-5">
        <h2 class="fw-bold">How can we help you?</h2>
        <p class="text-muted">Find answers to common questions or get in touch with our team.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <div class="mb-3 text-primary"><i class="fas fa-shopping-cart fa-3x"></i></div>
                <h5 class="fw-bold">Buying on Zula</h5>
                <p class="small text-muted">Learn how to find items, communicate with sellers, and pay securely.</p>
                <a href="#" class="btn btn-link text-decoration-none">Read Articles</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <div class="mb-3 text-warning"><i class="fas fa-store fa-3x"></i></div>
                <h5 class="fw-bold">Selling on Zula</h5>
                <p class="small text-muted">Discover how to list products, manage orders, and grow your business.</p>
                <a href="#" class="btn btn-link text-decoration-none">Read Articles</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <div class="mb-3 text-danger"><i class="fas fa-shield-alt fa-3x"></i></div>
                <h5 class="fw-bold">Safety & Trust</h5>
                <p class="small text-muted">Your safety is our priority. Learn about our verification and dispute systems.</p>
                <a href="#" class="btn btn-link text-decoration-none">Read Articles</a>
            </div>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-lg-6">
            <h4 class="fw-bold mb-4">Frequently Asked Questions</h4>
            <div class="accordion accordion-flush" id="faqAccordion">
                <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Is Zula free to use?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Yes, Zula is free for buyers. Sellers may be charged a small transaction fee on successful sales to help us maintain the platform.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            How do I know if a seller is trustworthy?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Look for the "Verified Seller" badge on profiles and check their ratings and reviews from previous buyers.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            What happens if my item doesn't arrive?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            You can open a dispute through your order history. Our moderation team will investigate and help resolve the issue.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h4 class="fw-bold mb-4">Contact Support</h4>
                <form>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Your Name</label>
                        <input type="text" class="form-control rounded-3" placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" class="form-control rounded-3" placeholder="john@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject</label>
                        <select class="form-select rounded-3">
                            <option>General Inquiry</option>
                            <option>Payment Issue</option>
                            <option>Account Problem</option>
                            <option>Report a Seller</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Message</label>
                        <textarea class="form-control rounded-3" rows="4" placeholder="How can we help?"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary btn-lg w-100 rounded-pill" style="background-color: #00695c; border: none;">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
