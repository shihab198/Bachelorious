<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

$database = new Database();
$conn = $database->getConnection();

$errors = [];
$success = false;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $user_type = $_POST['user_type'];
    
    // Validate inputs
    if(empty($username)) {
        $errors[] = "Username is required";
    } elseif(strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters";
    } else {
        // Check if username exists
        $query = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$username]);
        if($stmt->rowCount() > 0) {
            $errors[] = "Username already taken";
        }
    }
    
    if(empty($email)) {
        $errors[] = "Email is required";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        // Check if email exists
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0) {
            $errors[] = "Email already registered";
        }
    }
    
    if(empty($password)) {
        $errors[] = "Password is required";
    } elseif(strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if(empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if(empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if(empty($user_type)) {
        $errors[] = "Please select user type";
    }
    
    // If no errors, register user
    if(empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (username, email, password, full_name, phone, user_type) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if($stmt->execute([$username, $email, $hashed_password, $full_name, $phone, $user_type])) {
            $success = true;
            
            // Auto login after registration
            $user_id = $conn->lastInsertId();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user_type;
            $_SESSION['full_name'] = $full_name;
            
            header("Location: " . BASE_URL . "profile.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}

require_once '../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Create an Account</h2>
        
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
                <span class="close-alert">&times;</span>
            </div>
        <?php endif; ?>
        
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" required>
                    <i class="fas fa-eye password-toggle" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="user_type">I am a:</label>
                <select name="user_type" id="user_type" required>
                    <option value="">Select Type</option>
                    <option value="owner" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'owner') ? 'selected' : ''; ?>>Property Owner</option>
                    <option value="seeker" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'seeker') ? 'selected' : ''; ?>>Home Seeker</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
            
            <div class="form-footer">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>