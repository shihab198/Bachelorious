<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . BASE_URL . "properties/search.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

$property_id = $_GET['id'];

// Get property details
$query = "SELECT * FROM properties WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$property_id]);
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$property) {
    header("Location: " . BASE_URL . "properties/search.php");
    exit();
}

// Handle payment form submission
$payment_success = false;
$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_number = str_replace(' ', '', $_POST['card_number']);
    $card_expiry = $_POST['card_expiry'];
    $card_cvv = $_POST['card_cvv'];
    $amount = $property['price']; // Simple calculation - in real app you might calculate based on dates
    
    // Validate inputs
    if(empty($card_number) || strlen($card_number) != 16 || !is_numeric($card_number)) {
        $errors[] = "Valid card number is required";
    }
    
    if(empty($card_expiry) || !preg_match('/^\d{2}\/\d{2}$/', $card_expiry)) {
        $errors[] = "Valid expiry date (MM/YY) is required";
    }
    
    if(empty($card_cvv) || strlen($card_cvv) != 3 || !is_numeric($card_cvv)) {
        $errors[] = "Valid CVV is required";
    }
    
    // If no errors, process payment
    if(empty($errors)) {
        // In a real app, you would integrate with a payment gateway here
        // For this demo, we'll just simulate a successful payment
        
        // Create booking record
        $query = "INSERT INTO bookings 
                  (property_id, user_id, booking_date, start_date, end_date, amount, payment_status, transaction_id) 
                  VALUES (?, ?, NOW(), ?, ?, ?, 'completed', ?)";
        $stmt = $conn->prepare($query);
        
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime('+1 month'));
        $transaction_id = 'PAY-' . uniqid();
        
        if($stmt->execute([
            $property_id, 
            $_SESSION['user_id'], 
            $start_date, 
            $end_date, 
            $amount, 
            $transaction_id
        ])) {
            $payment_success = true;
        } else {
            $errors[] = "Payment processing failed. Please try again.";
        }
    }
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="payment-section">
        <h1>Complete Your Booking</h1>
        <p>You're booking: <strong><?php echo htmlspecialchars($property['title']); ?></strong></p>
        
        <?php if($payment_success): ?>
            <div class="alert alert-success">
                <h3>Payment Successful!</h3>
                <p>Your booking has been confirmed.</p>
                <p>Transaction ID: <?php echo $transaction_id; ?></p>
                <p>Amount Paid: ৳<?php echo number_format($amount); ?></p>
                <p><a href="../profile.php" class="btn">View Your Bookings</a></p>
                <span class="close-alert">&times;</span>
            </div>
        <?php else: ?>
            <?php if(!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                    <span class="close-alert">&times;</span>
                </div>
            <?php endif; ?>
            
            <div class="payment-container">
                <div class="payment-summary">
                    <h3>Booking Summary</h3>
                    <div class="summary-item">
                        <span>Property:</span>
                        <span><?php echo htmlspecialchars($property['title']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Type:</span>
                        <span><?php echo ucfirst($property['type']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Location:</span>
                        <span><?php echo htmlspecialchars($property['address']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Monthly Rent:</span>
                        <span>৳<?php echo number_format($property['price']); ?></span>
                    </div>
                    <div class="summary-item total">
                        <span>Total Amount:</span>
                        <span>৳<?php echo number_format($property['price']); ?></span>
                    </div>
                </div>
                
                <div class="payment-form">
                    <h3>Payment Information</h3>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" name="card_number" id="card_number" placeholder="1234 5678 9012 3456" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card_expiry">Expiry Date</label>
                                <input type="text" name="card_expiry" id="card_expiry" placeholder="MM/YY" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="card_cvv">CVV</label>
                                <input type="text" name="card_cvv" id="card_cvv" placeholder="123" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Pay ৳<?php echo number_format($property['price']); ?></button>
                        </div>
                        
                        <div class="payment-security">
                            <p><i class="fas fa-lock"></i> Your payment is secure and encrypted</p>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Format card number input
document.getElementById('card_number').addEventListener('input', function(e) {
    this.value = this.value.replace(/\s/g, '').replace(/(\d{4})/g, '$1 ').trim();
});

// Format expiry date input
document.getElementById('card_expiry').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').replace(/(\d{2})(\d{0,2})/, '$1/$2');
});
</script>

<?php require_once '../includes/footer.php'; ?>