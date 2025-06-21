<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

// Check if user is logged in and is a property owner
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'owner') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

$errors = [];
$success = false;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $type = $_POST['type'];
    $address = trim($_POST['address']);
    $city = $_POST['city'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $available_from = $_POST['available_from'];
    
    // Validate inputs
    if(empty($title)) {
        $errors[] = "Title is required";
    }
    
    if(empty($description)) {
        $errors[] = "Description is required";
    }
    
    if(empty($type)) {
        $errors[] = "Property type is required";
    }
    
    if(empty($address)) {
        $errors[] = "Address is required";
    }
    
    if(empty($city)) {
        $errors[] = "City is required";
    }
    
    if(empty($price) || !is_numeric($price) || $price <= 0) {
        $errors[] = "Valid price is required";
    }
    
    if(empty($size) || !is_numeric($size) || $size <= 0) {
        $errors[] = "Valid size is required";
    }
    
    if(empty($bedrooms) || !is_numeric($bedrooms) || $bedrooms <= 0) {
        $errors[] = "Valid number of bedrooms is required";
    }
    
    if(empty($bathrooms) || !is_numeric($bathrooms) || $bathrooms <= 0) {
        $errors[] = "Valid number of bathrooms is required";
    }
    
    if(empty($available_from)) {
        $errors[] = "Available from date is required";
    }
    
    // Handle image upload
    $image_path = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_path = $upload_dir . $file_name;
        
        // Check file type
        $imageFileType = strtolower(pathinfo($target_path, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        
        if(in_array($imageFileType, $allowed_types)) {
            if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = 'uploads/' . $file_name;
            } else {
                $errors[] = "Failed to upload image";
            }
        } else {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed";
        }
    } else {
        $errors[] = "Property image is required";
    }
    
    // If no errors, add property
    if(empty($errors)) {
        $query = "INSERT INTO properties 
                  (user_id, title, description, type, address, city, price, size, bedrooms, bathrooms, available_from, image_path) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if($stmt->execute([
            $_SESSION['user_id'], 
            $title, 
            $description, 
            $type, 
            $address, 
            $city, 
            $price, 
            $size, 
            $bedrooms, 
            $bathrooms, 
            $available_from, 
            $image_path
        ])) {
            $success = true;
        } else {
            $errors[] = "Failed to add property. Please try again.";
        }
    }
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="property-single">
        <h1>Add New Property</h1>
        
        <?php if($success): ?>
            <div class="alert alert-success">
                <p>Property added successfully!</p>
                <p><a href="view.php?id=<?php echo $conn->lastInsertId(); ?>">View Property</a> | <a href="add.php">Add Another</a></p>
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
        
        <form action="add.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Property Title</label>
                <input type="text" name="title" id="title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="type">Property Type</label>
                <select name="type" id="type" required>
                    <option value="">Select Type</option>
                    <option value="house" <?php echo (isset($_POST['type']) && $_POST['type'] == 'house') ? 'selected' : ''; ?>>House</option>
                    <option value="room" <?php echo (isset($_POST['type']) && $_POST['type'] == 'room') ? 'selected' : ''; ?>>Room</option>
                    <option value="seat" <?php echo (isset($_POST['type']) && $_POST['type'] == 'seat') ? 'selected' : ''; ?>>Seat</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" id="address" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="city">City</label>
                <select name="city" id="city" required>
                    <option value="">Select City</option>
                    <option value="dhaka" <?php echo (isset($_POST['city']) && $_POST['city'] == 'dhaka') ? 'selected' : ''; ?>>Dhaka</option>
                    <option value="chittagong" <?php echo (isset($_POST['city']) && $_POST['city'] == 'chittagong') ? 'selected' : ''; ?>>Chittagong</option>
                    <option value="sylhet" <?php echo (isset($_POST['city']) && $_POST['city'] == 'sylhet') ? 'selected' : ''; ?>>Sylhet</option>
                    <option value="khulna" <?php echo (isset($_POST['city']) && $_POST['city'] == 'khulna') ? 'selected' : ''; ?>>Khulna</option>
                    <option value="rajshahi" <?php echo (isset($_POST['city']) && $_POST['city'] == 'rajshahi') ? 'selected' : ''; ?>>Rajshahi</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Monthly Rent (৳)</label>
                    <input type="number" name="price" id="price" required value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="size">Size (sqft)</label>
                    <input type="number" name="size" id="size" required value="<?php echo isset($_POST['size']) ? htmlspecialchars($_POST['size']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="bedrooms">Bedrooms</label>
                    <input type="number" name="bedrooms" id="bedrooms" required min="1" value="<?php echo isset($_POST['bedrooms']) ? htmlspecialchars($_POST['bedrooms']) : '1'; ?>">
                </div>
                
                <div class="form-group">
                    <label for="bathrooms">Bathrooms</label>
                    <input type="number" name="bathrooms" id="bathrooms" required min="1" value="<?php echo isset($_POST['bathrooms']) ? htmlspecialchars($_POST['bathrooms']) : '1'; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="available_from">Available From</label>
                <input type="date" name="available_from" id="available_from" required value="<?php echo isset($_POST['available_from']) ? htmlspecialchars($_POST['available_from']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="image">Property Image</label>
                <input type="file" name="image" id="image" required accept="image/*">
                <small>Upload a clear photo of your property (max 2MB)</small>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Add Property</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>