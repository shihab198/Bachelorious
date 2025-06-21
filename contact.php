<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

$database = new Database();
$conn = $database->getConnection();

$message_sent = false;
$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Validate inputs
    if(empty($name)) {
        $errors[] = "Name is required";
    }
    
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if(empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if(empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If no errors, process contact form
    if(empty($errors)) {
        // In a real app, you would send an email here
        $message_sent = true;
    }
}

require_once 'includes/header.php';
?>

<div class="container">
    <div class="contact-section">
        <div class="section-title">
            <h2>Contact Us</h2>
            <p>Have questions? Get in touch with our team</p>
        </div>
        
        <div class="contact-container">
            <div class="contact-info">
                <h3>Contact Information</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 Rental Street, Dhaka, Bangladesh</p>
                <p><i class="fas fa-phone"></i> +880 1234 567890</p>
                <p><i class="fas fa-envelope"></i> info@bachelorious.com</p>
                
                <div class="social-links mt-4">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="contact-form">
                <?php if($message_sent): ?>
                    <div class="alert alert-success">
                        <p>Your message has been sent successfully! We'll get back to you soon.</p>
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
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" required value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea name="message" id="message" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>