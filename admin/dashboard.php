<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

// Check if user is admin (you would typically have an admin flag in users table)
if(!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Get stats for dashboard
$total_properties = $conn->query("SELECT COUNT(*) FROM properties")->fetchColumn();
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_bookings = $conn->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$pending_properties = $conn->query("SELECT COUNT(*) FROM properties WHERE is_approved = 0")->fetchColumn();

// Get recent properties for approval
$query = "SELECT p.*, u.full_name 
          FROM properties p 
          JOIN users u ON p.user_id = u.id 
          WHERE p.is_approved = 0 
          ORDER BY p.created_at DESC 
          LIMIT 5";
$recent_properties = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Get recent users
$recent_users = $conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<div class="container">
    <div class="admin-dashboard">
        <h1>Admin Dashboard</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Properties</h3>
                <p><?php echo $total_properties; ?></p>
            </div>
            
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p><?php echo $total_bookings; ?></p>
            </div>
            
            <div class="stat-card">
                <h3>Pending Properties</h3>
                <p><?php echo $pending_properties; ?></p>
            </div>
        </div>
        
        <div class="admin-sections">
            <div class="admin-section">
                <h2>Pending Properties for Approval</h2>
                
                <?php if(empty($recent_properties)): ?>
                    <div class="alert alert-info">
                        <p>No properties pending approval.</p>
                        <span class="close-alert">&times;</span>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Owner</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_properties as $property): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($property['title']); ?></td>
                                        <td><?php echo htmlspecialchars($property['full_name']); ?></td>
                                        <td><?php echo ucfirst($property['type']); ?></td>
                                        <td>৳<?php echo number_format($property['price']); ?></td>
                                        <td class="action-btns">
                                            <a href="../properties/view.php?id=<?php echo $property['id']; ?>" class="btn btn-outline">View</a>
                                            <a href="approve_property.php?id=<?php echo $property['id']; ?>" class="btn btn-success">Approve</a>
                                            <a href="reject_property.php?id=<?php echo $property['id']; ?>" class="btn btn-danger">Reject</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-right mt-3">
                        <a href="properties.php" class="btn">View All Properties</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="admin-section">
                <h2>Recent Users</h2>
                
                <?php if(empty($recent_users)): ?>
                    <div class="alert alert-info">
                        <p>No users found.</p>
                        <span class="close-alert">&times;</span>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo ucfirst($user['user_type']); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-right mt-3">
                        <a href="users.php" class="btn">View All Users</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>