<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . BASE_URL . "properties/search.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

$property_id = $_GET['id'];

// Get property details
$query = "SELECT p.*, u.full_name, u.phone, u.email 
          FROM properties p 
          JOIN users u ON p.user_id = u.id 
          WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$property_id]);
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$property) {
    header("Location: " . BASE_URL . "properties/search.php");
    exit();
}

// Get additional images (in a real app, you might have multiple images)
$images = [$property['image_path']];

// Handle contact form submission
$message_sent = false;
$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);
    
    // Validate inputs
    if(empty($name)) {
        $errors[] = "Name is required";
    }
    
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if(empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if(empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If no errors, save message
    if(empty($errors)) {
        $query = "INSERT INTO messages (sender_id, receiver_id, property_id, message) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        $sender_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        if($stmt->execute([$sender_id, $property['user_id'], $property_id, $message])) {
            $message_sent = true;
            
            // In a real app, you would send an email notification here
        } else {
            $errors[] = "Failed to send message. Please try again.";
        }
    }
}

// Handle booking form submission
$booking_success = false;
$booking_errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_property'])) {
    if(!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "auth/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
    
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $amount = $property['price']; // Simple calculation
    
    // Validate inputs
    if(empty($start_date)) {
        $booking_errors[] = "Start date is required";
    }
    
    if(empty($end_date)) {
        $booking_errors[] = "End date is required";
    }
    
    if($start_date > $end_date) {
        $booking_errors[] = "End date must be after start date";
    }
    
    // Check if property is available for the selected dates
    // In a real app, you would check against existing bookings
    
    // If no errors, process booking
    if(empty($booking_errors)) {
        // In a real app, you would process payment here
        // For this demo, we'll just create a booking record
        
        $query = "INSERT INTO bookings 
                  (property_id, user_id, start_date, end_date, amount, payment_status, transaction_id) 
                  VALUES (?, ?, ?, ?, ?, 'completed', ?)";
        $stmt = $conn->prepare($query);
        
        $transaction_id = 'DEMO-' . uniqid();
        
        if($stmt->execute([
            $property_id, 
            $_SESSION['user_id'], 
            $start_date, 
            $end_date, 
            $amount, 
            $transaction_id
        ])) {
            $booking_success = true;
        } else {
            $booking_errors[] = "Failed to process booking. Please try again.";
        }
    }
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="property-single">
        <div class="property-single-content">
            <div class="property-main">
                <h1><?php echo htmlspecialchars($property['title']); ?></h1>
                
                <div class="property-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo htmlspecialchars($property['address']) . ', ' . htmlspecialchars(ucfirst($property['city'])); ?>
                </div>
                
                <div class="property-gallery">
                    <div class="main-image">
                        <img src="<?php echo $property['image_path'] ? BASE_URL . $property['image_path'] : BASE_URL . 'assets/images/default-property.jpg'; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                    </div>
                    
                    <?php if(count($images) > 1): ?>
                        <div class="thumbnail-images">
                            <?php foreach($images as $image): ?>
                                <img src="<?php echo BASE_URL . $image; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="property-details">
                    <h3>Details</h3>
                    
                    <div class="property-features">
                        <div>
                            <i class="fas fa-home"></i>
                            <span>Type: <?php echo ucfirst($property['type']); ?></span>
                        </div>
                        <div>
                            <i class="fas fa-ruler-combined"></i>
                            <span>Size: <?php echo $property['size']; ?> sqft</span>
                        </div>
                        <div>
                            <i class="fas fa-bed"></i>
                            <span>Bedrooms: <?php echo $property['bedrooms']; ?></span>
                        </div>
                        <div>
                            <i class="fas fa-bath"></i>
                            <span>Bathrooms: <?php echo $property['bathrooms']; ?></span>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt"></i>
                            <span>Available from: <?php echo date('M j, Y', strtotime($property['available_from'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="property-description">
                        <h3>Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="contact-form mt-5">
                    <h3>Contact Owner</h3>
                    
                    <?php if($message_sent): ?>
                        <div class="alert alert-success">
                            <p>Your message has been sent successfully!</p>
                            <span class="close-alert">&times;</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                            <span class="close-alert">&times;</span>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input type="text" name="name" id="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : (isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Your Email</label>
                                <input type="email" name="email" id="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : (isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : (isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="send_message" class="btn">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="property-sidebar">
                <!-- Booking Card -->
                <div class="booking-card">
                    <h3>Book This Property</h3>
                    
                    <?php if($booking_success): ?>
                        <div class="alert alert-success">
                            <p>Your booking has been confirmed!</p>
                            <p>Transaction ID: <?php echo $transaction_id; ?></p>
                            <span class="close-alert">&times;</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($booking_errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach($booking_errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                            <span class="close-alert">&times;</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="price">
                        ৳<?php echo number_format($property['price']); ?> <span>/month</span>
                    </div>
                    
                    <form action="" method="POST" class="booking-form">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="book_property" class="btn btn-success">Book Now</button>
                        </div>
                    </form>
                </div>
                
                <!-- Owner Card -->
                <div class="owner-card">
                    <h3>Property Owner</h3>
                    
                    <div class="owner-info">
                        <div class="owner-img">
                            <img src="<?php echo BASE_URL; ?>assets/images/default-avatar.jpg" alt="<?php echo htmlspecialchars($property['full_name']); ?>">
                        </div>
                        
                        <div class="owner-details">
                            <h4><?php echo htmlspecialchars($property['full_name']); ?></h4>
                            <p>Property Owner</p>
                        </div>
                    </div>
                    
                    <div class="owner-meta">
                        <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($property['phone']); ?></span>
                        <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($property['email']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>