<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

$user_id = $_SESSION['user_id'];

// Get user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's properties if owner
$user_properties = [];
if($_SESSION['user_type'] == 'owner') {
    $query = "SELECT * FROM properties WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);
    $user_properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get user's bookings if seeker
$user_bookings = [];
if($_SESSION['user_type'] == 'seeker') {
    $query = "SELECT b.*, p.title, p.image_path 
              FROM bookings b 
              JOIN properties p ON b.property_id = p.id 
              WHERE b.user_id = ? 
              ORDER BY b.booking_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);
    $user_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get user's messages
$user_messages = [];
$query = "SELECT m.*, p.title as property_title, u.full_name as sender_name 
          FROM messages m 
          JOIN properties p ON m.property_id = p.id 
          JOIN users u ON m.sender_id = u.id 
          WHERE m.receiver_id = ? 
          ORDER BY m.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$user_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle profile update
$update_success = false;
$update_errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if(empty($full_name)) {
        $update_errors[] = "Full name is required";
    }
    
    if(empty($phone)) {
        $update_errors[] = "Phone number is required";
    }
    
    // Check if password is being changed
    if(!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        if(empty($current_password)) {
            $update_errors[] = "Current password is required to change password";
        } elseif(!password_verify($current_password, $user['password'])) {
            $update_errors[] = "Current password is incorrect";
        }
        
        if(empty($new_password)) {
            $update_errors[] = "New password is required";
        } elseif(strlen($new_password) < 6) {
            $update_errors[] = "New password must be at least 6 characters";
        }
        
        if($new_password !== $confirm_password) {
            $update_errors[] = "New passwords do not match";
        }
    }
    
    // If no errors, update profile
    if(empty($update_errors)) {
        if(!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET full_name = ?, phone = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$full_name, $phone, $hashed_password, $user_id]);
        } else {
            $query = "UPDATE users SET full_name = ?, phone = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$full_name, $phone, $user_id]);
        }
        
        // Update session
        $_SESSION['full_name'] = $full_name;
        $user['full_name'] = $full_name;
        $user['phone'] = $phone;
        
        $update_success = true;
    }
}

require_once 'includes/header.php';
?>

<div class="profile-section">
    <div class="container">
        <div class="profile-container">
            <div class="profile-sidebar">
                <div class="profile-img">
                    <img src="<?php echo BASE_URL; ?>assets/images/default-avatar.jpg" alt="<?php echo htmlspecialchars($user['full_name']); ?>">
                </div>
                
                <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p><?php echo ucfirst($user['user_type']); ?></p>
                
                <div class="profile-menu">
                    <ul>
                        <li><a href="#profile" class="active"><i class="fas fa-user"></i> Profile</a></li>
                        <?php if($_SESSION['user_type'] == 'owner'): ?>
                            <li><a href="#properties"><i class="fas fa-home"></i> My Properties</a></li>
                        <?php else: ?>
                            <li><a href="#bookings"><i class="fas fa-calendar-check"></i> My Bookings</a></li>
                        <?php endif; ?>
                        <li><a href="#messages"><i class="fas fa-envelope"></i> Messages</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="profile-content">
                <!-- Profile Update Section -->
                <div id="profile">
                    <h2>Profile Information</h2>
                    
                    <?php if($update_success): ?>
                        <div class="alert alert-success">
                            <p>Profile updated successfully!</p>
                            <span class="close-alert">&times;</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($update_errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach($update_errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                            <span class="close-alert">&times;</span>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" name="full_name" id="full_name" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" required value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="current_password">Current Password (to change password)</label>
                            <input type="password" name="current_password" id="current_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="update_profile" class="btn">Update Profile</button>
                        </div>
                    </form>
                </div>
                
                <!-- Properties Section (for owners) -->
                <?php if($_SESSION['user_type'] == 'owner'): ?>
                    <div id="properties" style="display: none;">
                        <h2>My Properties</h2>
                        
                        <?php if(empty($user_properties)): ?>
                            <div class="alert alert-info">
                                <p>You haven't listed any properties yet.</p>
                                <p><a href="<?php echo BASE_URL; ?>properties/add.php" class="btn btn-outline">Add a Property</a></p>
                                <span class="close-alert">&times;</span>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Property</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($user_properties as $property): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo BASE_URL; ?>properties/view.php?id=<?php echo $property['id']; ?>">
                                                        <?php echo htmlspecialchars($property['title']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo ucfirst($property['type']); ?></td>
                                                <td>৳<?php echo number_format($property['price']); ?></td>
                                                <td>
                                                    <span class="status-badge <?php echo $property['is_approved'] ? 'status-approved' : 'status-pending'; ?>">
                                                        <?php echo $property['is_approved'] ? 'Approved' : 'Pending Approval'; ?>
                                                    </span>
                                                </td>
                                                <td class="action-btns">
                                                    <a href="<?php echo BASE_URL; ?>properties/view.php?id=<?php echo $property['id']; ?>" class="btn btn-outline">View</a>
                                                    <a href="<?php echo BASE_URL; ?>properties/add.php?edit=<?php echo $property['id']; ?>" class="btn">Edit</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-center mt-4">
                                <a href="<?php echo BASE_URL; ?>properties/add.php" class="btn">Add New Property</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- Bookings Section (for seekers) -->
                    <div id="bookings" style="display: none;">
                        <h2>My Bookings</h2>
                        
                        <?php if(empty($user_bookings)): ?>
                            <div class="alert alert-info">
                                <p>You haven't made any bookings yet.</p>
                                <p><a href="<?php echo BASE_URL; ?>properties/search.php" class="btn btn-outline">Browse Properties</a></p>
                                <span class="close-alert">&times;</span>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Property</th>
                                            <th>Dates</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($user_bookings as $booking): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo BASE_URL; ?>properties/view.php?id=<?php echo $booking['property_id']; ?>">
                                                        <?php echo htmlspecialchars($booking['title']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo date('M j, Y', strtotime($booking['start_date'])); ?> - 
                                                    <?php echo date('M j, Y', strtotime($booking['end_date'])); ?>
                                                </td>
                                                <td>৳<?php echo number_format($booking['amount']); ?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo strtolower($booking['payment_status']); ?>">
                                                        <?php echo ucfirst($booking['payment_status']); ?>
                                                    </span>
                                                </td>
                                                <td class="action-btns">
                                                    <a href="<?php echo BASE_URL; ?>properties/view.php?id=<?php echo $booking['property_id']; ?>" class="btn btn-outline">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Messages Section -->
                <div id="messages" style="display: none;">
                    <h2>Messages</h2>
                    
                    <?php if(empty($user_messages)): ?>
                        <div class="alert alert-info">
                            <p>You don't have any messages yet.</p>
                            <span class="close-alert">&times;</span>
                        </div>
                    <?php else: ?>
                        <div class="messages-list">
                            <?php foreach($user_messages as $message): ?>
                                <div class="message-card <?php echo !$message['is_read'] ? 'unread' : ''; ?>">
                                    <div class="message-header">
                                        <h4>
                                            <a href="<?php echo BASE_URL; ?>properties/view.php?id=<?php echo $message['property_id']; ?>">
                                                <?php echo htmlspecialchars($message['property_title']); ?>
                                            </a>
                                        </h4>
                                        <span class="message-date"><?php echo date('M j, Y h:i A', strtotime($message['created_at'])); ?></span>
                                    </div>
                                    
                                    <div class="message-sender">
                                        From: <?php echo htmlspecialchars($message['sender_name']); ?>
                                    </div>
                                    
                                    <div class="message-content">
                                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.profile-menu a');
    const sections = document.querySelectorAll('.profile-content > div');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hide all sections
            sections.forEach(section => {
                section.style.display = 'none';
            });
            
            // Remove active class from all tabs
            tabs.forEach(t => {
                t.classList.remove('active');
            });
            
            // Show selected section and mark tab as active
            const target = this.getAttribute('href').substring(1);
            document.getElementById(target).style.display = 'block';
            this.classList.add('active');
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>